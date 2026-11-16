<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroPacienteRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\Role;
use App\Models\Usuario;
use App\Notifications\BienvenidaPacienteNotification;
use App\Notifications\RecuperarContrasenaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectPorRol(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ], [
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico ingresado no es válido.',
            'contrasena.required' => 'La contraseña es obligatoria.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($credenciales['correo']) . '|' . $request->ip());

        // Protección contra ataques de fuerza bruta (5 intentos por minuto)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $errorMsg = "Demasiados intentos de acceso fallidos. Por favor inténtelo nuevamente en {$seconds} segundos.";

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 429);
            }
            return back()->withErrors(['correo' => $errorMsg]);
        }

        $usuario = Usuario::where('correo', $credenciales['correo'])->first();

        // Autenticación estricta con Bcrypt
        if ($usuario && Hash::check($credenciales['contrasena'], $usuario->contrasena)) {
            // Verificar si el usuario se encuentra desactivado
            if (!$usuario->activo || (method_exists($usuario, 'trashed') && $usuario->trashed())) {
                RateLimiter::hit($throttleKey);
                $inactivoMsg = 'Su cuenta se encuentra inactiva. Por favor comuníquese con la administración de DentalCare.';

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $inactivoMsg], 403);
                }
                return back()->withErrors(['correo' => $inactivoMsg]);
            }

            RateLimiter::clear($throttleKey);

            Auth::login($usuario, $request->boolean('remember'));
            $request->session()->regenerate();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '¡Bienvenido(a), ' . $usuario->nombre . '!',
                    'user' => [
                        'id' => $usuario->id,
                        'nombre' => $usuario->nombre,
                        'correo' => $usuario->correo,
                        'rol' => $usuario->nombre_rol,
                        'debe_cambiar_contrasena' => (bool) $usuario->debe_cambiar_contrasena,
                    ],
                    'redirect' => $this->rutaPorRol($usuario),
                ]);
            }

            return $this->redirectPorRol($usuario);
        }

        RateLimiter::hit($throttleKey);
        $falloMsg = 'Las credenciales proporcionadas no coinciden con nuestros registros.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $falloMsg], 401);
        }

        return back()->withErrors(['correo' => $falloMsg])->withInput($request->except('contrasena'));
    }

    public function showRegister()
    {
        if (!config('app.registro_publico', true)) {
            return redirect()->route('login')->with('warning', 'El registro público de pacientes está deshabilitado temporalmente.');
        }

        if (Auth::check()) {
            return $this->redirectPorRol(Auth::user());
        }

        return view('auth.register');
    }

    public function register(RegistroPacienteRequest $request)
    {
        if (!config('app.registro_publico', true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'El registro público se encuentra inhabilitado.'], 403);
            }
            abort(403, 'El registro público de pacientes se encuentra deshabilitado.');
        }

        $data = $request->validated();

        $pacienteRole = Role::where('nombre', Role::PACIENTE)->first();
        $roleId = $pacienteRole ? $pacienteRole->id : 4;

        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'correo' => $data['correo'],
            'contrasena' => Hash::make($data['contrasena']),
            'telefono' => $data['telefono'],
            'documento_identidad' => $data['documento_identidad'] ?? null,
            'role_id' => $roleId,
            'rol' => 'paciente',
            'faltas_acumuladas' => 0,
            'activo' => true,
            'debe_cambiar_contrasena' => false,
        ]);

        // Envío de correo de bienvenida al paciente
        try {
            $usuario->notify(new BienvenidaPacienteNotification());
        } catch (\Throwable $e) {
            // No interrumpir el registro si el servidor de correo local no responde
        }

        Auth::login($usuario);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Registro exitoso! Bienvenido(a) a DentalCare.',
                'user' => [
                    'id' => $usuario->id,
                    'nombre' => $usuario->nombre,
                    'correo' => $usuario->correo,
                    'rol' => Role::PACIENTE,
                ],
                'redirect' => route('paciente.dashboard'),
            ], 201);
        }

        return redirect()->route('paciente.dashboard')->with('success', '¡Registro exitoso! Bienvenido(a) a DentalCare.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'correo' => ['required', 'email'],
        ], [
            'correo.required' => 'Por favor ingrese su correo electrónico.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
        ]);

        $correo = $request->input('correo');
        $usuario = Usuario::where('correo', $correo)->first();

        // Regla de seguridad: No revelar si el correo existe o no en el sistema
        if ($usuario && $usuario->activo && !$usuario->trashed()) {
            try {
                $token = Password::broker('usuarios')->createToken($usuario);
                $usuario->notify(new RecuperarContrasenaNotification($token));
            } catch (\Throwable $e) {
                // Registro silencioso en log
            }
        }

        $mensaje = 'Si tu correo electrónico coincide con una cuenta activa en DentalCare, te hemos enviado un enlace para restablecer tu contraseña.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $mensaje,
            ]);
        }

        return back()->with('status', $mensaje);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'correo' => $request->query('correo'),
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $credentials = [
            'email' => $request->input('correo'),
            'password' => $request->input('contrasena'),
            'password_confirmation' => $request->input('contrasena_confirmation'),
            'token' => $request->input('token'),
        ];

        $status = Password::broker('usuarios')->reset(
            $credentials,
            function ($user, string $password) {
                $user->contrasena = Hash::make($password);
                $user->debe_cambiar_contrasena = false;
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $msg = '¡Tu contraseña ha sido restablecida exitosamente! Ya puedes iniciar sesión.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'redirect' => route('login'),
                ]);
            }

            return redirect()->route('login')->with('success', $msg);
        }

        $errorMsg = 'El enlace de recuperación es inválido o ha expirado. Por favor solicita uno nuevo.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $errorMsg,
            ], 422);
        }

        return back()->withErrors(['correo' => $errorMsg]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente.',
                'redirect' => route('login'),
            ]);
        }

        return redirect()->route('login')->with('info', 'Sesión cerrada correctamente.');
    }

    public function rutaPorRol($usuario): string
    {
        if ($usuario->esAdmin() || $usuario->esRecepcionista()) {
            return route('admin.dashboard');
        } elseif ($usuario->esOdontologo()) {
            return route('odontologo.agenda');
        } else {
            return route('paciente.dashboard');
        }
    }

    public function redirectPorRol($usuario)
    {
        return redirect()->to($this->rutaPorRol($usuario));
    }
}
