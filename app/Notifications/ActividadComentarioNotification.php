<?php

namespace App\Notifications;

use App\Admin\Globales\Activity;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\ActivityTaskComment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class ActividadComentarioNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Activity            $activity,
        public ActivityTask        $task,
        public ActivityTaskComment $comment,
        public string              $autorNombre,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/mis-tareas/' . $this->activity->id);

        return (new MailMessage)
            ->subject('Nuevo comentario — ' . $this->task->title)
            ->greeting('Hola, ' . $notifiable->name . '.')
            ->line($this->autorNombre . ' dejó un comentario en la tarea **' . $this->task->title . '** de la actividad **' . $this->activity->name . '**:')
            ->line('"' . Str::limit($this->comment->comentario, 300) . '"')
            ->action('Ver mis tareas', $url)
            ->line('Podés responder desde el tablero de tareas.')
            ->salutation('Saludos, SIPLAN — Sistema de Planificación IPS');
    }
}
