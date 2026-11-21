<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CancelacionCitaNotification extends Notification
{
    use Queueable;

    public Cita $cita;
    public ?string $motivo;

    public function __construct(Cita $cita, ?string $motivo = null)
    {
        $this->cita = $cita;
        $this->motivo = $motivo;
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

        $mail = (new MailMessage)
            ->subject('Cancelación de Cita Odontológica #' . $this->cita->id . ' · DentalCare')
            ->greeting('Estimado(a) ' . $notifiable->nombre . ',')
            ->line('Te informamos que tu cita programada para el día **' . $fecha . '** a las **' . $hora . '** ha sido cancelada en nuestro sistema.');

        if ($this->motivo) {
            $mail->line('**Motivo registrado:** ' . $this->motivo);
        }

        return $mail->line('Si deseas reprogramar tu consulta, puedes hacerlo en cualquier momento ingresando a tu portal de paciente.')
                    ->action('Reprogramar mi Cita en Línea', url('/paciente/agendar'))
                    ->salutation('Atentamente, el equipo de DentalCare');
    }
}
