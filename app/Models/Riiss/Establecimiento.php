<?php

namespace App\Models\Riiss;

use App\Enums\ComplejidadEnum;
use App\Enums\TipoEstablecimientoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Establecimiento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'establecimientos';
    protected $primaryKey = 'id_establecimiento';
    public    $incrementing = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'id_establecimiento', 'nombre_oficial', 'codigo', 'tipo_est',
        'complejidad', 'departamento', 'microred', 'prestador',
        'nro_departamento', 'tipologia_clasificacion', 'latitude', 'longitude',
        'nm_empresa_costos', 'access_nm_empresa', 'area_gestion',
        'situacion_inmueble', 'observacion', 'sistema_hospitalario',
        'activo', 'codigo_ine',
        'nivel_atencion', 'grado_complejidad', 'es_hospitalario',
        'tiene_internacion', 'tiene_quirofano_req', 'tiene_uti_req', 'tiene_urgencias_req',
        'condicion_inmueble', 'superficie_terreno', 'superficie_construida', 'plano_url',
        'nro_llamado', 'nro_contrato_alquiler', 'propietario', 'vigencia_desde',
        'vigencia_hasta', 'canon_mensual', 'fecha_pago_alquiler',
        'nro_resolucion_convenio', 'vigencia_convenio_desde', 'vigencia_convenio_hasta',
        'descripcion_convenio', 'locales_convenio', 'archivo_convenio_url',
        'habilita_farmacia_cronicos', 'habilita_empadronamiento_cronicos',
    ];

    protected $casts = [
        'latitude'                       => 'decimal:8',
        'longitude'                      => 'decimal:8',
        'activo'                         => 'boolean',
        'es_hospitalario'                => 'boolean',
        'tiene_internacion'              => 'boolean',
        'tiene_quirofano_req'            => 'boolean',
        'tiene_uti_req'                  => 'boolean',
        'tiene_urgencias_req'            => 'boolean',
        'habilita_farmacia_cronicos'     => 'boolean',
        'habilita_empadronamiento_cronicos' => 'boolean',
    ];

    protected $appends = [
        'tipo_est_label', 'complejidad_label', 'complejidad_color',
        'coordenadas', 'es_asistencial',
    ];

    // ─── RELATIONSHIPS ───────────────────────────────────────────

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class, 'id_establecimiento', 'id_establecimiento');
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(Evaluacion::class, 'id_establecimiento');
    }

    public function inmuebleContratos(): HasMany
    {
        return $this->hasMany(InmuebleContrato::class, 'id_establecimiento', 'id_establecimiento');
    }

    public function complejidadTipo(): BelongsTo
    {
        return $this->belongsTo(ComplejidadTipo::class, 'complejidad_tipo_id');
    }

    public function homologaciones(): HasMany
    {
        return $this->hasMany(Homologacion::class, 'id_establecimiento_destino');
    }

    public function ultimaEvaluacion(): HasOne
    {
        return $this->hasOne(Evaluacion::class, 'id_establecimiento')->latestOfMany();
    }

    // ─── ACCESSORS ───────────────────────────────────────────────

    public function getTipoEstLabelAttribute(): string
    {
        if (empty($this->tipo_est)) {
            return '';
        }
        return TipoEstablecimientoEnum::tryFrom($this->tipo_est)?->label() ?? $this->tipo_est;
    }

    public function getComplejidadLabelAttribute(): string
    {
        if ($this->complejidadTipo) {
            return 'Nivel ' . $this->complejidadTipo->nivel_atencion . ' - ' . $this->complejidadTipo->nombre;
        }
        return ComplejidadEnum::fromString($this->complejidad)?->label() ?? $this->complejidad;
    }

    public function getComplejidadColorAttribute(): string
    {
        if ($this->complejidadTipo) {
            return $this->complejidadTipo->color;
        }
        return ComplejidadEnum::fromString($this->complejidad)?->color() ?? '#6b7280';
    }

    public function getCoordenadasAttribute(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return ['lat' => (float) $this->latitude, 'lng' => (float) $this->longitude];
        }
        return null;
    }

    public function getEsAsistencialAttribute(): bool
    {
        return TipoEstablecimientoEnum::tryFrom($this->tipo_est)?->esAsistencial() ?? false;
    }

    // ─── SCOPES ──────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeAsistenciales($query)
    {
        return $query->whereIn('tipo_est', ['PS', 'HR', 'US', 'CE', 'CP', 'HO', 'HC', 'H']);
    }

    public function scopePorDepartamento($query, string $depto)
    {
        return $query->where('departamento', $depto);
    }

    public function scopePorMicrored($query, string $microred)
    {
        return $query->where('microred', $microred);
    }

    public function scopePorComplejidad($query, string $complejidad)
    {
        return $query->where('complejidad', $complejidad);
    }

    public function scopePorTipo($query, string $tipo)
    {
        return $query->where('tipo_est', $tipo);
    }

    public function scopeHospitalarios($query)
    {
        return $query->where('es_hospitalario', true);
    }

    public function scopeConInternacion($query)
    {
        return $query->where('tiene_internacion', true);
    }

    public function scopePorNivel($query, int $nivel)
    {
        return $query->where('nivel_atencion', $nivel);
    }

    // ─── METHODS ─────────────────────────────────────────────────

    /**
     * Recalcula los campos derivados de la complejidad.
     */
    public function recalcularCamposDerivados(): void
    {
        $tipo = $this->complejidadTipo;
        if ($tipo) {
            $this->nivel_atencion      = $tipo->nivel_atencion;
            $this->grado_complejidad   = $tipo->grado;
            $this->es_hospitalario     = $tipo->es_hospitalario;
            $this->tiene_internacion   = $tipo->requiere_internacion;
            $this->tiene_quirofano_req = $tipo->requiere_quirofano;
            $this->tiene_uti_req       = $tipo->requiere_uti;
            $this->tiene_urgencias_req = $tipo->requiere_urgencias;
        }
        $this->saveQuietly();
    }

    public function toResumenArray(): array
    {
        return [
            'id'                      => $this->id_establecimiento,
            'nombre'                  => $this->nombre_oficial,
            'tipo'                    => $this->tipo_est,
            'tipo_label'              => $this->tipo_est_label,
            'tipologia'               => $this->tipologia_clasificacion,
            'complejidad'             => $this->complejidad,
            'complejidad_tipo_id'     => $this->complejidad_tipo_id,
            'complejidad_label'       => $this->complejidad_label,
            'complejidad_color'       => $this->complejidad_color,
            'nivel'                   => $this->nivel_atencion,
            'grado'                   => $this->grado_complejidad,
            'departamento'            => $this->departamento,
            'microred'                => $this->microred,
            'prestador'               => $this->prestador,
            'coordenadas'             => $this->coordenadas,
            'tiene_ultima_evaluacion' => $this->ultimaEvaluacion()->exists(),
        ];
    }

    public function especialidades()
    {
        return $this->belongsToMany(\App\Models\RiissEspecialidad::class, 'riiss_establecimiento_especialidades', 'establecimiento_id', 'especialidad_id');
    }

    public function medicamentos()
    {
        return $this->belongsToMany(\App\Models\RiissMedicamento::class, 'riiss_est_esp_medicamentos', 'establecimiento_id', 'medicamento_id')
                    ->withPivot('especialidad_id');
    }
}
