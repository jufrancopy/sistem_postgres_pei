<?php

namespace App\Admin\Planificacion\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksHasTypeTasks extends Model
{
    use HasFactory;

    protected $table = 'planificacion.tasks_has_type_tasks';

    protected $fillable = ['typetaskable_id', 'typetaskable_type', 'status']; // Agrega typetaskable_type aquí

    public function typetaskable()
    {
        return $this->morphTo('typetaskable', 'typetaskable_type', 'typetaskable_id');
    }
}
