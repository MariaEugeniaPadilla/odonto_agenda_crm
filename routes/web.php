<?php

use Illuminate\Support\Facades\Route;
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

// Rutas Públicas (El prototipo completo e interactivo se sirve en / y en /prototipo)
Route::get('/', function () {
    return view('prototype');
})->name('home');

Route::get('/prototipo', function () {
    return view('prototype');
})->name('prototipo');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Portal del Paciente (Protegido por Rol)
Route::prefix('paciente')->name('paciente.')->group(function () {
    Route::get('/dashboard', [PacienteController::class, 'dashboard'])->name('dashboard');
    Route::get('/agendar', [PacienteController::class, 'agendar'])->name('agendar');
    Route::post('/agendar', [PacienteController::class, 'storeCita'])->name('agendar.store');
    Route::get('/citas', [PacienteController::class, 'citas'])->name('citas');
    Route::post('/citas/{id}/cancelar', [PacienteController::class, 'cancelarCita'])->name('citas.cancelar');
    Route::get('/historial', [PacienteController::class, 'historial'])->name('historial');
});

// Portal del Odontólogo
Route::prefix('odontologo')->name('odontologo.')->group(function () {
    Route::get('/agenda', [OdontologoController::class, 'agenda'])->name('agenda');
    Route::post('/citas/{id}/iniciar', [OdontologoController::class, 'iniciarAtencion'])->name('citas.iniciar');
    Route::post('/citas/{id}/finalizar', [OdontologoController::class, 'finalizarAtencion'])->name('citas.finalizar');
    Route::post('/citas/{id}/no-asistio', [OdontologoController::class, 'marcarNoAsistio'])->name('citas.no_asistio');
});

// Panel Administrativo y de Recepción
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/agenda', [AdminController::class, 'agendaGeneral'])->name('agenda');
    Route::post('/citas/{id}/estado', [AdminController::class, 'cambiarEstadoCita'])->name('citas.estado');
    Route::get('/servicios', [AdminController::class, 'servicios'])->name('servicios');
    Route::get('/inventario', [AdminController::class, 'inventario'])->name('inventario');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
});
