<?php

namespace App\Models\Riiss;

use App\Models\RiissEspecialidad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiissValidacionFarmaceuticaEspecialidad extends Model
{
    use HasFactory;

    protected $table = 'riiss_validacion_farmaceutica_especialidades';

    protected $fillable = [
        'sesion_farmaceutica_id',
        'especialidad_id',
        'estado',
        'observaciones_tecnicas',
        'firma_digital',
        'firmado_por',
        'firmado_documento',
        'firmado_matricula',
        'firmado_at',
    ];

    protected $casts = [
        'firmado_at' => 'datetime',
    ];

    public function sesion(): BelongsTo
    {
        return $this->belongsTo(RiissSesionFarmaceutica::class, 'sesion_farmaceutica_id');
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(RiissEspecialidad::class, 'especialidad_id');
    }

    public function dictamenes(): HasMany
    {
        return $this->hasMany(RiissValidacionFarmaceuticaMedicamento::class, 'validacion_farmaceutica_especialidad_id');
    }
}
