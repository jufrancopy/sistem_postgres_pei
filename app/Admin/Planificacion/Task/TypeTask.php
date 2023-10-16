<?php

namespace App\Admin\Planificacion\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeTask extends Model
{
    use HasFactory;

    protected $table = "planificacion.typetasks";

    protected $fillable = ['task_id', 'name'];
}
