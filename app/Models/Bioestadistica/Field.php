<?php

namespace App\Models\Bioestadistica;

use App\Application\Bioestadistica\Dictionary\CatalogItemResolver;
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
     * Filas de captura: ítems del catálogo maestro vía puente.
     *
     * @return \Illuminate\Support\Collection<int, object>
     */
    public function rowItems()
    {
        $this->loadMissing('detalle');

        return (new CatalogItemResolver)->rowsForDetalle($this->detalle);
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
