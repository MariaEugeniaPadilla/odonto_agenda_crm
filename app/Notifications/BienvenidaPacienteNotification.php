<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BienvenidaPacienteNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('¡Bienvenido(a) a DentalCare! - Registro de Paciente')
            ->greeting('¡Hola, ' . $notifiable->nombre . '!')
            ->line('Te damos una cordial bienvenida a la plataforma oficial de la Clínica Odontológica DentalCare.')
            ->line('Tu cuenta de Paciente ha sido creada exitosamente. A partir de este momento puedes:')
            ->line('• Agendar tus citas odontológicas en línea con nuestros especialistas.')
            ->line('• Consultar tus citas programadas y estado de atención en tiempo real.')
            ->line('• Revisar el historial de tus tratamientos y evolución clínica.')
            ->action('Ingresar a mi Portal de Paciente', url('/login'))
            ->line('Recuerda que para mantener tu agendamiento web activo es importante asistir puntualmente o reprogramar con anticipación.')
            ->salutation('Atentamente, el equipo de DentalCare · Clínica Odontológica Especializada');
    }
}
