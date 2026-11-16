@extends('layouts.auth')

@section('title', 'Registro de Paciente')

@section('content')
<div class="text-center space-y-2 mb-6">
    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-200">
        <span>Portal del Paciente</span>
    </div>
    <h2 class="font-heading font-bold text-2xl text-secondary-900">Crear Cuenta de Paciente</h2>
    <p class="text-xs text-slate-500">Regístrate para agendar consultas en línea y consultar tu historial</p>
</div>

<form method="POST" action="{{ route('register.post') }}" class="space-y-4">
    @csrf

    <div>
        <label for="nombre" class="block text-xs font-semibold text-slate-700 mb-1.5">Nombre Completo *</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required autofocus
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="Ej. Juan Pérez">
    </div>

    <div>
        <label for="correo" class="block text-xs font-semibold text-slate-700 mb-1.5">Correo Electrónico *</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="tu.correo@ejemplo.com">
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label for="telefono" class="block text-xs font-semibold text-slate-700 mb-1.5">Teléfono / Celular *</label>
            <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" required
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                placeholder="300 123 4567">
        </div>
        <div>
            <label for="documento_identidad" class="block text-xs font-semibold text-slate-700 mb-1.5">Documento de Identidad</label>
            <input type="text" id="documento_identidad" name="documento_identidad" value="{{ old('documento_identidad') }}"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                placeholder="CC 114400...">
        </div>
    </div>

    <div>
        <label for="contrasena" class="block text-xs font-semibold text-slate-700 mb-1.5">Contraseña (Mínimo 8 caracteres) *</label>
        <input type="password" id="contrasena" name="contrasena" required minlength="8"
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="••••••••">
    </div>

    <div>
        <label for="contrasena_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Confirmar Contraseña *</label>
        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" required minlength="8"
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="••••••••">
    </div>

    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-500 space-y-1">
        <div class="font-semibold text-slate-700 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Asignación de rol: Paciente</span>
        </div>
        <p>Tu cuenta se creará con el rol exclusivo de Paciente para agendar citas en DentalCare.</p>
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-sm shadow-sm transition">
        Crear mi Cuenta de Paciente
    </button>
</form>

<div class="pt-6 mt-6 border-t border-slate-100 text-center text-xs text-slate-500">
    ¿Ya tienes una cuenta registrada?
    <a href="{{ route('login') }}" class="text-primary-700 hover:text-primary-800 font-bold hover:underline ml-1">
        Iniciar Sesión
    </a>
</div>
@endsection
