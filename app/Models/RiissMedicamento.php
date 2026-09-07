<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiissMedicamento extends Model
{
    use HasFactory;

    protected $table = 'riiss_medicamentos';
    protected $fillable = ['codigo', 'nombre'];
}
