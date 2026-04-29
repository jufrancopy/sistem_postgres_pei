<?php

namespace App\Admin\Globales;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityTaskEvidence extends Model
{
    protected $table = 'activity_task_evidences';

    protected $fillable = ['activity_task_id', 'type', 'label', 'value', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
