<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\DeterminacionEstudio;
use App\Models\Bioestadistica\EspecialidadMedica;
use App\Models\Bioestadistica\Prestacion;
use App\Models\Bioestadistica\Procedimiento;
use App\Models\Bioestadistica\Vacuna;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Eloquent\Model;

class MasterCatalogRegistry
{
    public function __construct(private CatalogClassifier $classifier = new CatalogClassifier)
    {
    }

    /**
     * @return array{
     *     variable: Variable,
     *     detalle: VariableDetalle,
     *     catalog_type: ?CatalogType,
     *     catalog_item: ?Model,
     *     bridge: ?DetalleCatalogoItem,
     *     skipped_column: bool,
     *     created: array<string, bool>
     * }
     */
    public function remember(string $codigoDominio, string $dominio, string $tipoRegistro, string $prestacionNombre, int $orden = 0): array
    {
        [$variable, $variableCreated] = $this->rememberVariable($codigoDominio, $dominio);
        [$detalle, $detalleCreated] = $this->rememberDetalle($variable, $tipoRegistro);

        if ($this->classifier->isFormColumn($codigoDominio, $tipoRegistro, $prestacionNombre)) {
            $this->applyLayoutMetadata($detalle, $codigoDominio, $tipoRegistro);

            return [
                'variable' => $variable,
                'detalle' => $detalle,
                'catalog_type' => null,
                'catalog_item' => null,
                'bridge' => null,
                'skipped_column' => true,
                'created' => [
                    'variable' => $variableCreated,
                    'detalle' => $detalleCreated,
                    'catalog_item' => false,
                    'bridge' => false,
                ],
            ];
        }

        $catalogType = $this->classifier->classify($codigoDominio, $tipoRegistro, $prestacionNombre);
        if ($detalle->catalogo_tipo === null && $catalogType !== null) {
            $detalle->catalogo_tipo = $catalogType->value;
            $detalle->save();
        }

        [$catalogItem, $itemCreated] = $this->rememberCatalogItem(
            $catalogType,
            $codigoDominio,
            $tipoRegistro,
            $prestacionNombre
        );

        $bridge = DetalleCatalogoItem::query()->firstOrCreate(
            [
                'variable_detalle_id' => $detalle->id,
                'catalogo_tipo' => $catalogType->value,
                'catalogo_item_id' => $catalogItem->id,
            ],
            [
                'orden' => $orden,
                'activo' => true,
            ]
        );

        return [
            'variable' => $variable,
            'detalle' => $detalle,
            'catalog_type' => $catalogType,
            'catalog_item' => $catalogItem,
            'bridge' => $bridge,
            'skipped_column' => false,
            'created' => [
                'variable' => $variableCreated,
                'detalle' => $detalleCreated,
                'catalog_item' => $itemCreated,
                'bridge' => $bridge->wasRecentlyCreated,
            ],
        ];
    }

    /** @return array{0: Variable, 1: bool} */
    private function rememberVariable(string $codigo, string $dominio): array
    {
        $variable = Variable::withTrashed()->firstOrNew([
            'codigo' => $codigo,
            'nombre' => $dominio,
        ]);
        $created = ! $variable->exists;
        if ($variable->exists && method_exists($variable, 'trashed') && $variable->trashed()) {
            $variable->restore();
        }
        $variable->fill(['activo' => true]);
        $variable->save();

        return [$variable, $created];
    }

    /** @return array{0: VariableDetalle, 1: bool} */
    private function rememberDetalle(Variable $variable, string $tipo): array
    {
        $detalle = VariableDetalle::withTrashed()->firstOrNew([
            'variable_id' => $variable->id,
            'nombre' => $tipo,
        ]);
        $created = ! $detalle->exists;
        if ($detalle->exists && method_exists($detalle, 'trashed') && $detalle->trashed()) {
            $detalle->restore();
        }
        $detalle->fill(['activo' => true]);
        $detalle->save();

        return [$detalle, $created];
    }

    private function applyLayoutMetadata(VariableDetalle $detalle, string $codigoDominio, string $tipoRegistro): void
    {
        $layout = $this->classifier->layoutForColumn($codigoDominio, $tipoRegistro);
        $columns = $this->classifier->columnDefinitions($codigoDominio, $tipoRegistro);
        $meta = $detalle->meta ?? [];
        $meta['form_columns'] = $columns;

        $detalle->fill([
            'layout_captura' => $layout,
            'meta' => $meta,
        ]);
        $detalle->save();
    }

    /**
     * @return array{0: Model, 1: bool}
     */
    private function rememberCatalogItem(
        CatalogType $type,
        string $codigoDominio,
        string $tipoRegistro,
        string $nombre
    ): array {
        $normalized = EspecialidadMedica::normalizeNombre($nombre);

        return match ($type) {
            CatalogType::EspecialidadMedica => $this->rememberEspecialidad($tipoRegistro, $nombre, $normalized),
            CatalogType::Determinacion => $this->rememberDeterminacion($codigoDominio, $tipoRegistro, $nombre, $normalized),
            CatalogType::Procedimiento => $this->rememberProcedimiento($tipoRegistro, $nombre, $normalized),
            CatalogType::Vacuna => $this->rememberVacuna($nombre, $normalized),
            CatalogType::Prestacion => $this->rememberPrestacionGeneral($codigoDominio, $tipoRegistro, $nombre, $normalized),
        };
    }

    /** @return array{0: EspecialidadMedica, 1: bool} */
    private function rememberEspecialidad(string $tipoRegistro, string $nombre, string $normalized): array
    {
        $contexto = $this->classifier->especialidadContexto($tipoRegistro, $nombre);
        if ($this->classifier->isOdontologiaConsultaName($nombre) && $contexto === 'ambulatorio') {
            $contexto = 'odontologia_consulta';
        }

        $item = EspecialidadMedica::withTrashed()->firstOrNew([
            'nombre' => $nombre,
            'contexto' => $contexto,
        ]);
        $created = ! $item->exists;
        if ($item->exists && $item->trashed()) {
            $item->restore();
        }
        $item->fill([
            'nombre_normalizado' => $normalized,
            'especialidad_base' => $this->classifier->especialidadBase($nombre, $contexto),
            'activo' => true,
        ]);
        $item->save();

        return [$item, $created];
    }

    /** @return array{0: DeterminacionEstudio, 1: bool} */
    private function rememberDeterminacion(string $codigo, string $tipoRegistro, string $nombre, string $normalized): array
    {
        $item = DeterminacionEstudio::withTrashed()->firstOrNew(['nombre' => $nombre]);
        $created = ! $item->exists;
        if ($item->exists && $item->trashed()) {
            $item->restore();
        }
        $item->fill([
            'nombre_normalizado' => $normalized,
            'familia' => $this->classifier->determinacionFamilia($codigo),
            'modalidad' => $tipoRegistro,
            'es_agregado' => str_contains(strtoupper($nombre), 'CANTIDAD DE PACIENTES'),
            'activo' => true,
        ]);
        $item->save();

        return [$item, $created];
    }

    /** @return array{0: Procedimiento, 1: bool} */
    private function rememberProcedimiento(string $tipoRegistro, string $nombre, string $normalized): array
    {
        $item = Procedimiento::withTrashed()->firstOrNew(['nombre' => $nombre]);
        $created = ! $item->exists;
        if ($item->exists && $item->trashed()) {
            $item->restore();
        }
        $item->fill([
            'nombre_normalizado' => $normalized,
            'categoria' => $this->classifier->procedimientoCategoria($tipoRegistro),
            'activo' => true,
        ]);
        $item->save();

        return [$item, $created];
    }

    /** @return array{0: Vacuna, 1: bool} */
    private function rememberVacuna(string $nombre, string $normalized): array
    {
        $item = Vacuna::withTrashed()->firstOrNew(['nombre' => $nombre]);
        $created = ! $item->exists;
        if ($item->exists && $item->trashed()) {
            $item->restore();
        }
        $item->fill([
            'nombre_normalizado' => $normalized,
            'abreviatura' => strlen($nombre) <= 20 ? $nombre : null,
            'activo' => true,
        ]);
        $item->save();

        return [$item, $created];
    }

    /** @return array{0: Prestacion, 1: bool} */
    private function rememberPrestacionGeneral(string $codigo, string $tipoRegistro, string $nombre, string $normalized): array
    {
        $item = Prestacion::withTrashed()->firstOrNew(['nombre' => $nombre]);
        $created = ! $item->exists;
        if ($item->exists && $item->trashed()) {
            $item->restore();
        }
        $item->fill([
            'nombre_normalizado' => $normalized,
            'familia' => $this->classifier->prestacionFamilia($codigo, $tipoRegistro),
            'dominio_codigo' => $codigo,
            'es_indicador' => $this->classifier->isIndicador($codigo, $nombre),
            'activo' => true,
        ]);
        $item->save();

        return [$item, $created];
    }
}
