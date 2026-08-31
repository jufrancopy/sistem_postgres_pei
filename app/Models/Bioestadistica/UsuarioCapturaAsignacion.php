<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\Auditable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioCapturaAsignacion extends Model
{
    use Auditable;

    protected $table = 'bioestadistica.usuario_captura_asignaciones';

    protected $guarded = ['id'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formulario(): BelongsTo
    {
        return $this->belongsTo(Formulario::class);
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    public function formularioLabel(): string
    {
        if ($this->formulario_id === null) {
            return 'Todos los formularios';
        }

        return trim(($this->formulario?->codigo ?? '').' — '.($this->formulario?->nombre ?? ''));
    }
}
