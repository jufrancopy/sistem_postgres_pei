<?php

namespace App\Admin\Planificacion\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Admin\Globales\Group;
use App\Admin\Planificacion\Task\TasksHasTypeTasks;


class Task extends Model
{
    use HasFactory;

    protected $table = 'planificacion.tasks';
    protected $fillable = ['group_id', 'details', 'status'];

    public function analysts()
    {
        return $this->belongsToMany(User::class, 'planificacion.tasks_has_analysts', 'task_id', 'analyst_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'planificacion.tasks_has_participants', 'task_id', 'participant_id');
    }

    public function analyst()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function typeTasks()
    {
        return $this->hasMany(TasksHasTypeTasks::class, 'task_id');
    }
}
