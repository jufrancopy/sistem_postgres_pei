<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiissMedicamento extends Model
{
    use HasFactory;

    protected $table = 'riiss_medicamentos';
    protected $fillable = [
        'codigo',
        'nombre',
        'es_cronico',
        'categoria_terapeutica',
        'es_psicotropico',
        'resolucion_respaldo',
    ];

    protected $casts = [
        'es_cronico'      => 'boolean',
        'es_psicotropico' => 'boolean',
    ];
}
