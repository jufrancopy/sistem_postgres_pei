<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormularioPregunta extends Model
{
    protected $table = 'formulario_preguntas';

    protected $fillable = [
        'formulario_seccion_id', 'pregunta', 'tipo_respuesta', 'opciones',
        'respuesta_ejemplo', 'orden', 'activa', 'tags_cartera',
        'servicio_cartera_grupo', 'especialidad_relacionada',
    ];

    protected $casts = [
        'tipo_respuesta' => 'string',
        'opciones'       => 'array',
        'tags_cartera'   => 'array',
        'orden'          => 'integer',
        'activa'         => 'boolean',
    ];

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(FormularioSeccion::class, 'formulario_seccion_id');
    }

    /**
     * Determina si una respuesta indica cumplimiento.
     */
    public function evaluarRespuesta(string $respuesta): string
    {
        $r = strtolower(trim($respuesta));

        return match($this->tipo_respuesta) {
            'si_no' => match($r) {
                'si', 'sí', 'si contamos', 'si contamos.', 'cuenta.', 'cuenta' => 'cumple',
                'no', 'no contamos', 'no contamos.', 'no.', 'falta de actualizacion.' => 'no_cumple',
                default => 'no_verificable',
            },
            'si_no_na' => match($r) {
                'si', 'sí', 'si contamos', 'cuenta' => 'cumple',
                'no', 'no contamos'                  => 'no_cumple',
                'no aplica', 'no aplica.'             => 'no_aplica',
                default                              => 'no_verificable',
            },
            'texto'            => strlen($respuesta) > 3 ? 'cumple' : 'no_verificable',
            'numero'           => is_numeric($respuesta) && $respuesta > 0 ? 'cumple' : 'no_cumple',
            'lista', 'checklist' => !empty($respuesta) ? 'cumple' : 'no_cumple',
            default            => 'no_verificable',
        };
    }
}
