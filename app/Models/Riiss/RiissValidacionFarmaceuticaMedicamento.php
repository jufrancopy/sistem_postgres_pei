<?php

namespace App\Models\Riiss;

use App\Models\RiissEspecialidad;
use App\Models\RiissMedicamento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiissValidacionFarmaceuticaMedicamento extends Model
{
    use HasFactory;

    protected $table = 'riiss_validacion_farmaceutica_medicamentos';

    protected $fillable = [
        'validacion_farmaceutica_especialidad_id',
        'sesion_farmaceutica_id',
        'especialidad_id',
        'medicamento_id',
        'estado_validacion', // 'validado' (aprobado), 'invalidado' (no pertinente), 'incorporado' (agregado)
        'justificacion',
        'validado_por',
        'validado_at',
    ];

    protected $casts = [
        'validado_at' => 'datetime',
    ];

    public function sesion(): BelongsTo
    {
        return $this->belongsTo(RiissSesionFarmaceutica::class, 'sesion_farmaceutica_id');
    }

    public function validacionEspecialidad(): BelongsTo
    {
        return $this->belongsTo(RiissValidacionFarmaceuticaEspecialidad::class, 'validacion_farmaceutica_especialidad_id');
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(RiissEspecialidad::class, 'especialidad_id');
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(RiissMedicamento::class, 'medicamento_id');
    }
}
