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
