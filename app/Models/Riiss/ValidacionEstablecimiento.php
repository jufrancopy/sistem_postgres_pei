<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidacionEstablecimiento extends Model
{
    use HasFactory;

    protected $table = 'riiss_validacion_establecimientos';

    protected $fillable = [
        'establecimiento_id',
        'sesion_validador_id',
        'validador_nombre',
        'validador_cargo',
        'validador_documento',
        'estado',
        'total_db',
        'total_activas',
        'total_inactivas',
        'total_agregadas',
        'notas',
        'firma_digital',
        'firmado_at',
    ];

    protected $casts = [
        'firmado_at' => 'datetime',
        'total_db' => 'integer',
        'total_activas' => 'integer',
        'total_inactivas' => 'integer',
        'total_agregadas' => 'integer',
    ];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id', 'id_establecimiento');
    }

    public function sesionValidador(): BelongsTo
    {
        return $this->belongsTo(SesionValidador::class, 'sesion_validador_id');
    }
}
