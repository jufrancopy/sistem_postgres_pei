<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleItem extends Model
{
    protected $fillable = [
        'schedule_id', 'activity_id', 'activity_task_id', 'responsible_id',
        'title', 'description', 'start_date', 'end_date', 'status', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function activityTask()
    {
        return $this->belongsTo(\App\Admin\Globales\ActivityTask::class, 'activity_task_id');
    }

    public function responsible()
    {
        return $this->belongsTo(\App\Models\User::class, 'responsible_id');
    }
}
