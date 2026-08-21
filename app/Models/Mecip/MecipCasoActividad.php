<?php

namespace App\Models\Mecip;

use Illuminate\Database\Eloquent\Model;

class MecipCasoActividad extends Model
{
    protected $table = 'mecip_caso_actividades';

    protected $fillable = [
        'mecip_caso_id',
        'codigo_actividad',
        'nombre',
        'objetivo',
        'responsable',
        'orden',
        'estado_revision',
    ];

    public function caso()
    {
        return $this->belongsTo(MecipCaso::class, 'mecip_caso_id');
    }

    public function tareas()
    {
        return $this->hasMany(MecipCasoTarea::class, 'actividad_id')->orderBy('orden');
    }

    public function comentarios()
    {
        return $this->hasMany(MecipCasoComentario::class, 'actividad_id')->latest();
    }

    public function getTiempoTotalMinutosAttribute(): int
    {
        return (int) $this->tareas()->sum('tiempo_estimado_minutos');
    }
}
