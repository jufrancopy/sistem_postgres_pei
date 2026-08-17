<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\Auditable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportJob extends Model
{
    use Auditable;

    public const ESTADO_SUBIDO = 'subido';
    public const ESTADO_ANALIZADO = 'analizado';
    public const ESTADO_MAPEADO = 'mapeado';
    public const ESTADO_CONFIRMADO = 'confirmado';
    public const ESTADO_COMPLETADO = 'completado';
    public const ESTADO_ERROR = 'error';

    protected $table = 'bioestadistica.import_jobs';

    protected $guarded = ['id'];

    protected array $auditSensitive = ['archivo_path', 'checksum', 'error'];

    protected $casts = [
        'analisis' => 'array',
        'mapeo' => 'array',
        'resumen' => 'array',
        'confirmed_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
