@extends('layouts.auth')

@section('title', 'Definir Nueva Contraseña')

@section('content')
<div class="text-center space-y-2 mb-6">
    <img src="{{ asset('images/dentalcare-clean.png') }}" alt="Dental Care - Sonrisas saludables" class="h-11 mx-auto object-contain mb-3">
    <h2 class="font-heading font-bold text-2xl text-secondary-900">Definir Nueva Contraseña</h2>
    <p class="text-xs text-slate-500 max-w-xs mx-auto">Ingresa tu correo y la nueva contraseña para acceder a DentalCare.</p>
</div>

<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="correo" class="block text-xs font-semibold text-slate-700 mb-1.5">Correo Electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo', $correo) }}" required autofocus
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="tu.correo@ejemplo.com">
    </div>

    <div>
        <label for="contrasena" class="block text-xs font-semibold text-slate-700 mb-1.5">Nueva Contraseña (Mínimo 8 caracteres)</label>
        <input type="password" id="contrasena" name="contrasena" required minlength="8"
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="••••••••">
    </div>

    <div>
        <label for="contrasena_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Confirmar Nueva Contraseña</label>
        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" required minlength="8"
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="••••••••">
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-sm shadow-sm transition">
        Guardar Contraseña e Ingresar
    </button>
</form>

<div class="pt-6 mt-6 border-t border-slate-100 text-center text-xs text-slate-500">
    <a href="{{ route('login') }}" class="text-primary-700 hover:text-primary-800 font-bold hover:underline inline-flex items-center gap-1">
        &larr; <span>Regresar al inicio de sesión</span>
    </a>
</div>
@endsection
