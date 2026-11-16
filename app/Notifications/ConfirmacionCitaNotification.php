<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmacionCitaNotification extends Notification
{
    use Queueable;

    public Cita $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $fecha = $this->cita->fecha instanceof \DateTimeInterface
            ? $this->cita->fecha->format('d/m/Y')
            : (string) $this->cita->fecha;
        $hora = substr($this->cita->hora_inicio, 0, 5);
        $doctor = $this->cita->odontologo?->usuario?->nombre ?? 'Especialista DentalCare';
        $servicios = $this->cita->servicios->pluck('nombre')->join(', ');

        return (new MailMessage)
            ->subject('Confirmación de Cita Odontológica #' . $this->cita->id . ' · DentalCare')
            ->greeting('¡Hola, ' . $notifiable->nombre . '!')
            ->line('Nos complace informarte que tu cita odontológica ha sido confirmada con éxito.')
            ->line('**Detalles de tu consulta:**')
            ->line('• **Fecha:** ' . $fecha)
            ->line('• **Hora de inicio:** ' . $hora)
            ->line('• **Profesional a cargo:** ' . $doctor)
            ->line('• **Procedimiento(s):** ' . ($servicios ?: 'Valoración general'))
            ->line('• **Ubicación:** Sede Principal DentalCare, Cali')
            ->action('Ver Estado de mi Cita', url('/paciente/citas'))
            ->line('Por favor recuerda presentarte 10 minutos antes. La tolerancia máxima para el inicio de consulta es de 15 minutos.')
            ->salutation('Atentamente, el equipo de DentalCare');
    }
}
