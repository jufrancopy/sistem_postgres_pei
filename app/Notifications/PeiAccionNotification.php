<?php

namespace App\Notifications;

use App\Admin\Planificacion\Pei\PeiProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class PeiAccionNotification extends Notification
{
    use Queueable;

    /**
     * @param PeiProfile $perfil   Perfil PEI raíz
     * @param Collection $acciones Acciones asignadas al usuario
     * @param string     $tipo     'general' | 'individual'
     */
    public function __construct(
        public PeiProfile $perfil,
        public Collection $acciones,
        public string     $tipo = 'general'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $nombrePlan  = strip_tags($this->perfil->name);
        $urlMisAcciones = url('/admin/pei-profiles/' . $this->perfil->id . '/mis-acciones');

        $asunto = $this->tipo === 'individual'
            ? 'Acción asignada — ' . $nombrePlan
            : '[PEI] Tenés acciones asignadas — ' . $nombrePlan;

        $mail = (new MailMessage)
            ->subject($asunto)
            ->greeting('Hola, ' . $notifiable->name . '.')
            ->line($this->tipo === 'individual'
                ? 'Se te asignó una acción estratégica en el plan **' . $nombrePlan . '**.'
                : 'Tenés acciones estratégicas asignadas en el plan **' . $nombrePlan . '** y podés comenzar a reportar tu avance.');

        foreach ($this->acciones as $accion) {
            $nombre = strip_tags($accion->name);
            $ind    = $accion->indicador;
            $mail->line('• **' . $nombre . '**');
            if ($ind) {
                $mail->line('  Indicador: ' . $ind->nombre);
            }
        }

        return $mail
            ->action('Ver mis acciones y reportar avance', $urlMisAcciones)
            ->line('Ingresá al sistema para ver el detalle completo y registrar tus reportes de avance.')
            ->salutation('Dirección de Planificación — SIPLAN');
    }
}
