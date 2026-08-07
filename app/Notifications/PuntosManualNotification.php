<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PuntosManualNotification extends Notification
{
    use Queueable;

    public function __construct(
        public int    $puntos,
        public string $motivo,
        public string $peiNombre,
        public string $adminNombre
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $emoji = match(true) {
            $this->puntos >= 50 => '🏆',
            $this->puntos >= 10 => '⭐',
            default             => '✅',
        };

        return (new MailMessage)
            ->subject("{$emoji} Recibiste +{$this->puntos} puntos en SIPLAN")
            ->greeting("¡Hola, {$notifiable->name}!")
            ->line("El administrador **{$this->adminNombre}** te otorgó **+{$this->puntos} puntos** en el plan:")
            ->line("> **{$this->peiNombre}**")
            ->line("**Motivo:** {$this->motivo}")
            ->action('Ver mi perfil de gamificación', url('/perfil'))
            ->line('¡Seguí así, tu esfuerzo es reconocido!')
            ->salutation('Saludos, SIPLAN — Sistema de Planificación IPS');
    }
}
