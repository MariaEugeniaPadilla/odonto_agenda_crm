<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecuperarContrasenaNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $urlReset = url('/recuperar-contrasena/' . $this->token . '?correo=' . urlencode($notifiable->correo));

        return (new MailMessage)
            ->subject('Restablecimiento de Contraseña · DentalCare')
            ->greeting('¡Hola, ' . $notifiable->nombre . '!')
            ->line('Recibiste este correo porque se solicitó restablecer la contraseña de tu cuenta en OdontoAgenda CRM (DentalCare).')
            ->action('Restablecer mi Contraseña', $urlReset)
            ->line('Este enlace seguro expirará en ' . config('auth.passwords.usuarios.expire', 60) . ' minutos.')
            ->line('Si no realizaste esta solicitud, puedes ignorar este mensaje de forma segura; tu contraseña actual continuará protegida.')
            ->salutation('Seguridad y Soporte · Clínica Odontológica DentalCare');
    }
}
