<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;

class PeiChatRead extends Model
{
    protected $table = 'pei_chat_reads';

    protected $fillable = [
        'pei_profile_id',
        'user_id',
        'last_read_message_id',
    ];

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lastReadMessage()
    {
        return $this->belongsTo(PeiChatMessage::class, 'last_read_message_id');
    }
}
