<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiissMedicamento extends Model
{
    use HasFactory;

    protected $table = 'riiss_medicamentos';
    protected $fillable = [
        'codigo',
        'nombre',
        'es_vademecum',
        'uso_vademecum',
        'concentracion',
        'forma_farmaceutica',
        'via_administracion',
        'presentacion',
        'unidad_medida',
        'especialidades_vademecum',
        'es_cronico',
        'categoria_terapeutica',
        'es_psicotropico',
        'resolucion_respaldo',
    ];

    protected $casts = [
        'es_vademecum'    => 'boolean',
        'es_cronico'      => 'boolean',
        'es_psicotropico' => 'boolean',
    ];

    /**
     * Especialidades autorizadas a prescribir este medicamento en Vademécum Oficial IPS.
     */
    public function especialidadesVademecum()
    {
        return $this->belongsToMany(\App\Models\RiissEspecialidad::class, 'riiss_especialidad_vademecum', 'medicamento_id', 'especialidad_id');
    }
}
