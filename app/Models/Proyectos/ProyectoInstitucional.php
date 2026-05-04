<?php

namespace App\Models\Proyectos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\PeiProfile;

class ProyectoInstitucional extends Model
{
    use SoftDeletes;

    protected $table = 'proyectos_institucionales';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'estado',
        'pei_profile_id', 'dependencia_solicitante_id', 'dependencia_ejecutora_id',
        'analista_id', 'created_by',
        'fecha_solicitud', 'fecha_aprobacion', 'fecha_inicio_ejecucion',
        'fecha_fin_estimada', 'fecha_fin_real',
        'presupuesto_estimado', 'presupuesto_aprobado', 'presupuesto_ejecutado',
        'nro_resolucion', 'fecha_resolucion',
        'motivo_rechazo', 'tipo_rechazo', 'avance_pct',
    ];

    protected $casts = [
        'fecha_solicitud'        => 'date',
        'fecha_aprobacion'       => 'date',
        'fecha_inicio_ejecucion' => 'date',
        'fecha_fin_estimada'     => 'date',
        'fecha_fin_real'         => 'date',
        'fecha_resolucion'       => 'date',
    ];

    // ── Estados ───────────────────────────────────────────────────────────────
    const ESTADOS = [
        'solicitud'          => 'Solicitud',
        'en_analisis'        => 'En Análisis de Factibilidad',
        'en_desarrollo'      => 'En Desarrollo',
        'en_homologacion'    => 'En Homologación Técnica',
        'consulta_gerencias' => 'Consulta a Gerencias',
        'en_tramite'         => 'En Trámite de Resolución',
        'aprobado'           => 'Aprobado',
        'en_ejecucion'       => 'En Ejecución',
        'finalizado'         => 'Finalizado',
        'rechazado_docs'     => 'Rechazado — Falta Documentación',
        'rechazado_tecnico'  => 'Rechazado — Error Técnico',
    ];

    const CHECKLIST_ITEMS = [
        'matriz_resultados'       => 'Matriz de Resultados',
        'cronograma'              => 'Cronograma',
        'presupuesto_detallado'   => 'Presupuesto Detallado',
        'estudio_factibilidad'    => 'Estudio de Factibilidad',
        'documentos_respaldatorios' => 'Documentos Respaldatorios',
        'datos_interes'           => 'Datos de Interés',
        'alineacion_pei'          => 'Alineación Estratégica (PEI)',
        'impacto_gerencias'       => 'Impacto en otras Gerencias',
        'plan_riesgos'            => 'Plan de Riesgos',
    ];

    public static function estadoLabel(string $estado): string
    {
        return self::ESTADOS[$estado] ?? ucfirst($estado);
    }

    public static function estadoBadge(string $estado): string
    {
        return match($estado) {
            'solicitud'          => 'badge-secondary',
            'en_analisis'        => 'badge-info',
            'en_desarrollo'      => 'badge-primary',
            'en_homologacion'    => 'badge-warning',
            'consulta_gerencias' => 'badge-warning',
            'en_tramite'         => 'badge-dark',
            'aprobado'           => 'badge-success',
            'en_ejecucion'       => 'badge-success',
            'finalizado'         => 'badge-secondary',
            'rechazado_docs',
            'rechazado_tecnico'  => 'badge-danger',
            default              => 'badge-light',
        };
    }

    // ── Generador de código ───────────────────────────────────────────────────
    public static function generarCodigo(): string
    {
        $anio     = now()->year;
        $ultimo   = self::withTrashed()
            ->where('codigo', 'like', "PROY-{$anio}-%")
            ->orderByDesc('id')->value('codigo');
        $secuencia = $ultimo
            ? (int) substr($ultimo, -3) + 1
            : 1;
        return sprintf('PROY-%d-%03d', $anio, $secuencia);
    }

    // ── Máquina de estados ────────────────────────────────────────────────────
    public function avanzarEstado(string $nuevoEstado, int $userId, ?string $comentario = null): void
    {
        $estadoAnterior = $this->estado;
        $this->estado   = $nuevoEstado;

        // Fechas automáticas
        match($nuevoEstado) {
            'aprobado'      => $this->fecha_aprobacion = now(),
            'en_ejecucion'  => $this->fecha_inicio_ejecucion = now(),
            'finalizado'    => $this->fecha_fin_real = now(),
            default         => null,
        };

        $this->save();

        // Registrar en historial
        ProyectoHistorial::create([
            'proyecto_id'     => $this->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => $nuevoEstado,
            'usuario_id'      => $userId,
            'comentario'      => $comentario,
            'fecha'           => now(),
        ]);
    }

    public function checklistCompleto(): bool
    {
        return $this->checklist()->where('completado', false)->doesntExist()
            && $this->checklist()->count() === count(self::CHECKLIST_ITEMS);
    }

    public function pctChecklist(): int
    {
        $total     = count(self::CHECKLIST_ITEMS);
        $completados = $this->checklist()->where('completado', true)->count();
        return $total > 0 ? (int) round($completados / $total * 100) : 0;
    }

    // ── Relaciones ────────────────────────────────────────────────────────────
    public function peiProfile(): BelongsTo
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function dependenciaSolicitante(): BelongsTo
    {
        return $this->belongsTo(Organigrama::class, 'dependencia_solicitante_id');
    }

    public function dependenciaEjecutora(): BelongsTo
    {
        return $this->belongsTo(Organigrama::class, 'dependencia_ejecutora_id');
    }

    public function analista(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analista_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checklist(): HasMany
    {
        return $this->hasMany(ProyectoChecklist::class, 'proyecto_id');
    }

    public function consultasGerencias(): HasMany
    {
        return $this->hasMany(ProyectoConsultaGerencia::class, 'proyecto_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(ProyectoHistorial::class, 'proyecto_id')->orderBy('fecha');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeActivos($q)
    {
        return $q->whereNotIn('estado', ['finalizado','rechazado_docs','rechazado_tecnico']);
    }

    public function scopeEnEjecucion($q)
    {
        return $q->where('estado', 'en_ejecucion');
    }
}
