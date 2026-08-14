<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariableDefinition extends BioestadisticaModel
{
    protected $table = 'bioestadistica.variable_definitions';

    protected $casts = [
        'activo' => 'boolean',
        'form_codes' => 'array',
        'meta' => 'array',
    ];

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class);
    }
}
