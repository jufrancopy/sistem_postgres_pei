<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['period_id', 'department', 'title', 'description'];

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    public function items()
    {
        return $this->hasMany(ScheduleItem::class, 'schedule_id');
    }
}
