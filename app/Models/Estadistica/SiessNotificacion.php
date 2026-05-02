<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Admin\Globales\Organigrama;

class SiessNotificacion extends Model
{
    protected $table = 'estadistica.siess_notificaciones';

    protected $fillable = [
        'extracto_id', 'user_id', 'direccion_id',
        'tipo', 'titulo', 'mensaje', 'leida', 'leida_at',
    ];

    protected $casts = [
        'leida'    => 'boolean',
        'leida_at' => 'datetime',
    ];

    public function extracto(): BelongsTo  { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function usuario(): BelongsTo   { return $this->belongsTo(User::class, 'user_id'); }
    public function direccion(): BelongsTo { return $this->belongsTo(Organigrama::class, 'direccion_id'); }

    public function marcarLeida(): void
    {
        $this->update(['leida' => true, 'leida_at' => now()]);
    }

    public function iconoTipo(): string
    {
        return match($this->tipo) {
            'envio_validacion'   => 'fa-paper-plane text-warning',
            'aprobado'           => 'fa-check-circle text-success',
            'objetado'           => 'fa-times-circle text-danger',
            'aprobado_silencio'  => 'fa-volume-mute text-info',
            'vencimiento_proximo'=> 'fa-exclamation-triangle text-danger',
            'fuente_unica'       => 'fa-bookmark text-secondary',
            default              => 'fa-bell text-muted',
        };
    }

    // ── Factory methods ───────────────────────────────────────────────────────

    public static function crearParaValidacion(SiessExtracto $extracto): void
    {
        if (!$extracto->direccion_id) return;

        // Obtener el user_id del responsable de la dirección (organigramas.user_id)
        $organigrama = \App\Admin\Globales\Organigrama::find($extracto->direccion_id);
        $userId = $organigrama?->user_id;

        $titulo  = 'Extracto pendiente de validación';
        $mensaje = sprintf(
            'El extracto "%s — %s" del período %s requiere su validación. Tiene hasta el %s para aprobar u objetar (Art. 8 Res. 266/2022).',
            $extracto->modulo->codigo,
            $extracto->indicador->nombre,
            $extracto->periodo->nombre,
            $extracto->fecha_limite_validacion?->format('d/m/Y') ?? '—'
        );

        self::create([
            'extracto_id' => $extracto->id,
            'user_id'     => $userId,        // usuario responsable de la dirección
            'direccion_id'=> $extracto->direccion_id,
            'tipo'        => 'envio_validacion',
            'titulo'      => $titulo,
            'mensaje'     => $mensaje,
        ]);
    }

    public static function crearAlertaVencimiento(SiessExtracto $extracto): void
    {
        if (!$extracto->direccion_id) return;

        $organigrama = \App\Admin\Globales\Organigrama::find($extracto->direccion_id);
        $userId = $organigrama?->user_id;
        $dias   = $extracto->diasRestantes();

        self::create([
            'extracto_id' => $extracto->id,
            'user_id'     => $userId,
            'direccion_id'=> $extracto->direccion_id,
            'tipo'        => 'vencimiento_proximo',
            'titulo'      => '⚠ Plazo de validación próximo a vencer',
            'mensaje'     => sprintf(
                'El extracto "%s — %s" vence en %s día(s). Si no responde, será aprobado automáticamente por silencio administrativo.',
                $extracto->modulo->codigo,
                $extracto->indicador->nombre,
                $dias
            ),
        ]);
    }

    public static function crearParaAprobacion(SiessExtracto $extracto, string $tipo): void
    {
        $labels = [
            'aprobado'          => ['✅ Extracto aprobado',                    'fa-check-circle'],
            'objetado'          => ['❌ Extracto objetado',                    'fa-times-circle'],
            'aprobado_silencio' => ['🔇 Aprobado por silencio administrativo', 'fa-volume-mute'],
        ];

        [$titulo] = $labels[$tipo] ?? ['Cambio de estado', 'fa-bell'];

        self::create([
            'extracto_id' => $extracto->id,
            'user_id'     => $extracto->cargado_por,
            'tipo'        => $tipo,
            'titulo'      => $titulo,
            'mensaje'     => sprintf(
                'El extracto "%s — %s" del período %s cambió a estado: %s.',
                $extracto->modulo->codigo,
                $extracto->indicador->nombre,
                $extracto->periodo->nombre,
                SiessExtracto::estadoLabel($extracto->estado)
            ),
        ]);
    }
}
