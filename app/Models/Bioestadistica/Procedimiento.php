<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\HasMasterCatalogFields;

class Procedimiento extends BioestadisticaModel
{
    use HasMasterCatalogFields;

    protected $table = 'bioestadistica.procedimientos';

    protected $casts = [
        'activo' => 'boolean',
        'requiere_pacientes' => 'boolean',
        'requiere_prestaciones' => 'boolean',
        'meta' => 'array',
    ];
}
