<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $table = 'system_notifications';

    protected $fillable = [
        'user_id', 'tipo', 'titulo', 'mensaje', 'icono', 'url', 'leida', 'leida_at',
    ];

    protected $casts = [
        'leida'    => 'boolean',
        'leida_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marcarLeida(): void
    {
        $this->update(['leida' => true, 'leida_at' => now()]);
    }

    // ── Factory methods ───────────────────────────────────────────────────────

    public static function crearPuntosManual(int $userId, int $puntos, string $motivo, string $peiNombre, string $adminNombre): self
    {
        $emoji = match(true) {
            $puntos >= 50 => '🏆',
            $puntos >= 10 => '⭐',
            default       => '✅',
        };

        return self::create([
            'user_id' => $userId,
            'tipo'    => 'puntos_manual',
            'titulo'  => "{$emoji} ¡Recibiste +{$puntos} puntos!",
            'mensaje' => "El administrador <b>{$adminNombre}</b> te otorgó <b>+{$puntos} pts</b> en <i>{$peiNombre}</i>.<br><small class=\"text-muted\">{$motivo}</small>",
            'icono'   => 'fa-star text-warning',
            'url'     => '/perfil',
        ]);
    }
}
