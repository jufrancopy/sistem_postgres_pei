<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PeiProfileEdit extends Model
{
    protected $table = 'pei_profile_edits';

    protected $fillable = ['pei_profile_id', 'user_id', 'old_values', 'new_values'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peiNode()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }
}
