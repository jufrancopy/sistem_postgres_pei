<?php

namespace App\Models\Bioestadistica;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class AuditLog extends Model
{
    public const ACTIONS = [
        'create', 'update', 'delete', 'submit', 'approve', 'reject',
        'publish', 'view', 'export', 'import',
    ];

    public $timestamps = false;

    protected $table = 'bioestadistica.audit_log';

    protected $guarded = ['id'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::updating(function () {
            throw new LogicException('bioestadistica.audit_log is append-only.');
        });
        static::deleting(function () {
            throw new LogicException('bioestadistica.audit_log is append-only.');
        });
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function entityLabel(): string
    {
        $class = class_basename($this->entity_type);

        return $class.($this->entity_id ? ' #'.$this->entity_id : '');
    }
}
