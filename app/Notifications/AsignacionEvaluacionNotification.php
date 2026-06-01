<?php

namespace App\Notifications;

use App\Models\Riiss\Asignacion;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AsignacionEvaluacionNotification extends Notification
{
    use Queueable;

    public function __construct(public Asignacion $asignacion) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $est      = $this->asignacion->establecimiento;
        $admin    = $this->asignacion->asignadoPor;
        $limite   = $this->asignacion->fecha_limite?->format('d/m/Y') ?? 'Sin fecha límite';
        $url      = url('/riiss/mis-asignaciones');

        return (new MailMessage)
            ->subject('Nueva asignación de evaluación — ' . $est->nombre_oficial)
            ->greeting('Hola, ' . $notifiable->name . '.')
            ->line('Se te ha asignado una evaluación de cartera de servicios.')
            ->line('**Establecimiento:** ' . $est->nombre_oficial)
            ->line('**Tipología:** ' . $est->tipologia_clasificacion)
            ->line('**Complejidad:** ' . $est->complejidad)
            ->line('**Fecha límite:** ' . $limite)
            ->line('**Asignado por:** ' . $admin->name)
            ->when($this->asignacion->instrucciones, fn($m) =>
                $m->line('**Instrucciones:** ' . $this->asignacion->instrucciones)
            )
            ->action('Ver mis asignaciones', $url)
            ->line('Por favor completá la evaluación antes de la fecha límite.')
            ->salutation('SIPLAN — Sistema de Planificación IPS');
    }
}
