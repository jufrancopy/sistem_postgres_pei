<?php

namespace App\Admin\Planificacion\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Admin\Globales\Group;

class Task extends Model
{
    use HasFactory;

    protected $table = 'planificacion.tasks';
    protected $fillable = ['group_id', 'details', 'status'];

    public function analysts()
    {
        return $this->belongsToMany(User::class, 'planificacion.tasks_has_analysts', 'task_id', 'analyst_id');
    }

    public function analyst()
    {
        return $this->belongsTo(User::class);
    }

    public function typeTasks()
    {
        return $this->belongsToMany('App\Admin\Planificacion\Task\TypeTask', 'planificacion.tasks_has_type_tasks', 'task_id', 'typetaskable_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function taskable()
    {
        return $this->morphTo('taskable', 'taskable_id');
    }
}
