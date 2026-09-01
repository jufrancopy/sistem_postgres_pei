<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Admin\Globales\Organigrama;

class RelevamientoPaso extends Model
{
    use HasUuids;

    protected $table = 'planificacion.relevamiento_pasos';

    protected $fillable = [
        'relevamiento_proceso_id',
        'orden',
        'nombre',
        'descripcion',
        'organigrama_id',
        'rol_responsable',
        'tiempo_atencion_min',
        'tiempo_espera_min',
        'tiempo_traslado_min',
        'herramienta_sistema',
        'es_cuello_botella',
        'criticidad',
        'causa_raiz',
        'observacion_campo',
        'propuesta_mejora',
    ];

    protected $casts = [
        'es_cuello_botella' => 'boolean',
        'orden' => 'integer',
        'tiempo_atencion_min' => 'integer',
        'tiempo_espera_min' => 'integer',
        'tiempo_traslado_min' => 'integer',
    ];

    public function proceso()
    {
        return $this->belongsTo(RelevamientoProceso::class, 'relevamiento_proceso_id');
    }

    public function organigrama()
    {
        return $this->belongsTo(Organigrama::class, 'organigrama_id');
    }

    public function getTiempoTotalAttribute()
    {
        return $this->tiempo_atencion_min + $this->tiempo_espera_min + $this->tiempo_traslado_min;
    }
}
