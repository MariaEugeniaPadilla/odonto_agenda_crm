@extends('layouts.auth')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="text-center space-y-2 mb-6">
    <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mx-auto mb-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
    </div>
    <h2 class="font-heading font-bold text-2xl text-secondary-900">¿Olvidaste tu contraseña?</h2>
    <p class="text-xs text-slate-500 max-w-xs mx-auto">Ingresa tu correo registrado y te enviaremos un enlace seguro para restablecer o definir tu contraseña.</p>
</div>

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
    @csrf

    <div>
        <label for="correo" class="block text-xs font-semibold text-slate-700 mb-1.5">Correo Electrónico Registrado</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required autofocus
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="tu.correo@ejemplo.com">
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-sm shadow-sm transition">
        Enviar Enlace de Recuperación
    </button>
</form>

<div class="pt-6 mt-6 border-t border-slate-100 text-center text-xs text-slate-500">
    <a href="{{ route('login') }}" class="text-primary-700 hover:text-primary-800 font-bold hover:underline inline-flex items-center gap-1">
        &larr; <span>Regresar al inicio de sesión</span>
    </a>
</div>
@endsection
