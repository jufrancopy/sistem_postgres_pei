<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;

class GamificationPoint extends Model
{
    protected $table = 'gamification_points';

    protected $fillable = [
        'user_id',
        'pei_profile_id',
        'points',
        'action_type',
        'description',
        'reference_type',
        'reference_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
