<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InterfazRolesTest extends TestCase
{
    protected Usuario $admin;
    protected Usuario $recepcionista;
    protected Usuario $odontologo;
    protected Usuario $paciente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Usuario::firstOrCreate(
            ['correo' => 'admin.interfaz@dentalcare.com'],
            [
                'nombre' => 'Admin Interfaz',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000001',
                'rol' => 'admin',
                'role_id' => 1,
                'activo' => true,
            ]
        );
        $this->admin->update(['role_id' => 1, 'activo' => true]);

        $this->recepcionista = Usuario::firstOrCreate(
            ['correo' => 'recep.interfaz@dentalcare.com'],
            [
                'nombre' => 'Recep Interfaz',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000002',
                'rol' => 'recepcionista',
                'role_id' => 3,
                'activo' => true,
            ]
        );
        $this->recepcionista->update(['role_id' => 3, 'activo' => true]);

        $this->odontologo = Usuario::firstOrCreate(
            ['correo' => 'odonto.interfaz@dentalcare.com'],
            [
                'nombre' => 'Odonto Interfaz',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000003',
                'rol' => 'odontologo',
                'role_id' => 2,
                'activo' => true,
            ]
        );
        $this->odontologo->update(['role_id' => 2, 'activo' => true]);

        $this->paciente = Usuario::firstOrCreate(
            ['correo' => 'paciente.interfaz@dentalcare.com'],
            [
                'nombre' => 'Paciente Interfaz',
                'contrasena' => Hash::make('Secret123!'),
                'telefono' => '3000000004',
                'rol' => 'paciente',
                'role_id' => 4,
                'activo' => true,
            ]
        );
        $this->paciente->update(['role_id' => 4, 'activo' => true]);
    }

    /**
     * TAREA 1: Módulo inicial según rol y paso de variables a Blade.
     */
    public function test_modulo_inicial_segun_rol_en_rutas(): void
    {
        // 1. Administrador abre mod-admin
        $this->actingAs($this->admin);
        $resAdmin = $this->get('/admin/dashboard');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('window.INITIAL_ACTIVE_MODULE = "admin"', false);
        $resAdmin->assertSee('rol: "Administrador"', false);

        // 2. Recepcionista abre mod-admin
        $this->actingAs($this->recepcionista);
        $resRecep = $this->get('/admin/dashboard');
        $resRecep->assertStatus(200);
        $resRecep->assertSee('window.INITIAL_ACTIVE_MODULE = "admin"', false);
        $resRecep->assertSee('rol: "Recepcionista"', false);

        // 3. Odontólogo abre mod-odontologo
        $this->actingAs($this->odontologo);
        $resOdonto = $this->get('/odontologo/agenda');
        $resOdonto->assertStatus(200);
        $resOdonto->assertSee('window.INITIAL_ACTIVE_MODULE = "odontologo"', false);
        $resOdonto->assertSee('rol: "Odontólogo"', false);

        // 4. Paciente abre mod-paciente
        $this->actingAs($this->paciente);
        $resPac = $this->get('/paciente/dashboard');
        $resPac->assertStatus(200);
        $resPac->assertSee('window.INITIAL_ACTIVE_MODULE = "paciente"', false);
        $resPac->assertSee('rol: "Paciente"', false);

        // 5. Usuario autenticado al entrar a '/' abre su módulo
        $this->actingAs($this->admin);
        $resHomeAdmin = $this->get('/');
        $resHomeAdmin->assertStatus(200);
        $resHomeAdmin->assertSee('window.INITIAL_ACTIVE_MODULE = "admin"', false);
    }

    /**
     * TAREA 3: Acceso a /admin/dashboard por cada rol.
     */
    public function test_acceso_a_admin_dashboard_por_cada_rol(): void
    {
        // Administrador: Permitido
        $this->actingAs($this->admin);
        $this->get('/admin/dashboard')->assertStatus(200);

        // Recepcionista: Permitido
        $this->actingAs($this->recepcionista);
        $this->get('/admin/dashboard')->assertStatus(200);

        // Odontólogo: Denegado 403
        $this->actingAs($this->odontologo);
        $resOdonto = $this->get('/admin/dashboard');
        $resOdonto->assertStatus(403);
        $resOdonto->assertSee('No tiene permisos para realizar esta acción.');

        // Paciente: Denegado 403
        $this->actingAs($this->paciente);
        $resPac = $this->get('/admin/dashboard');
        $resPac->assertStatus(403);
        $resPac->assertSee('No tiene permisos para realizar esta acción.');

        // Sin sesión: Redirigido a login
        auth()->logout();
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    /**
     * TAREA 2: Recepcionista no debe ver "Usuarios" ni acceder a gestión de usuarios.
     */
    public function test_recepcionista_no_ve_ni_accede_a_usuarios(): void
    {
        $this->actingAs($this->recepcionista);

        // En /admin/dashboard la pestaña Usuarios y el subbotón no deben aparecer
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertDontSee('id="nav-btn-usuarios"', false);
        $response->assertDontSee('id="admin-tab-btn-usuarios"', false);

        // Si intenta consultar la vista o la API de usuarios, recibe 403
        $this->get('/admin/usuarios')->assertStatus(403);
        $this->getJson('/api/admin/usuarios')->assertStatus(403);

        // El Administrador sí debe ver ambos botones
        $this->actingAs($this->admin);
        $resAdmin = $this->get('/admin/dashboard');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('id="nav-btn-usuarios"', false);
        $resAdmin->assertSee('id="admin-tab-btn-usuarios"', false);
    }

    /**
     * TAREA 4: Barra superior con sesión iniciada y cierre de sesión seguro.
     */
    public function test_barra_superior_autenticada_y_cierre_de_sesion(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        // Muestra nombre y rol del usuario autenticado
        $response->assertSee($this->admin->nombre);
        $response->assertSee($this->admin->nombre_rol);
        // Muestra botón de cerrar sesión
        $response->assertSee('Cerrar sesión');
        $response->assertSee('id="logout-form"', false);
        // No muestra botón Login/Registro ni barra de simulación en sesión
        $response->assertDontSee('Simular usuario:');

        // Cierre de sesión POST /logout
        $resLogout = $this->post('/logout');
        $resLogout->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * TAREA 5: Mensajes claros en español para auto-desactivación y último administrador.
     */
    public function test_mensajes_claros_autodesactivacion_y_ultimo_administrador(): void
    {
        $this->actingAs($this->admin);

        // 1. Mensaje de auto-desactivación
        $resAuto = $this->patchJson("/api/admin/usuarios/{$this->admin->id}/toggle-activo");
        $resAuto->assertStatus(403);
        $resAuto->assertJsonFragment([
            'success' => false,
            'message' => 'No puede desactivar su propia cuenta.',
        ]);

        // 2. Mensaje de último administrador activo
        // Dejar a $this->admin como único admin activo inicialmente
        Usuario::where(function ($q) {
            $q->where('role_id', 1)->orWhere('rol', 'admin');
        })->where('id', '!=', $this->admin->id)->update(['activo' => false]);

        // Crear un segundo admin temporal para probar
        $admin2 = Usuario::create([
            'nombre' => 'Segundo Admin Test',
            'correo' => 'admin2.test@dentalcare.com',
            'contrasena' => Hash::make('Clave123!'),
            'telefono' => '3009998877',
            'role_id' => 1,
            'rol' => 'admin',
            'activo' => true,
        ]);

        // Desactivamos el primer admin
        $this->actingAs($admin2);
        $resDesc1 = $this->patchJson("/api/admin/usuarios/{$this->admin->id}/toggle-activo");
        $resDesc1->assertStatus(200);

        // Ahora admin2 es el ÚLTIMO admin activo. Intentar desactivar a admin2 usando admin
        $this->actingAs($this->admin);
        $resUltimo = $this->patchJson("/api/admin/usuarios/{$admin2->id}/toggle-activo");
        $resUltimo->assertStatus(403);
        $resUltimo->assertJsonFragment([
            'success' => false,
            'message' => 'Debe existir al menos un administrador activo.',
        ]);

        // Intentar quitarle el rol de admin al último admin activo
        $resRol = $this->putJson("/api/admin/usuarios/{$admin2->id}", [
            'nombre' => 'Segundo Admin',
            'correo' => 'admin2.test@dentalcare.com',
            'telefono' => '3009998877',
            'rol' => Role::RECEPCIONISTA,
        ]);
        $resRol->assertStatus(422);
        $resRol->assertJsonFragment([
            'success' => false,
            'message' => 'Debe existir al menos un administrador activo.',
        ]);

        // Limpiar admin2 y restaurar
        $this->admin->update(['activo' => true]);
        $admin2->delete();
    }
}
