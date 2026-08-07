<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PeiProfileEdit extends Model
{
    protected $table = 'pei_profile_edits';

    protected $fillable = ['pei_profile_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
