<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\RiissEspecialidad;
use App\Models\RiissMedicamento;
use Illuminate\Support\Facades\DB;

class ValidacionEspecialidadItem extends Model
{
    use HasFactory;

    protected $table = 'riiss_validacion_especialidad_items';

    protected $fillable = [
        'validacion_id',
        'especialidad_id',
        'estado',
        'justificacion_inactivacion',
        'es_agregada_en_terreno',
        'validado_at',
        'validado_por',
    ];

    protected $casts = [
        'es_agregada_en_terreno' => 'boolean',
        'validado_at'            => 'datetime',
    ];

    public function validacion(): BelongsTo
    {
        return $this->belongsTo(ValidacionEspecialidad::class, 'validacion_id');
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(RiissEspecialidad::class, 'especialidad_id');
    }

    /**
     * Retorna los medicamentos vinculados a esta especialidad en el establecimiento de la validación.
     */
    public function getMedicamentosAttribute()
    {
        $estId = $this->validacion?->establecimiento_id;
        if (!$estId) return collect();

        $medIds = DB::table('riiss_est_esp_medicamentos')
            ->where('establecimiento_id', $estId)
            ->where('especialidad_id', $this->especialidad_id)
            ->pluck('medicamento_id');

        return RiissMedicamento::whereIn('id', $medIds)->orderBy('nombre')->get();
    }
}
