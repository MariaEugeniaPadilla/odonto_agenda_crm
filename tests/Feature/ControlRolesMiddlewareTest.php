<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ControlRolesMiddlewareTest extends TestCase
{
    protected Usuario $admin;
    protected Usuario $odontologo;
    protected Usuario $recepcionista;
    protected Usuario $paciente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Usuario::firstOrCreate(
            ['correo' => 'test.admin@dentalcare.com'],
            [
                'nombre' => 'Test Admin',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000001',
                'rol' => 'admin',
                'role_id' => 1,
                'activo' => true,
            ]
        );
        $this->admin->update(['role_id' => 1, 'activo' => true]);

        $this->odontologo = Usuario::firstOrCreate(
            ['correo' => 'test.odontologo@dentalcare.com'],
            [
                'nombre' => 'Test Odontologo',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000002',
                'rol' => 'odontologo',
                'role_id' => 2,
                'activo' => true,
            ]
        );
        $this->odontologo->update(['role_id' => 2, 'activo' => true]);

        $this->recepcionista = Usuario::firstOrCreate(
            ['correo' => 'test.recepcionista@dentalcare.com'],
            [
                'nombre' => 'Test Recepcionista',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000003',
                'rol' => 'recepcionista',
                'role_id' => 3,
                'activo' => true,
            ]
        );
        $this->recepcionista->update(['role_id' => 3, 'activo' => true]);

        $this->paciente = Usuario::firstOrCreate(
            ['correo' => 'test.paciente@dentalcare.com'],
            [
                'nombre' => 'Test Paciente',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000004',
                'rol' => 'paciente',
                'role_id' => 4,
                'activo' => true,
            ]
        );
        $this->paciente->update(['role_id' => 4, 'activo' => true]);
    }

    public function test_paciente_accede_a_sus_rutas_y_recibe_403_en_admin_y_odontologo(): void
    {
        $this->actingAs($this->paciente);

        $this->get('/paciente/dashboard')->assertStatus(200);
        $this->get('/paciente/citas')->assertStatus(200);

        // Denegado en áreas de odontólogo y admin
        $this->get('/odontologo/agenda')->assertStatus(403);
        $this->get('/admin/dashboard')->assertStatus(403);
        $this->get('/admin/usuarios')->assertStatus(403);
    }

    public function test_odontologo_accede_a_su_agenda_y_recibe_403_en_paciente_y_admin(): void
    {
        $this->actingAs($this->odontologo);

        $this->get('/odontologo/agenda')->assertStatus(200);

        // Denegado en áreas de paciente y admin
        $this->get('/paciente/dashboard')->assertStatus(403);
        $this->get('/admin/dashboard')->assertStatus(403);
        $this->get('/admin/usuarios')->assertStatus(403);
    }

    public function test_recepcionista_accede_a_operaciones_y_recibe_403_en_usuarios_y_odontologo(): void
    {
        $this->actingAs($this->recepcionista);

        // Permitido en panel operativo administrativo
        $this->get('/admin/dashboard')->assertStatus(200);
        $this->get('/admin/agenda')->assertStatus(200);

        // Denegado en gestión exclusiva de usuarios y en odontólogo
        $this->get('/admin/usuarios')->assertStatus(403);
        $this->get('/odontologo/agenda')->assertStatus(403);
        $this->get('/paciente/dashboard')->assertStatus(403);
    }

    public function test_administrador_tiene_acceso_total_a_operaciones_y_usuarios(): void
    {
        $this->actingAs($this->admin);

        $this->get('/admin/dashboard')->assertStatus(200);
        $this->get('/admin/usuarios')->assertStatus(200);
        $this->getJson('/api/admin/usuarios')->assertStatus(200);
    }

    public function test_usuario_desactivado_mientras_tiene_sesion_es_expulsado_inmediatamente(): void
    {
        $usuario = Usuario::create([
            'nombre' => 'Sesion Activa Pero Desactivada',
            'correo' => 'expulsado.' . time() . '@dentalcare.com',
            'contrasena' => Hash::make('Clave123!'),
            'telefono' => '3000000000',
            'role_id' => 4,
            'rol' => 'paciente',
            'activo' => true,
        ]);

        $this->actingAs($usuario);
        $this->get('/paciente/dashboard')->assertStatus(200);

        // Desactivamos el usuario en base de datos
        $usuario->update(['activo' => false]);

        // La siguiente petición debe invalidar la sesión y redirigir a login con error
        $response = $this->get('/paciente/dashboard');
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('correo');
        $this->assertGuest();
    }
}
