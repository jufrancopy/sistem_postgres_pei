<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class SiessValidacion extends Model
{
    protected $table = 'estadistica.siess_validaciones';

    protected $fillable = ['extracto_id', 'accion', 'usuario_id', 'comentario', 'metadata', 'fecha'];

    protected $casts = [
        'metadata' => 'array',
        'fecha'    => 'datetime',
    ];

    public function extracto(): BelongsTo
    {
        return $this->belongsTo(SiessExtracto::class, 'extracto_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function accionLabel(): string
    {
        return match($this->accion) {
            'envio'        => 'Enviado a validación',
            'aprobacion'   => 'Aprobado',
            'objecion'     => 'Objetado',
            'silencio'     => 'Aprobado por silencio administrativo',
            'reenvio'      => 'Reenviado a validación',
            'fuente_unica' => 'Marcado como fuente única',
            default        => ucfirst($this->accion),
        };
    }
}
