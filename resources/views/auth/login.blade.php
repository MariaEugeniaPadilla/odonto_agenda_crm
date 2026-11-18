@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="text-center space-y-2 mb-6">
    <img src="{{ asset('images/dentalcare-clean.png') }}" alt="Dental Care - Sonrisas saludables" class="h-12 mx-auto object-contain mb-2">
    <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[11px] text-slate-600 font-medium">
        <img src="{{ asset('images/odontoagenda-icon.png') }}" class="w-3.5 h-3.5 object-contain" alt="">
        <span>OdontoAgenda CRM</span>
    </div>
    <h2 class="font-heading font-bold text-2xl text-secondary-900 mt-1">Iniciar Sesión</h2>
    <p class="text-xs text-slate-500">Accede con tus credenciales de paciente o personal de la clínica</p>
</div>

<form method="POST" action="{{ route('login.post') }}" class="space-y-4">
    @csrf

    <div>
        <label for="correo" class="block text-xs font-semibold text-slate-700 mb-1.5">Correo Electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required autofocus
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="ejemplo@correo.com">
    </div>

    <div>
        <label for="contrasena" class="block text-xs font-semibold text-slate-700 mb-1.5">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena" required
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
            placeholder="••••••••">
    </div>

    <div class="flex items-center justify-between text-xs pt-1">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-slate-300">
            <span class="text-slate-600 font-medium">Recordarme</span>
        </label>
        <a href="{{ route('password.request') }}" class="text-primary-700 hover:text-primary-800 font-semibold hover:underline">
            ¿Olvidaste tu contraseña?
        </a>
    </div>

    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-sm shadow-sm transition">
        Iniciar Sesión
    </button>
</form>

@if(config('app.registro_publico', true))
    <div class="pt-6 mt-6 border-t border-slate-100 text-center text-xs text-slate-500">
        ¿Eres un paciente nuevo?
        <a href="{{ route('register') }}" class="text-primary-700 hover:text-primary-800 font-bold hover:underline ml-1">
            Crear cuenta de Paciente
        </a>
    </div>
@endif
@endsection
