<?php

namespace App\Admin\Globales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityTaskComment extends Model
{
    protected $table = 'activity_task_comments';

    protected $fillable = ['activity_task_id', 'user_id', 'comentario'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(ActivityTask::class, 'activity_task_id');
    }
}
