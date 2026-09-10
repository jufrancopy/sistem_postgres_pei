<?php

namespace App\Models\Riiss;

use App\Models\RiissEspecialidad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidacionEspecialidadRegistro extends Model
{
    use HasFactory;

    protected $table = 'riiss_validacion_especialidad_registros';

    protected $fillable = [
        'establecimiento_id',
        'especialidad_id',
        'estado',
        'justificacion',
        'es_agregada',
        'sesion_validador_id',
        'validado_por',
        'validado_at',
    ];

    protected $casts = [
        'es_agregada' => 'boolean',
        'validado_at' => 'datetime',
    ];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id', 'id_establecimiento');
    }

    /**
     * Relación directa con el catálogo canónico de bioestadistica.especialidades_medicas
     */
    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(RiissEspecialidad::class, 'especialidad_id', 'id');
    }

    public function sesionValidador(): BelongsTo
    {
        return $this->belongsTo(SesionValidador::class, 'sesion_validador_id');
    }
}
