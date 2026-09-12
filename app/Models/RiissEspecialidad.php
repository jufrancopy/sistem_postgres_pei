<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiissEspecialidad extends Model
{
    use HasFactory;

    protected $table = 'bioestadistica.especialidades_medicas';
    protected $fillable = ['nombre', 'codigo', 'activo', 'especialidad_base_id', 'meta'];

    public function establecimientos()
    {
        return $this->belongsToMany(\App\Models\Riiss\Establecimiento::class, 'riiss_establecimiento_especialidades', 'especialidad_id', 'establecimiento_id');
    }

    /**
     * Medicamentos autorizados según el Vademécum Oficial IPS (2026).
     */
    public function medicamentosVademecum()
    {
        return $this->belongsToMany(\App\Models\RiissMedicamento::class, 'riiss_especialidad_vademecum', 'especialidad_id', 'medicamento_id');
    }

    /**
     * Medicamentos registrados históricamente por dispensación en establecimientos.
     */
    public function medicamentosHistoricos()
    {
        return $this->belongsToMany(\App\Models\RiissMedicamento::class, 'riiss_est_esp_medicamentos', 'especialidad_id', 'medicamento_id')->distinct();
    }
}
