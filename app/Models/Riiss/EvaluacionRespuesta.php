<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionRespuesta extends Model
{
    protected $table = 'evaluacion_respuestas';

    protected $fillable = [
        'evaluacion_id', 'formulario_pregunta_id', 'respuesta',
        'estado_cumplimiento', 'observacion', 'evidencia_adjuntos',
    ];

    protected $casts = [
        'evidencia_adjuntos' => 'array',
    ];

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class);
    }

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(FormularioPregunta::class, 'formulario_pregunta_id');
    }
}
