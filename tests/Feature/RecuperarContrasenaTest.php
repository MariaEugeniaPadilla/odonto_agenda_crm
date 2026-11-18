<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Notifications\RecuperarContrasenaNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class RecuperarContrasenaTest extends TestCase
{
    public function test_solicitud_recuperacion_no_revela_si_correo_existe_y_envia_notificacion(): void
    {
        Notification::fake();

        $usuario = Usuario::where('activo', true)->first();

        // Correo existente
        $response = $this->post('/olvide-contrasena', [
            'correo' => $usuario->correo,
        ]);
        $response->assertSessionHas('status');

        Notification::assertSentTo($usuario, RecuperarContrasenaNotification::class);

        // Correo inexistente (debe devolver el mismo mensaje sin revelar existencia)
        $responseInexistente = $this->post('/olvide-contrasena', [
            'correo' => 'no_existe_absolutamente_' . time() . '@dentalcare.com',
        ]);
        $responseInexistente->assertSessionHas('status');
    }

    public function test_restablecimiento_de_contrasena_con_token_valido_actualiza_hash(): void
    {
        $usuario = Usuario::where('activo', true)->first();
        $token = Password::broker('usuarios')->createToken($usuario);
        $nuevaClave = 'NuevaClaveSuperSegura99!';

        $response = $this->post('/recuperar-contrasena', [
            'token' => $token,
            'correo' => $usuario->correo,
            'contrasena' => $nuevaClave,
            'contrasena_confirmation' => $nuevaClave,
        ]);

        $response->assertRedirect('/login');

        $usuario->refresh();
        $this->assertTrue(Hash::check($nuevaClave, $usuario->contrasena));
        $this->assertFalse((bool) $usuario->debe_cambiar_contrasena);
    }

    public function test_restablecimiento_con_token_invalido_falla(): void
    {
        $usuario = Usuario::where('activo', true)->first();

        $response = $this->post('/recuperar-contrasena', [
            'token' => 'token_totalmente_invalido_y_falso',
            'correo' => $usuario->correo,
            'contrasena' => 'NuevaClaveSegura123!',
            'contrasena_confirmation' => 'NuevaClaveSegura123!',
        ]);

        $response->assertSessionHasErrors('correo');
    }
}
