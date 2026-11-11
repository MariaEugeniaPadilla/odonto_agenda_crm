<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        ]);

        $usuario = Usuario::where('correo', $credenciales['correo'])->first();

        if ($usuario && Hash::check($credenciales['contrasena'], $usuario->contrasena)) {
            if (!$usuario->activo) {
                return back()->withErrors(['correo' => 'Su cuenta se encuentra inactiva.']);
            }

            Auth::login($usuario, $request->boolean('remember'));
            $request->session()->regenerate();

            return $this->redirectPorRol($usuario);
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => 'required|email|unique:usuarios,correo|max:150',
            'contrasena' => 'required|string|min:8|confirmed',
            'telefono' => 'required|string|max:20',
            'documento_identidad' => 'nullable|string|max:20',
        ]);

        $usuario = Usuario::create([
            'nombre' => $data['nombre'],
            'correo' => $data['correo'],
            'contrasena' => Hash::make($data['contrasena']),
            'telefono' => $data['telefono'],
            'documento_identidad' => $data['documento_identidad'] ?? null,
            'rol' => 'paciente',
            'faltas_acumuladas' => 0,
            'activo' => true,
        ]);

        Auth::login($usuario);

        return redirect()->route('paciente.dashboard')->with('success', '¡Registro exitoso! Bienvenido a OdontoAgenda CRM.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Sesión cerrada correctamente.');
    }

    protected function redirectPorRol($usuario)
    {
        switch ($usuario->rol) {
            case 'admin':
            case 'recepcionista':
                return redirect()->route('admin.dashboard');
            case 'odontologo':
                return redirect()->route('odontologo.agenda');
            case 'paciente':
            default:
                return redirect()->route('paciente.dashboard');
        }
    }
}
