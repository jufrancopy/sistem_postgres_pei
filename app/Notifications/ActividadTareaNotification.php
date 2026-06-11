<?php

namespace App\Notifications;

use App\Admin\Globales\Activity;
use App\Admin\Globales\ActivityTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class ActividadTareaNotification extends Notification
{
    use Queueable;

    /**
     * @param Activity   $activity  Actividad padre
     * @param Collection $tareas    Tareas asignadas al usuario (puede ser una o varias)
     * @param string     $tipo      'asignacion' | 'recordatorio' | 'tarea_individual'
     */
    public function __construct(
        public Activity   $activity,
        public Collection $tareas,
        public string     $tipo = 'recordatorio'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $asunto = match($this->tipo) {
            'asignacion'       => 'Nueva tarea asignada — ' . $this->activity->name,
            'tarea_individual' => 'Recordatorio de tarea — ' . ($this->tareas->first()?->title ?? ''),
            default            => 'Resumen de tareas — ' . $this->activity->name,
        };

        $estados = [0 => 'Pendiente', 1 => 'En Progreso', 2 => 'Completada', 3 => 'En Revisión'];
        $url = url('/mis-tareas/' . $this->activity->id);

        $mail = (new MailMessage)
            ->subject($asunto)
            ->greeting('Hola, ' . $notifiable->name . '.')
            ->line($this->tipo === 'asignacion'
                ? 'Se te ha asignado una nueva tarea en la actividad **' . $this->activity->name . '**.'
                : 'Tenés tareas pendientes en la actividad **' . $this->activity->name . '**.');

        foreach ($this->tareas as $tarea) {
            $estado = $estados[$tarea->status] ?? 'Pendiente';
            $etiqueta = $tarea->etiqueta ? " [{$tarea->etiqueta}]" : '';
            $mail->line("**{$tarea->title}**{$etiqueta} — Estado: {$estado}");
            if ($tarea->details) {
                $mail->line($tarea->details);
            }
        }

        return $mail
            ->action('Ver mis tareas', $url)
            ->line('Podés actualizar el estado de tus tareas desde el sistema.')
            ->salutation('SIPLAN — Sistema de Planificación IPS');
    }
}
