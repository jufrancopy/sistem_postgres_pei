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
        'activity_id', 'title', 'details', 'etiqueta', 'color',
        'fecha_inicio', 'fecha_vencimiento', 'assigned_to', 'status',
        'completed_at', 'completed_by', 'completion_note'
    ];

    protected $dates = ['completed_at', 'fecha_inicio', 'fecha_vencimiento'];

    protected $casts = [
        'fecha_inicio'      => 'datetime',
        'fecha_vencimiento' => 'datetime',
        'completed_at'      => 'datetime',
    ];

    public function getEstadoVencimientoAttribute(): ?string
    {
        if (!$this->fecha_vencimiento || $this->status === 2) return null;
        $dias = now()->startOfDay()->diffInDays($this->fecha_vencimiento->startOfDay(), false);
        if ($dias < 0)  return 'vencida';
        if ($dias <= 3) return 'pronto';
        return 'ok';
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function evidences()
    {
        return $this->hasMany(ActivityTaskEvidence::class, 'activity_task_id');
    }

    public function comments()
    {
        return $this->hasMany(ActivityTaskComment::class, 'activity_task_id')->with('user')->orderBy('created_at', 'asc');
    }
}
