<?php

namespace App\Admin\Planificacion\Tasks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeTask extends Model
{
    use HasFactory;

    protected $table = "planificacion.typetasks";

    protected $fillable = ['name', 'route'];
}
