<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Admin\Globales\Group;
use App\Models\User;

class GroupReward extends Model
{
    use SoftDeletes;

    protected $table = 'group_rewards';

    protected $fillable = [
        'group_id',
        'created_by',
        'points',
        'title',
        'description',
        'is_retroactive',
    ];

    protected $casts = [
        'points' => 'integer',
        'is_retroactive' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
