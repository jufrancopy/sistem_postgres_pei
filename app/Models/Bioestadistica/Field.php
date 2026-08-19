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

    public function detalle(): BelongsTo
    {
        return $this->belongsTo(VariableDetalle::class, 'detalle_id');
    }

    /**
     * Filas de una tabla o lista de captura: prestaciones del detalle del diccionario.
     *
     * @return \Illuminate\Support\Collection<int, object>
     */
    public function rowItems()
    {
        $this->loadMissing('detalle.prestaciones');

        return ($this->detalle?->prestaciones ?? collect())
            ->where('activo', true)
            ->values()
            ->map(fn (Prestacion $prestacion) => (object) [
                'id' => $prestacion->id,
                'label' => $prestacion->nombre,
                'activo' => $prestacion->activo,
            ]);
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
