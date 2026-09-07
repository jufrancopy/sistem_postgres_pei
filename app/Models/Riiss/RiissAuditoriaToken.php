<?php

namespace App\Models\Riiss;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RiissAuditoriaToken extends Model
{
    use HasFactory;

    protected $table = 'riiss_auditoria_tokens';

    protected $fillable = [
        'token',
        'pin',
        'establecimiento_id',
        'destinatario',
        'duracion_horas',
        'expira_en',
        'creado_por',
        'visitas_count',
        'ultimo_acceso_at',
        'ip_ultimo_acceso',
        'estado',
    ];

    protected $casts = [
        'expira_en'        => 'datetime',
        'ultimo_acceso_at' => 'datetime',
        'duracion_horas'   => 'integer',
        'visitas_count'    => 'integer',
    ];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id', 'id_establecimiento');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function isExpirado(): bool
    {
        return Carbon::now()->greaterThan($this->expira_en) || $this->estado !== 'activo';
    }

    public function getMinutosRestantesAttribute(): int
    {
        if ($this->isExpirado()) {
            return 0;
        }
        return Carbon::now()->diffInMinutes($this->expira_en, false);
    }

    public function getTiempoRestanteTextoAttribute(): string
    {
        if ($this->isExpirado()) {
            return 'Expirado';
        }
        $now = Carbon::now();
        $horas = $now->diffInHours($this->expira_en);
        $minutos = $now->diffInMinutes($this->expira_en) % 60;

        if ($horas > 0) {
            return "{$horas}h {$minutos}m";
        }
        return "{$minutos}m";
    }

    public function isOnline(): bool
    {
        if ($this->isExpirado() || !$this->ultimo_acceso_at) {
            return false;
        }
        return $this->ultimo_acceso_at->diffInMinutes(Carbon::now()) <= 3;
    }

    public function getEstadoAuditorAttribute(): string
    {
        if ($this->estado === 'revocado') {
            return 'revocado';
        }
        if (Carbon::now()->greaterThan($this->expira_en)) {
            return 'expirado';
        }
        if ($this->isOnline()) {
            return 'online';
        }
        if ($this->visitas_count > 0 && $this->ultimo_acceso_at) {
            return 'desconectado';
        }
        return 'pendiente';
    }

    public function getUltimoAccesoHumanoAttribute(): string
    {
        if (!$this->ultimo_acceso_at) {
            return 'Sin accesos aún';
        }
        return $this->ultimo_acceso_at->diffForHumans();
    }

    public static function generar(
        ?string $establecimientoId = null,
        int $duracionHoras = 24,
        ?string $destinatario = null,
        ?int $userId = null
    ): self {
        $token = Str::random(40);
        $pin = str_pad((string) mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiraEn = Carbon::now()->addHours($duracionHoras);

        return self::create([
            'token'              => $token,
            'pin'                => $pin,
            'establecimiento_id' => $establecimientoId,
            'destinatario'       => $destinatario,
            'duracion_horas'     => $duracionHoras,
            'expira_en'          => $expiraEn,
            'creado_por'         => $userId,
            'estado'             => 'activo',
        ]);
    }
}
