<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Web Routes - OdontoAgenda CRM (DentalCare)
|--------------------------------------------------------------------------
*/

if (!function_exists('renderOdontoView')) {
    function renderOdontoView($activeModule = 'landing', $userId = null) {
        $actualUserId = $userId ?? (Auth::check() ? Auth::id() : null);
        $initialState = ApiController::getInitialState($actualUserId);
        return view('prototype', compact('initialState', 'activeModule'));
    }
}

// Rutas Públicas e Interfaces Principales
Route::get('/', function () {
    return renderOdontoView('landing');
})->name('home');

Route::get('/prototipo', function () {
    return renderOdontoView('landing');
})->name('prototipo');

// -------------------------------------------------------------------------
// AUTENTICACIÓN, REGISTRO PÚBLICO Y RECUPERACIÓN DE CONTRASEÑA
// -------------------------------------------------------------------------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registro público (restringido exclusivamente al rol Paciente)
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');

// Recuperación y definición de contraseña (Password Broker)
Route::get('/olvide-contrasena', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/olvide-contrasena', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/recuperar-contrasena/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/recuperar-contrasena', [AuthController::class, 'resetPassword'])->name('password.update');

// -------------------------------------------------------------------------
// RUTAS PROTEGIDAS POR ROL CON MIDDLEWARE 'role'
// -------------------------------------------------------------------------

// 1. Rol: Paciente
Route::middleware(['role:Paciente'])->prefix('paciente')->name('paciente.')->group(function () {
    Route::get('/dashboard', function () {
        return renderOdontoView('paciente');
    })->name('dashboard');

    Route::get('/agendar', function () {
        return renderOdontoView('paciente');
    })->name('agendar');

    Route::get('/citas', function () {
        return renderOdontoView('paciente');
    })->name('citas');

    Route::get('/historial', function () {
        return renderOdontoView('paciente');
    })->name('historial');

    Route::post('/agendar', [ApiController::class, 'storeCita'])->name('agendar.store');
    Route::post('/citas/{id}/cancelar', [ApiController::class, 'cancelarCita'])->name('citas.cancelar');
});

// 2. Rol: Odontólogo
Route::middleware(['role:Odontólogo'])->prefix('odontologo')->name('odontologo.')->group(function () {
    Route::get('/agenda', function () {
        return renderOdontoView('odontologo');
    })->name('agenda');

    Route::post('/citas/{id}/iniciar', [ApiController::class, 'iniciarAtencion'])->name('citas.iniciar');
    Route::post('/citas/{id}/finalizar', [ApiController::class, 'finalizarAtencion'])->name('citas.finalizar');
    Route::post('/citas/{id}/no-asistio', [ApiController::class, 'marcarNoAsistio'])->name('citas.no_asistio');
});

// 3. Roles: Administrador y Recepcionista (Supervisión, Inventario, Agenda y Citas)
Route::middleware(['role:Administrador,Recepcionista'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return renderOdontoView('admin');
    })->name('dashboard');

    Route::get('/agenda', function () {
        return renderOdontoView('admin');
    })->name('agenda');

    Route::get('/servicios', function () {
        return renderOdontoView('admin');
    })->name('servicios');

    Route::get('/inventario', function () {
        return renderOdontoView('admin');
    })->name('inventario');

    Route::get('/reportes', function () {
        return renderOdontoView('admin');
    })->name('reportes');

    Route::post('/citas/{id}/estado', [ApiController::class, 'cambiarEstado'])->name('citas.estado');
});

// 4. Exclusivo: Administrador (Gestión de Usuarios del Sistema)
Route::middleware(['role:Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/usuarios', function () {
        return renderOdontoView('admin-usuarios');
    })->name('usuarios.index');
});

// =========================================================================
// API & Acciones Conectadas a la Base de Datos (MySQL - odontoagenda_crm)
// =========================================================================
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/estado', [ApiController::class, 'estado'])->name('estado');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/switch-user/{id}', [ApiController::class, 'switchUser'])->name('switch_user');
    
    // Citas
    Route::post('/citas', [ApiController::class, 'storeCita'])->name('citas.store');
    Route::post('/citas/{id}/cancelar', [ApiController::class, 'cancelarCita'])->name('citas.cancelar');
    Route::post('/citas/{id}/iniciar', [ApiController::class, 'iniciarAtencion'])->name('citas.iniciar');
    Route::post('/citas/{id}/finalizar', [ApiController::class, 'finalizarAtencion'])->name('citas.finalizar');
    Route::post('/citas/{id}/no-asistio', [ApiController::class, 'marcarNoAsistio'])->name('citas.no_asistio');
    Route::post('/citas/{id}/estado', [ApiController::class, 'cambiarEstado'])->name('citas.estado');

    // Insumos e inventario
    Route::post('/insumos/{id}/reabastecer', [ApiController::class, 'reabastecerInsumo'])->name('insumos.reabastecer');

    // Notificaciones
    Route::post('/notificaciones', [ApiController::class, 'enviarNotificacion'])->name('notificaciones.store');

    // Historial clínico de pacientes (para consulta del odontólogo)
    Route::get('/pacientes/{id}/historial', [ApiController::class, 'historialPaciente'])->name('pacientes.historial');

    // Módulo de Gestión de Usuarios (Exclusivo Administrador con UserPolicy)
    Route::prefix('admin/usuarios')->middleware(['role:Administrador'])->name('usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::post('/', [UsuarioController::class, 'store'])->name('store');
        Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
        Route::patch('/{usuario}/toggle-activo', [UsuarioController::class, 'toggleActivo'])->name('toggle_activo');
        Route::post('/{usuario}/reenviar-acceso', [UsuarioController::class, 'resendAccess'])->name('resend_access');
        Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy');
    });
});
