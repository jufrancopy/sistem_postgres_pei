<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiissEspecialidad extends Model
{
    use HasFactory;

    protected $table = 'riiss_especialidades';
    protected $fillable = ['nombre'];

    public function establecimientos()
    {
        return $this->belongsToMany(\App\Models\Riiss\Establecimiento::class, 'riiss_establecimiento_especialidades', 'especialidad_id', 'establecimiento_id');
    }
}
