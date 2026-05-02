<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Admin\Globales\Organigrama;
use Carbon\Carbon;

class SiessExtracto extends Model
{
    use SoftDeletes;

    protected $table = 'estadistica.siess_extractos';

    protected $fillable = [
        'modulo_id', 'indicador_id', 'periodo_id', 'fuente_id',
        'direccion_id', 'estado', 'datos', 'resumen',
        'cargado_por', 'aprobado_por',
        'fecha_envio_validacion', 'fecha_limite_validacion', 'fecha_respuesta',
        'observacion_objecion', 'es_fuente_unica', 'fecha_fuente_unica',
    ];

    protected $casts = [
        'datos'               => 'array',
        'es_fuente_unica'     => 'boolean',
        'fecha_envio_validacion'  => 'datetime',
        'fecha_limite_validacion' => 'datetime',
        'fecha_respuesta'         => 'datetime',
        'fecha_fuente_unica'      => 'datetime',
    ];

    // ── Estados ───────────────────────────────────────────────────────────────
    const ESTADO_BORRADOR              = 'borrador';
    const ESTADO_PENDIENTE_VALIDACION  = 'pendiente_validacion';
    const ESTADO_APROBADO              = 'aprobado';
    const ESTADO_OBJETADO              = 'objetado';
    const ESTADO_APROBADO_SILENCIO     = 'aprobado_silencio';

    public static function estadoLabel(string $estado): string
    {
        return match($estado) {
            self::ESTADO_BORRADOR             => 'Borrador',
            self::ESTADO_PENDIENTE_VALIDACION => 'Pendiente de Validación',
            self::ESTADO_APROBADO             => 'Aprobado',
            self::ESTADO_OBJETADO             => 'Objetado',
            self::ESTADO_APROBADO_SILENCIO    => 'Aprobado por Silencio',
            default                           => ucfirst($estado),
        };
    }

    public static function estadoBadge(string $estado): string
    {
        return match($estado) {
            self::ESTADO_BORRADOR             => 'badge-secondary',
            self::ESTADO_PENDIENTE_VALIDACION => 'badge-warning',
            self::ESTADO_APROBADO             => 'badge-success',
            self::ESTADO_OBJETADO             => 'badge-danger',
            self::ESTADO_APROBADO_SILENCIO    => 'badge-info',
            default                           => 'badge-light',
        };
    }

    public function estaAprobado(): bool
    {
        return in_array($this->estado, [self::ESTADO_APROBADO, self::ESTADO_APROBADO_SILENCIO]);
    }

    // ── Máquina de estados (Art. 8 Res. 266/2022) ─────────────────────────────

    /**
     * Enviar a validación — calcula fecha límite (+5 días hábiles)
     */
    public function enviarAValidacion(int $userId): void
    {
        $this->estado                   = self::ESTADO_PENDIENTE_VALIDACION;
        $this->fecha_envio_validacion   = now();
        $this->fecha_limite_validacion  = $this->calcularFechaLimite(now(), 5);
        $this->save();

        $this->registrarValidacion('envio', $userId);

        // Notificar a la dirección responsable
        \App\Models\Estadistica\SiessNotificacion::crearParaValidacion($this->load(['modulo','indicador','periodo']));
    }

    public function aprobar(int $userId, ?string $comentario = null): void
    {
        $this->estado           = self::ESTADO_APROBADO;
        $this->aprobado_por     = $userId;
        $this->fecha_respuesta  = now();
        $this->save();

        $this->registrarValidacion('aprobacion', $userId, $comentario);
        \App\Models\Estadistica\SiessNotificacion::crearParaAprobacion($this->load(['modulo','indicador','periodo']), 'aprobado');
    }

    public function objetar(int $userId, string $observacion): void
    {
        $this->estado                  = self::ESTADO_OBJETADO;
        $this->observacion_objecion    = $observacion;
        $this->fecha_respuesta         = now();
        $this->save();

        $this->registrarValidacion('objecion', $userId, $observacion);
        \App\Models\Estadistica\SiessNotificacion::crearParaAprobacion($this->load(['modulo','indicador','periodo']), 'objetado');
    }

    public function aprobarPorSilencio(): void
    {
        $this->estado           = self::ESTADO_APROBADO_SILENCIO;
        $this->fecha_respuesta  = now();
        $this->save();

        $this->registrarValidacion('silencio', null, 'Aprobado automáticamente por vencimiento del plazo (Art. 8 Res. 266/2022)');
        \App\Models\Estadistica\SiessNotificacion::crearParaAprobacion($this->load(['modulo','indicador','periodo']), 'aprobado_silencio');
    }

    public function marcarFuenteUnica(int $userId): void
    {
        $this->es_fuente_unica      = true;
        $this->fecha_fuente_unica   = now();
        $this->save();

        $this->registrarValidacion('fuente_unica', $userId, 'Marcado como fuente única de consulta (Art. 6 Res. 266/2022)');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Calcula fecha límite sumando N días hábiles (lunes-viernes)
     */
    public function calcularFechaLimite(Carbon $desde, int $diasHabiles): Carbon
    {
        $fecha = $desde->copy();
        $contados = 0;
        while ($contados < $diasHabiles) {
            $fecha->addDay();
            if ($fecha->isWeekday()) {
                $contados++;
            }
        }
        return $fecha;
    }

    public function diasRestantes(): ?int
    {
        if ($this->estado !== self::ESTADO_PENDIENTE_VALIDACION || !$this->fecha_limite_validacion) {
            return null;
        }
        return max(0, now()->diffInDays($this->fecha_limite_validacion, false));
    }

    private function registrarValidacion(string $accion, ?int $userId, ?string $comentario = null): void
    {
        SiessValidacion::create([
            'extracto_id' => $this->id,
            'accion'      => $accion,
            'usuario_id'  => $userId,
            'comentario'  => $comentario,
            'fecha'       => now(),
            'metadata'    => json_encode(['estado' => $this->estado]),
        ]);
    }

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(SiessModulo::class, 'modulo_id');
    }

    public function indicador(): BelongsTo
    {
        return $this->belongsTo(SiessIndicador::class, 'indicador_id');
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(SiessPeriodo::class, 'periodo_id');
    }

    public function fuente(): BelongsTo
    {
        return $this->belongsTo(SiessFuente::class, 'fuente_id');
    }

    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Organigrama::class, 'direccion_id');
    }

    public function cargadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cargado_por');
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function validaciones(): HasMany
    {
        return $this->hasMany(SiessValidacion::class, 'extracto_id')->orderBy('fecha');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeAprobados($query)
    {
        return $query->whereIn('estado', [self::ESTADO_APROBADO, self::ESTADO_APROBADO_SILENCIO]);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE_VALIDACION);
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE_VALIDACION)
                     ->where('fecha_limite_validacion', '<', now());
    }
}
