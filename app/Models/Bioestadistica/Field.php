<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends BioestadisticaModel
{
    protected $table = 'bioestadistica.fields';

    protected $casts = [
        'required' => 'boolean',
        'min_value' => 'decimal:4',
        'max_value' => 'decimal:4',
        'config' => 'array',
    ];

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(FormSeccion::class, 'seccion_id');
    }

    public function catalogo(): BelongsTo
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function variableDefinition(): BelongsTo
    {
        return $this->belongsTo(VariableDefinition::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_field_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_field_id')->orderBy('orden');
    }
}
