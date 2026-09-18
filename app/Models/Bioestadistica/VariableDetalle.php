<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariableDetalle extends BioestadisticaModel
{
    protected $table = 'bioestadistica.variable_detalles';

    protected $casts = [
        'activo' => 'boolean',
        'meta' => 'array',
    ];

    public const ORDEN_ITEMS_MANUAL = 'manual';

    public const ORDEN_ITEMS_ALFABETICO = 'alfabetico';

    public function variable(): BelongsTo
    {
        return $this->belongsTo(Variable::class);
    }

    public function catalogoItems(): HasMany
    {
        return $this->hasMany(DetalleCatalogoItem::class, 'variable_detalle_id')->orderBy('orden');
    }

    /**
     * Cómo ordenar prestaciones/ítems del catálogo en captura y diccionario.
     */
    public function ordenItemsMode(): string
    {
        $mode = is_array($this->meta) ? ($this->meta['orden_items'] ?? self::ORDEN_ITEMS_MANUAL) : self::ORDEN_ITEMS_MANUAL;

        return $mode === self::ORDEN_ITEMS_ALFABETICO
            ? self::ORDEN_ITEMS_ALFABETICO
            : self::ORDEN_ITEMS_MANUAL;
    }

    public function setOrdenItemsMode(string $mode): void
    {
        $meta = is_array($this->meta) ? $this->meta : [];
        $meta['orden_items'] = $mode === self::ORDEN_ITEMS_ALFABETICO
            ? self::ORDEN_ITEMS_ALFABETICO
            : self::ORDEN_ITEMS_MANUAL;
        $this->meta = $meta;
    }

    public function ordenaItemsAlfabeticamente(): bool
    {
        return $this->ordenItemsMode() === self::ORDEN_ITEMS_ALFABETICO;
    }
}
