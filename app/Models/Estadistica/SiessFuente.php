<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;

class SiessFuente extends Model
{
    protected $table = 'estadistica.siess_fuentes';

    protected $fillable = ['nombre', 'tipo', 'sistema_origen', 'endpoint_url', 'descripcion', 'activa'];

    protected $casts = ['activa' => 'boolean'];
}
