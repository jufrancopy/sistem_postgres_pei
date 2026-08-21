<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MecipNotificacionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $casoId;
    public string $numeroCaso;
    public string $subproceso;
    public string $mensaje;
    public string $accion;
    public ?int $destinatarioId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $casoId, string $numeroCaso, string $subproceso, string $mensaje, string $accion = 'remitido', ?int $destinatarioId = null)
    {
        $this->casoId         = $casoId;
        $this->numeroCaso     = $numeroCaso;
        $this->subproceso      = $subproceso;
        $this->mensaje        = $mensaje;
        $this->accion         = $accion;
        $this->destinatarioId = $destinatarioId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('mecip-notificaciones'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'mecip.notificacion';
    }
}
