<?php

namespace App\Application\Bioestadistica\Dictionary;

class HealthVariableDictionary
{
    public function __construct(private MasterCatalogRegistry $registry = new MasterCatalogRegistry)
    {
    }

    /**
     * @return array{
     *     variable: \App\Models\Bioestadistica\Variable,
     *     detalle: \App\Models\Bioestadistica\VariableDetalle,
     *     catalog_type: ?CatalogType,
     *     catalog_item: ?\Illuminate\Database\Eloquent\Model,
     *     bridge: ?\App\Models\Bioestadistica\DetalleCatalogoItem,
     *     skipped_column: bool,
     *     created: array<string, bool>
     * }
     */
    public function remember(string $codigo, string $dominio, string $tipo, string $prestacionNombre, int $orden = 0): array
    {
        return $this->registry->remember($codigo, $dominio, $tipo, $prestacionNombre, $orden);
    }
}
