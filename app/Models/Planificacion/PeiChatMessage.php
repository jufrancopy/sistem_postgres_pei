<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;

class PeiChatMessage extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'pei_chat_messages';

    protected $fillable = [
        'pei_profile_id',
        'user_id',
        'recipient_id',
        'parent_id',
        'message',
        'attachments',
        'is_system',
        'reference_type',
        'reference_id',
        'reference_title',
        'reference_url',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_system' => 'boolean',
    ];

    public function peiProfile()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function parent()
    {
        return $this->belongsTo(PeiChatMessage::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(PeiChatMessage::class, 'parent_id');
    }
}
