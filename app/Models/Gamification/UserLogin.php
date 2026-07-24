<?php

namespace App\Models\Gamification;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserLogin extends Model
{
    protected $table = 'user_logins';

    protected $fillable = [
        'user_id',
        'login_date',
        'ip_address',
    ];

    protected $casts = [
        'login_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
