<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormularioPregunta extends Model
{
    protected $table = 'formulario_preguntas';

    protected $fillable = [
        'formulario_seccion_id',
        'dimension',
        'pregunta',
        'tipo_respuesta',
        'opciones',
        'respuesta_ejemplo',
        'orden',
        'grado_complejidad_min',
        'es_requerido',
        'peso_ponderacion',
        'activa',
        'tags_cartera',
        'servicio_cartera_grupo',
        'especialidad_relacionada',
        'metadata_cartera',
        'codigo_pregunta',
    ];

    protected $casts = [
        'tipo_respuesta'       => 'string',
        'opciones'             => 'array',
        'tags_cartera'         => 'array',
        'metadata_cartera'     => 'array',
        'orden'                => 'integer',
        'grado_complejidad_min'=> 'integer',
        'es_requerido'         => 'boolean',
        'peso_ponderacion'     => 'decimal:2',
        'activa'               => 'boolean',
    ];

    public const EQUIPARACION_NIVELES = [
        1 => [
            'grado'            => 1,
            'nivel_atencion'   => 'NIVEL 1',
            'mspbs'            => 'U.S.F. ESTÁNDAR',
            'ips'              => 'Puesto Sanitario',
            'modalidad'        => 'NO HOSPITALARIA',
            'complejidad'      => 'Baja complejidad',
            'color_hex'        => '#4338ca',
            'color_bg_mspbs'   => '#e0e7ff',
            'color_text_mspbs' => '#312e81',
            'color_bg_ips'     => '#3730a3',
        ],
        2 => [
            'grado'            => 2,
            'nivel_atencion'   => 'NIVEL 2',
            'mspbs'            => 'C.A.E.S.',
            'ips'              => 'Clínica Periférica',
            'modalidad'        => 'NO HOSPITALARIA',
            'complejidad'      => 'Mediana complejidad',
            'color_hex'        => '#0f766e',
            'color_bg_mspbs'   => '#ccfbf1',
            'color_text_mspbs' => '#115e59',
            'color_bg_ips'     => '#0f766e',
        ],
        3 => [
            'grado'            => 3,
            'nivel_atencion'   => 'NIVEL 2',
            'mspbs'            => 'HOSPITAL BÁSICO',
            'ips'              => 'Unidad Sanitaria',
            'modalidad'        => 'HOSPITALARIA',
            'complejidad'      => 'Mediana complejidad',
            'color_hex'        => '#15803d',
            'color_bg_mspbs'   => '#dcfce7',
            'color_text_mspbs' => '#14532d',
            'color_bg_ips'     => '#16a34a',
        ],
        4 => [
            'grado'            => 4,
            'nivel_atencion'   => 'NIVEL 3',
            'mspbs'            => 'HOSPITAL GENERAL REGIONAL',
            'ips'              => 'Hospital Regional',
            'modalidad'        => 'HOSPITALARIA',
            'complejidad'      => 'Mediana complejidad',
            'color_hex'        => '#b45309',
            'color_bg_mspbs'   => '#fef9c3',
            'color_text_mspbs' => '#854d0e',
            'color_bg_ips'     => '#d97706',
        ],
        5 => [
            'grado'            => 5,
            'nivel_atencion'   => 'NIVEL 3',
            'mspbs'            => 'HOSPITAL GENERAL INTERREGIONAL',
            'ips'              => 'Hospital Interregional',
            'modalidad'        => 'HOSPITALARIA',
            'complejidad'      => 'Alta complejidad',
            'color_hex'        => '#c2410c',
            'color_bg_mspbs'   => '#ffedd5',
            'color_text_mspbs' => '#9a3412',
            'color_bg_ips'     => '#ea580c',
        ],
        6 => [
            'grado'            => 6,
            'nivel_atencion'   => 'NIVEL 4',
            'mspbs'            => 'HOSPITAL ESPECIALIZADO',
            'ips'              => 'Hospital Especializado',
            'modalidad'        => 'HOSPITALARIA',
            'complejidad'      => 'Alta complejidad',
            'color_hex'        => '#b91c1c',
            'color_bg_mspbs'   => '#fee2e2',
            'color_text_mspbs' => '#7f1d1d',
            'color_bg_ips'     => '#dc2626',
        ],
    ];

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(FormularioSeccion::class, 'formulario_seccion_id');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(EvaluacionRespuesta::class, 'formulario_pregunta_id');
    }

    public function getDimensionConfigAttribute(): array
    {
        $dim = $this->dimension ?: ($this->seccion?->dimension ?: 'cartera_servicios');
        return FormularioSeccion::DIMENSIONES[$dim] ?? FormularioSeccion::DIMENSIONES['cartera_servicios'];
    }

    public function getEquiparacionAttribute(): ?array
    {
        $grado = $this->grado_complejidad_min ?: 1;
        return self::EQUIPARACION_NIVELES[$grado] ?? null;
    }

    /**
     * Determina si una respuesta indica cumplimiento.
     */
    public function evaluarRespuesta(string $respuesta): string
    {
        $r = strtolower(trim($respuesta));

        return match($this->tipo_respuesta) {
            'si_no' => match($r) {
                'si', 'sí', 'si contamos', 'si contamos.', 'cuenta.', 'cuenta', 'aplica' => 'cumple',
                'no', 'no contamos', 'no contamos.', 'no.', 'falta de actualizacion.', 'no tiene' => 'no_cumple',
                default => 'no_verificable',
            },
            'si_no_na' => match($r) {
                'si', 'sí', 'si contamos', 'cuenta', 'aplica' => 'cumple',
                'no', 'no contamos', 'no tiene'               => 'no_cumple',
                'no aplica', 'no aplica.', 'n/a'              => 'no_aplica',
                default                                       => 'no_verificable',
            },
            'texto'            => strlen($respuesta) > 2 ? 'cumple' : 'no_verificable',
            'numero'           => is_numeric($respuesta) && $respuesta > 0 ? 'cumple' : 'no_cumple',
            'lista', 'checklist' => !empty($respuesta) ? 'cumple' : 'no_cumple',
            default            => 'no_verificable',
        };
    }
}
