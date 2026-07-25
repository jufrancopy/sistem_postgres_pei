<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'type', 'description', 'date_start', 'date_end', 'pei_profile_id'];

    public function responsibles()
    {
        return $this->belongsToMany(User::class, 'activities_has_responsibles', 'activity_id', 'responsible_id');
    }

    public function tasks()
    {
        return $this->hasMany(ActivityTask::class, 'activity_id');
    }

    public function peiProfile()
    {
        return $this->belongsTo(\App\Admin\Planificacion\Pei\PeiProfile::class, 'pei_profile_id');
    }
}
