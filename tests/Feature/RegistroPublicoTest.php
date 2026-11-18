<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Usuario;
use App\Notifications\BienvenidaPacienteNotification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistroPublicoTest extends TestCase
{
    public function test_registro_publico_valido_asigna_siempre_rol_paciente_y_notifica(): void
    {
        Notification::fake();

        $correo = 'paciente.nuevo.' . time() . '@dentalcare.com';

        $response = $this->post('/registro', [
            'nombre' => 'Paciente Auto Registrado',
            'correo' => $correo,
            'telefono' => '3201122334',
            'documento_identidad' => '1144998877',
            'contrasena' => 'ClaveSegura123!',
            'contrasena_confirmation' => 'ClaveSegura123!',
        ]);

        $response->assertRedirect('/paciente/dashboard');

        $usuario = Usuario::where('correo', $correo)->first();
        $this->assertNotNull($usuario);
        $this->assertEquals(Role::PACIENTE, $usuario->nombre_rol);
        $this->assertEquals(4, $usuario->role_id);
        $this->assertTrue((bool) $usuario->activo);

        Notification::assertSentTo($usuario, BienvenidaPacienteNotification::class);
    }

    public function test_registro_publico_invalido_falla_por_contrasena_corta_o_sin_confirmar(): void
    {
        $response = $this->post('/registro', [
            'nombre' => 'Paciente Invalido',
            'correo' => 'invalido@dentalcare.com',
            'telefono' => '3000000000',
            'contrasena' => '123',
            'contrasena_confirmation' => '456',
        ]);

        $response->assertSessionHasErrors(['contrasena']);
    }

    public function test_registro_publico_respeta_variable_cuando_esta_desactivado(): void
    {
        Config::set('app.registro_publico', false);

        $response = $this->get('/registro');
        $response->assertRedirect('/login');

        $responsePost = $this->post('/registro', [
            'nombre' => 'Intento Bloqueado',
            'correo' => 'bloqueado@dentalcare.com',
            'telefono' => '3000000000',
            'contrasena' => 'ClaveSegura123!',
            'contrasena_confirmation' => 'ClaveSegura123!',
        ]);

        $responsePost->assertStatus(403);
    }
}
