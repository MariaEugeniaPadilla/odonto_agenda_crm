<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CuentaCreadaAdminNotification extends Notification
{
    use Queueable;

    public string $token;
    public ?string $passwordManual;

    public function __construct(string $token, ?string $passwordManual = null)
    {
        $this->token = $token;
        $this->passwordManual = $passwordManual;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $urlDefinir = url('/recuperar-contrasena/' . $this->token . '?correo=' . urlencode($notifiable->correo));

        $mail = (new MailMessage)
            ->subject('Tu cuenta en DentalCare ha sido creada por el Administrador')
            ->greeting('¡Hola, ' . $notifiable->nombre . '!')
            ->line('La Administración de la Clínica Odontológica DentalCare ha creado una cuenta para ti con el rol de **' . $notifiable->nombre_rol . '**.');

        if ($this->passwordManual) {
            $mail->line('Se te ha configurado una contraseña temporal de acceso:')
                 ->line('**Contraseña inicial:** `' . $this->passwordManual . '`')
                 ->line('Por políticas de seguridad, el sistema te solicitará cambiar tu contraseña inmediatamente en tu primer inicio de sesión.')
                 ->action('Iniciar Sesión en DentalCare', url('/login'));
        } else {
            $mail->line('Para garantizar la privacidad y seguridad de tu cuenta, haz clic en el botón siguiente para definir tu contraseña personal:')
                 ->action('Definir mi Contraseña Segura', $urlDefinir)
                 ->line('Este enlace es seguro y tiene una vigencia de ' . config('auth.passwords.usuarios.expire', 60) . ' minutos.');
        }

        return $mail->line('Si tienes alguna pregunta o no reconoces esta solicitud, contacta a la administración de la clínica.')
                    ->salutation('Administración · Clínica Odontológica DentalCare');
    }
}
