<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;

class PeiAsesoriaComentario extends Model
{
    protected $table = 'planificacion.pei_asesoria_comentarios';

    protected $fillable = [
        'pei_asesoria_id',
        'node_id',
        'node_type',
        'comentario',
    ];

    public function asesoria()
    {
        return $this->belongsTo(PeiAsesoria::class, 'pei_asesoria_id', 'id');
    }
}
