<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use App\Models\Bioestadistica\EspecialidadMedica;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CatalogItemAdminService
{
    public function __construct(private CatalogClassifier $classifier = new CatalogClassifier)
    {
    }

    /**
     * @return Collection<int, object{
     *     bridge_id: int,
     *     id: int,
     *     catalogo_tipo: string,
     *     label: string,
     *     activo: bool,
     *     bridge_orden: int,
     *     bridge_activo: bool,
     *     attributes: array<string, mixed>
     * }>
     */
    public function rowsForDetalle(VariableDetalle $detalle): Collection
    {
        return DetalleCatalogoItem::query()
            ->where('variable_detalle_id', $detalle->id)
            ->orderBy('orden')
            ->orderBy('id')
            ->get()
            ->map(function (DetalleCatalogoItem $bridge) {
                $item = $bridge->resolveCatalogItem();

                return (object) [
                    'bridge_id' => (int) $bridge->id,
                    'id' => (int) $bridge->catalogo_item_id,
                    'catalogo_tipo' => $bridge->catalogo_tipo,
                    'label' => $item?->nombre ?? ('Ítem #'.$bridge->catalogo_item_id),
                    'activo' => (bool) ($item?->activo ?? true),
                    'bridge_orden' => (int) $bridge->orden,
                    'bridge_activo' => (bool) $bridge->activo,
                    'attributes' => $item ? $this->editableAttributes($item, CatalogType::from($bridge->catalogo_tipo)) : [],
                ];
            });
    }

    /**
     * @return array<string, mixed>
     */
    public function editableAttributes(Model $item, CatalogType $type): array
    {
        return match ($type) {
            CatalogType::EspecialidadMedica => [
                'especialidad_base' => $item->especialidad_base,
            ],
            CatalogType::Determinacion => [
                'familia' => $item->familia,
                'modalidad' => $item->modalidad,
                'es_agregado' => (bool) $item->es_agregado,
            ],
            CatalogType::Procedimiento => [
                'categoria' => $item->categoria,
                'requiere_pacientes' => (bool) $item->requiere_pacientes,
                'requiere_prestaciones' => (bool) $item->requiere_prestaciones,
            ],
            CatalogType::Vacuna => [
                'abreviatura' => $item->abreviatura,
                'requiere_lote' => (bool) $item->requiere_lote,
            ],
            CatalogType::Prestacion => [
                'familia' => $item->familia,
                'es_indicador' => (bool) $item->es_indicador,
            ],
        };
    }

    public function resolveCatalogType(VariableDetalle $detalle): CatalogType
    {
        if ($detalle->catalogo_tipo) {
            return CatalogType::from($detalle->catalogo_tipo);
        }

        $detalle->loadMissing('variable');

        return $this->classifier->classify(
            $detalle->variable->codigo,
            $detalle->nombre,
            $detalle->nombre
        ) ?? CatalogType::Prestacion;
    }

    public function linkExisting(VariableDetalle $detalle, int $catalogItemId, int $orden = 0): DetalleCatalogoItem
    {
        $type = $detalle->catalogo_tipo
            ? CatalogType::from($detalle->catalogo_tipo)
            : $this->resolveCatalogType($detalle);
        $item = $type->modelClass()::query()->find($catalogItemId);
        if (! $item) {
            throw ValidationException::withMessages(['catalogo_item_id' => 'El ítem seleccionado no existe en el catálogo.']);
        }

        if ($detalle->catalogo_tipo === null) {
            $detalle->update(['catalogo_tipo' => $type->value]);
        } elseif ($detalle->catalogo_tipo !== $type->value) {
            throw ValidationException::withMessages(['catalogo_item_id' => 'El ítem no pertenece al catálogo de este tipo de registro.']);
        }

        return DetalleCatalogoItem::query()->firstOrCreate(
            [
                'variable_detalle_id' => $detalle->id,
                'catalogo_tipo' => $type->value,
                'catalogo_item_id' => $catalogItemId,
            ],
            ['orden' => $orden, 'activo' => true]
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateItem(DetalleCatalogoItem $bridge, array $data): Model
    {
        $type = CatalogType::from($bridge->catalogo_tipo);
        $item = $type->modelClass()::query()->findOrFail($bridge->catalogo_item_id);

        $item->fill($this->itemPayload($type, $data));
        $item->save();

        $bridge->fill([
            'orden' => (int) ($data['bridge_orden'] ?? $bridge->orden),
            'activo' => array_key_exists('bridge_activo', $data)
                ? (bool) $data['bridge_activo']
                : $bridge->activo,
        ]);
        $bridge->save();

        return $item->fresh();
    }

    public function unlink(DetalleCatalogoItem $bridge): void
    {
        $bridge->delete();
    }

    public function deleteMasterItem(DetalleCatalogoItem $bridge): void
    {
        $type = CatalogType::from($bridge->catalogo_tipo);
        $item = $type->modelClass()::query()->find($bridge->catalogo_item_id);
        DetalleCatalogoItem::query()
            ->where('catalogo_tipo', $bridge->catalogo_tipo)
            ->where('catalogo_item_id', $bridge->catalogo_item_id)
            ->delete();
        $item?->delete();
    }

    /**
     * @return Collection<int, object{id:int,label:string}>
     */
    public function searchLinkable(VariableDetalle $detalle, ?string $term = null, int $limit = 30): Collection
    {
        $type = $detalle->catalogo_tipo
            ? CatalogType::from($detalle->catalogo_tipo)
            : $this->resolveCatalogType($detalle);
        $linkedIds = DetalleCatalogoItem::query()
            ->where('variable_detalle_id', $detalle->id)
            ->where('catalogo_tipo', $type->value)
            ->pluck('catalogo_item_id');

        $query = $type->modelClass()::query()
            ->where('activo', true)
            ->whereNotIn('id', $linkedIds)
            ->orderBy('nombre');

        if ($term !== null && trim($term) !== '') {
            $query->where('nombre', 'ilike', '%'.trim($term).'%');
        }

        return $query->limit($limit)->get(['id', 'nombre'])->map(fn ($row) => (object) [
            'id' => (int) $row->id,
            'label' => $row->nombre,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function itemPayload(CatalogType $type, array $data): array
    {
        $payload = [
            'nombre' => trim((string) ($data['nombre'] ?? '')),
            'activo' => (bool) ($data['activo'] ?? true),
            'nombre_normalizado' => EspecialidadMedica::normalizeNombre((string) ($data['nombre'] ?? '')),
        ];

        return match ($type) {
            CatalogType::EspecialidadMedica => $payload + [
                'especialidad_base' => $data['especialidad_base'] ?? null,
            ],
            CatalogType::Determinacion => $payload + [
                'familia' => (string) ($data['familia'] ?? 'laboratorio'),
                'modalidad' => $data['modalidad'] ?? null,
                'es_agregado' => (bool) ($data['es_agregado'] ?? false),
            ],
            CatalogType::Procedimiento => $payload + [
                'categoria' => (string) ($data['categoria'] ?? 'otro'),
                'requiere_pacientes' => (bool) ($data['requiere_pacientes'] ?? false),
                'requiere_prestaciones' => (bool) ($data['requiere_prestaciones'] ?? false),
            ],
            CatalogType::Vacuna => $payload + [
                'abreviatura' => $data['abreviatura'] ?? null,
                'requiere_lote' => (bool) ($data['requiere_lote'] ?? false),
            ],
            CatalogType::Prestacion => $payload + [
                'familia' => (string) ($data['familia'] ?? 'metrica'),
                'es_indicador' => (bool) ($data['es_indicador'] ?? false),
            ],
        };
    }
}
