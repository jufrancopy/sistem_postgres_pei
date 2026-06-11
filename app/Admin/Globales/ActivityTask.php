<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ActivityTask extends Model
{
    use SoftDeletes;

    protected $table = 'activity_tasks';

    protected $fillable = [
        'activity_id', 'title', 'details', 'etiqueta', 'color', 'assigned_to', 'status',
        'completed_at', 'completed_by', 'completion_note'
    ];

    protected $dates = ['completed_at'];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function evidences()
    {
        return $this->hasMany(ActivityTaskEvidence::class, 'activity_task_id');
    }
}
