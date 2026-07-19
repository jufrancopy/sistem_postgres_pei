<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitucionParaguay extends Model
{
    protected $table = 'instituciones_paraguay';

    protected $fillable = ['nombre', 'sigla', 'tipo', 'activo'];
}
