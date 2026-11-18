<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('test.login@dentalcare.com|127.0.0.1');
    }

    public function test_login_correcto_con_contrasena_hasheada(): void
    {
        $password = 'Secret123!';
        $admin = Usuario::firstOrCreate(
            ['correo' => 'admin.test@dentalcare.com'],
            [
                'nombre' => 'Admin Test',
                'contrasena' => Hash::make($password),
                'telefono' => '3001234567',
                'rol' => 'admin',
                'role_id' => 1,
                'activo' => true,
            ]
        );
        $admin->update([
            'contrasena' => Hash::make($password),
            'activo' => true,
            'role_id' => 1,
        ]);

        $response = $this->post('/login', [
            'correo' => 'admin.test@dentalcare.com',
            'contrasena' => $password,
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_incorrecto_falla_y_muestra_error(): void
    {
        $response = $this->post('/login', [
            'correo' => 'admin.test@dentalcare.com',
            'contrasena' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('correo');
        $this->assertGuest();
    }

    public function test_usuario_inactivo_no_puede_iniciar_sesion(): void
    {
        $password = 'Secret123!';
        $inactivo = Usuario::firstOrCreate(
            ['correo' => 'inactivo.test@dentalcare.com'],
            [
                'nombre' => 'Usuario Inactivo',
                'contrasena' => Hash::make($password),
                'telefono' => '3001234567',
                'rol' => 'paciente',
                'role_id' => 4,
                'activo' => false,
            ]
        );
        $inactivo->update([
            'contrasena' => Hash::make($password),
            'activo' => false,
        ]);

        $response = $this->post('/login', [
            'correo' => 'inactivo.test@dentalcare.com',
            'contrasena' => $password,
        ]);

        $response->assertSessionHasErrors('correo');
        $this->assertGuest();
    }

    public function test_logout_cierra_sesion_e_invalida_sesion(): void
    {
        $usuario = Usuario::where('activo', true)->first();
        $this->actingAs($usuario);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_login_rate_limiting_bloquea_tras_5_intentos_fallidos(): void
    {
        $correo = 'bruteforce@dentalcare.com';

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'correo' => $correo,
                'contrasena' => 'clave_erronea',
            ]);
        }

        $response = $this->postJson('/api/login', [
            'correo' => $correo,
            'contrasena' => 'clave_erronea',
        ]);

        $response->assertStatus(429);
    }
}
