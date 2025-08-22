<?php
// app/Notifications/UpcomingExamNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UpcomingExamNotification extends Notification
{
    use Queueable;

    public function __construct(protected $exam) {}

    public function via($notifiable)
    {
        return ['mail', /* 'nexmo', etc */];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Próximo Examen Médico')
            ->line("Su examen de {$this->exam->profesiogramaExamenTipo->examenTipo->nombre} vence el {$this->exam->fecha_siguiente->format('d/m/Y')}.")
            ->action('Ver en SST Easy', url('/admin/examenes-medicos'));
    }
}
