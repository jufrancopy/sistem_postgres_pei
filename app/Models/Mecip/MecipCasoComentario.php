<?php

namespace App\Models\Mecip;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MecipCasoComentario extends Model
{
    protected $table = 'mecip_caso_comentarios';

    protected $fillable = [
        'mecip_caso_id',
        'actividad_id',
        'user_id',
        'rol_usuario',
        'comentario',
        'justificacion_camino',
        'es_resolucion',
    ];

    protected $casts = [
        'es_resolucion' => 'boolean',
    ];

    public function caso()
    {
        return $this->belongsTo(MecipCaso::class, 'mecip_caso_id');
    }

    public function actividad()
    {
        return $this->belongsTo(MecipCasoActividad::class, 'actividad_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
