<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Maneja la verificación de sesión activa, estado del usuario y roles permitidos.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }
            return redirect()->guest(route('login'))->with('warning', 'Debe iniciar sesión para acceder a este módulo.');
        }

        $usuario = Auth::user();

        // Regla: Si el usuario fue desactivado, su sesión activa se invalida de inmediato
        if (!$usuario->activo || (method_exists($usuario, 'trashed') && $usuario->trashed())) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Su cuenta se encuentra inactiva. Comuníquese con la administración.'], 403);
            }

            return redirect()->route('login')->withErrors([
                'correo' => 'Su cuenta se encuentra inactiva. Por favor comuníquese con la administración de DentalCare.',
            ]);
        }

        // Verificación de roles permitidos
        if (!empty($roles)) {
            $permitido = false;
            foreach ($roles as $rolEsperado) {
                if ($usuario->hasRole($rolEsperado)) {
                    $permitido = true;
                    break;
                }
            }

            if (!$permitido) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'No tiene permisos para realizar esta acción.'], 403);
                }
                abort(403, 'No tiene permisos para realizar esta acción.');
            }
        }

        return $next($request);
    }
}
