<?php

namespace App\Models\Mecip;

use Illuminate\Database\Eloquent\Model;

class MecipCasoTarea extends Model
{
    protected $table = 'mecip_caso_tareas';

    protected $fillable = [
        'actividad_id',
        'descripcion',
        'tiempo_estimado_minutos',
        'orden',
    ];

    public function actividad()
    {
        return $this->belongsTo(MecipCasoActividad::class, 'actividad_id');
    }
}
