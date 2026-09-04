<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\HasMasterCatalogFields;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EspecialidadMedica extends BioestadisticaModel
{
    use HasMasterCatalogFields;

    protected $table = 'bioestadistica.especialidades_medicas';

    protected $casts = [
        'activo' => 'boolean',
        'meta' => 'array',
    ];

    public function especialidadBase(): BelongsTo
    {
        return $this->belongsTo(self::class, 'especialidad_base_id');
    }
}
