<?php

namespace Tests\Feature;

use App\Models\Odontologo;
use App\Models\Role;
use App\Models\Usuario;
use App\Notifications\CuentaCreadaAdminNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class GestionUsuariosTest extends TestCase
{
    protected Usuario $admin;
    protected Usuario $paciente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Usuario::where('role_id', 1)->first() ?? Usuario::firstOrCreate(
            ['correo' => 'admin.primary@dentalcare.com'],
            [
                'nombre' => 'Admin Principal',
                'contrasena' => Hash::make('Admin123!'),
                'telefono' => '3101112233',
                'rol' => 'admin',
                'role_id' => 1,
                'activo' => true,
            ]
        );

        $this->paciente = Usuario::where('role_id', 4)->first() ?? Usuario::firstOrCreate(
            ['correo' => 'paciente.base@dentalcare.com'],
            [
                'nombre' => 'Paciente Base',
                'contrasena' => Hash::make('Paciente123!'),
                'telefono' => '3102223344',
                'rol' => 'paciente',
                'role_id' => 4,
                'activo' => true,
            ]
        );
    }

    public function test_administrador_puede_listar_usuarios_con_filtros_y_busqueda(): void
    {
        $this->actingAs($this->admin);

        $response = $this->getJson('/api/admin/usuarios?buscar=Carlos&rol=Paciente');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'pagination',
            'roles_disponibles',
        ]);
    }

    public function test_rol_no_administrador_recibe_403_en_modulo_de_usuarios(): void
    {
        $this->actingAs($this->paciente);

        $responseGet = $this->get('/admin/usuarios');
        $responseGet->assertStatus(403);

        $responseApi = $this->getJson('/api/admin/usuarios');
        $responseApi->assertStatus(403);

        $responsePost = $this->postJson('/api/admin/usuarios', [
            'nombre' => 'Intento Hack',
            'correo' => 'hack@dentalcare.com',
            'telefono' => '3000000000',
            'rol' => Role::PACIENTE,
            'metodo_contrasena' => 'enlace',
        ]);
        $responsePost->assertStatus(403);
    }

    public function test_administrador_puede_crear_usuario_con_enlace_por_correo_y_notificacion(): void
    {
        Notification::fake();
        $this->actingAs($this->admin);

        $correo = 'nuevo.recep.' . time() . '@dentalcare.com';

        $response = $this->postJson('/api/admin/usuarios', [
            'nombre' => 'Recepcionista Turno Tarde',
            'correo' => $correo,
            'telefono' => '3159988776',
            'documento_identidad' => '1144556677',
            'rol' => Role::RECEPCIONISTA,
            'activo' => 1,
            'metodo_contrasena' => 'enlace',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        $creado = Usuario::where('correo', $correo)->first();
        $this->assertNotNull($creado);
        $this->assertEquals(Role::RECEPCIONISTA, $creado->nombre_rol);
        $this->assertEquals($this->admin->id, $creado->created_by);

        Notification::assertSentTo($creado, CuentaCreadaAdminNotification::class);
    }

    public function test_administrador_puede_crear_odontologo_y_crea_registro_en_tabla_odontologos(): void
    {
        $this->actingAs($this->admin);

        $correo = 'doctor.especialista.' . time() . '@dentalcare.com';

        $response = $this->postJson('/api/admin/usuarios', [
            'nombre' => 'Dr. Fernando Arango',
            'correo' => $correo,
            'telefono' => '3185544332',
            'documento_identidad' => '1144332211',
            'rol' => Role::ODONTOLOGO,
            'activo' => 1,
            'metodo_contrasena' => 'manual',
            'contrasena' => 'ClaveTemporal123!',
            'especialidad' => 'Periodoncia e Implantología',
            'registro_medico' => 'RM-PER-9988',
        ]);

        $response->assertStatus(201);

        $usuario = Usuario::where('correo', $correo)->first();
        $this->assertNotNull($usuario);
        $this->assertTrue((bool) $usuario->debe_cambiar_contrasena);

        $odontologo = Odontologo::where('usuario_id', $usuario->id)->first();
        $this->assertNotNull($odontologo);
        $this->assertEquals('Periodoncia e Implantología', $odontologo->especialidad);
        $this->assertEquals('RM-PER-9988', $odontologo->registro_medico);
    }

    public function test_administrador_puede_editar_usuario_y_cambiar_rol(): void
    {
        $this->actingAs($this->admin);

        $usuario = Usuario::create([
            'nombre' => 'Usuario Para Editar',
            'correo' => 'editar.' . time() . '@dentalcare.com',
            'contrasena' => Hash::make('Clave123!'),
            'telefono' => '3000000000',
            'role_id' => 4,
            'rol' => 'paciente',
            'activo' => true,
        ]);

        $response = $this->putJson("/api/admin/usuarios/{$usuario->id}", [
            'nombre' => 'Usuario Editado Exitosamente',
            'correo' => $usuario->correo,
            'telefono' => '3119998888',
            'documento_identidad' => '1144000111',
            'rol' => Role::RECEPCIONISTA,
            'activo' => 1,
        ]);

        $response->assertStatus(200);

        $usuario->refresh();
        $this->assertEquals('Usuario Editado Exitosamente', $usuario->nombre);
        $this->assertEquals(Role::RECEPCIONISTA, $usuario->nombre_rol);
        $this->assertEquals($this->admin->id, $usuario->updated_by);
    }

    public function test_administrador_puede_activar_y_desactivar_usuario(): void
    {
        $this->actingAs($this->admin);

        $usuario = Usuario::create([
            'nombre' => 'Usuario Toggle Activo',
            'correo' => 'toggle.' . time() . '@dentalcare.com',
            'contrasena' => Hash::make('Clave123!'),
            'telefono' => '3000000000',
            'role_id' => 4,
            'rol' => 'paciente',
            'activo' => true,
        ]);

        // Desactivar
        $resDesactivar = $this->patchJson("/api/admin/usuarios/{$usuario->id}/toggle-activo");
        $resDesactivar->assertStatus(200);
        $this->assertFalse((bool) $usuario->fresh()->activo);

        // Activar
        $resActivar = $this->patchJson("/api/admin/usuarios/{$usuario->id}/toggle-activo");
        $resActivar->assertStatus(200);
        $this->assertTrue((bool) $usuario->fresh()->activo);
    }

    public function test_administrador_no_puede_desactivarse_a_si_mismo(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patchJson("/api/admin/usuarios/{$this->admin->id}/toggle-activo");
        $response->assertStatus(403);
    }

    public function test_administrador_no_puede_quitarse_su_propio_rol(): void
    {
        $this->actingAs($this->admin);

        $response = $this->putJson("/api/admin/usuarios/{$this->admin->id}", [
            'nombre' => $this->admin->nombre,
            'correo' => $this->admin->correo,
            'telefono' => $this->admin->telefono,
            'rol' => Role::PACIENTE, // Intento de quitarse el rol de Administrador
        ]);

        $response->assertStatus(422);
    }

    public function test_no_se_puede_desactivar_al_ultimo_administrador(): void
    {
        // Dejar solo 1 administrador activo
        Usuario::where('role_id', 1)->where('id', '!=', $this->admin->id)->update(['activo' => false]);

        $otroAdmin = Usuario::create([
            'nombre' => 'Segundo Admin Auxiliar',
            'correo' => 'auxiliar.' . time() . '@dentalcare.com',
            'contrasena' => Hash::make('Clave123!'),
            'telefono' => '3000000000',
            'role_id' => 1,
            'rol' => 'admin',
            'activo' => true,
        ]);

        $this->actingAs($otroAdmin);

        // Desactivar a this->admin deja a otroAdmin como único activo
        $res1 = $this->patchJson("/api/admin/usuarios/{$this->admin->id}/toggle-activo");
        $res1->assertStatus(200);

        // Ahora intentar desactivar a otroAdmin (el último admin activo)
        $this->actingAs($this->admin); // aunque estuviera inactivo en BD, simulamos la llamada
        $res2 = $this->patchJson("/api/admin/usuarios/{$otroAdmin->id}/toggle-activo");
        $res2->assertStatus(403);

        // Restaurar estado
        Usuario::where('id', $this->admin->id)->update(['activo' => true]);
        $otroAdmin->delete();
    }
}
