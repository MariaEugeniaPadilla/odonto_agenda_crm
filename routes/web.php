<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\OdontologoController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes - OdontoAgenda CRM
|--------------------------------------------------------------------------
*/

if (!function_exists('renderOdontoView')) {
    function renderOdontoView($activeModule = 'landing', $userId = null) {
        $initialState = ApiController::getInitialState($userId);
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

// Rutas directas para cada interfaz/módulo
Route::get('/login', function () {
    return renderOdontoView('login');
})->name('login');

Route::get('/paciente/dashboard', function () {
    return renderOdontoView('paciente', 6);
})->name('paciente.dashboard');

Route::get('/paciente/agendar', function () {
    return renderOdontoView('paciente', 6);
})->name('paciente.agendar');

Route::get('/paciente/citas', function () {
    return renderOdontoView('paciente', 6);
})->name('paciente.citas');

Route::get('/paciente/historial', function () {
    return renderOdontoView('paciente', 6);
})->name('paciente.historial');

Route::get('/odontologo/agenda', function () {
    return renderOdontoView('odontologo', 3);
})->name('odontologo.agenda');

Route::get('/admin/dashboard', function () {
    return renderOdontoView('admin', 1);
})->name('admin.dashboard');

Route::get('/admin/agenda', function () {
    return renderOdontoView('admin', 1);
})->name('admin.agenda');

Route::get('/admin/servicios', function () {
    return renderOdontoView('admin', 1);
})->name('admin.servicios');

Route::get('/admin/inventario', function () {
    return renderOdontoView('admin', 1);
})->name('admin.inventario');

Route::get('/admin/reportes', function () {
    return renderOdontoView('admin', 1);
})->name('admin.reportes');

// =========================================================================
// API & Acciones Conectadas a la Base de Datos (MySQL - odontoagenda_crm)
// =========================================================================
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/estado', [ApiController::class, 'estado'])->name('estado');
    Route::post('/login', [ApiController::class, 'login'])->name('login');
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
});

// Soporte de compatibilidad con formularios POST tradicionales
Route::post('/login', [ApiController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/paciente/agendar', [ApiController::class, 'storeCita'])->name('paciente.agendar.store');
Route::post('/paciente/citas/{id}/cancelar', [ApiController::class, 'cancelarCita'])->name('paciente.citas.cancelar');
Route::post('/odontologo/citas/{id}/iniciar', [ApiController::class, 'iniciarAtencion'])->name('odontologo.citas.iniciar');
Route::post('/odontologo/citas/{id}/finalizar', [ApiController::class, 'finalizarAtencion'])->name('odontologo.citas.finalizar');
Route::post('/odontologo/citas/{id}/no-asistio', [ApiController::class, 'marcarNoAsistio'])->name('odontologo.citas.no_asistio');
Route::post('/admin/citas/{id}/estado', [ApiController::class, 'cambiarEstado'])->name('admin.citas.estado');
