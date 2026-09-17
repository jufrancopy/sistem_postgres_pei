<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;

/**
 * Alinea dominio 16 con planilla interior (vacunas + clasificación agregada).
 * - Ítems de CLASIFICACION BENEFICIARIOS como filas (prestación), no columnas.
 * - Alias de vacunas faltantes en la planilla (PREVENAR13, OTRAS).
 */
class Sp8VacunacionInteriorDictionarySync
{
    public const DOMINIO_CODIGO = '16';

    public const DOMINIO_NOMBRE = 'VACUNACION';

    public const TIPO_VACUNAS = 'CLASIFICACION DE VACUNACION';

    public const TIPO_BENEFICIARIOS = 'CLASIFICACION BENEFICIARIOS DE VACUNACION';

    /**
     * @var list<string>
     */
    public const CLASIFICACION_ITEMS = [
        'N° DE BENEFICIARIOS',
        'FEMENINO',
        'MASCULINO',
        'MENORES DE 1 AÑO',
        '1 A 3 AÑOS',
        '4 A 14 AÑOS',
        '15 A 59 AÑOS',
        '60 Y MAS',
    ];

    /**
     * @var list<string>
     */
    public const VACUNA_ALIASES = [
        'PREVENAR13',
        'OTRAS',
    ];

    public function __construct(private HealthVariableDictionary $dictionary = new HealthVariableDictionary)
    {
    }

    /**
     * @return array{bridges: int, created_bridges: int, created_detalles: int, layout_updated: bool}
     */
    public function apply(): array
    {
        $bridges = 0;
        $createdBridges = 0;
        $createdDetalles = 0;
        $orden = 100;

        foreach (self::CLASIFICACION_ITEMS as $item) {
            $result = $this->dictionary->remember(
                self::DOMINIO_CODIGO,
                self::DOMINIO_NOMBRE,
                self::TIPO_BENEFICIARIOS,
                $item,
                $orden++
            );
            $bridges++;
            if ($result['created']['bridge'] ?? false) {
                $createdBridges++;
            }
            if ($result['created']['detalle'] ?? false) {
                $createdDetalles++;
            }
        }

        $orden = 9000;
        foreach (self::VACUNA_ALIASES as $item) {
            $result = $this->dictionary->remember(
                self::DOMINIO_CODIGO,
                self::DOMINIO_NOMBRE,
                self::TIPO_VACUNAS,
                $item,
                $orden++
            );
            $bridges++;
            if ($result['created']['bridge'] ?? false) {
                $createdBridges++;
            }
            if ($result['created']['detalle'] ?? false) {
                $createdDetalles++;
            }
        }

        $layoutUpdated = $this->normalizeBeneficiariosDetalle();

        return [
            'bridges' => $bridges,
            'created_bridges' => $createdBridges,
            'created_detalles' => $createdDetalles,
            'layout_updated' => $layoutUpdated,
        ];
    }

    private function normalizeBeneficiariosDetalle(): bool
    {
        $variable = Variable::query()->where('codigo', self::DOMINIO_CODIGO)->first();
        if (! $variable) {
            return false;
        }

        $detalle = VariableDetalle::query()
            ->where('variable_id', $variable->id)
            ->where('nombre', self::TIPO_BENEFICIARIOS)
            ->first();
        if (! $detalle) {
            return false;
        }

        $meta = $detalle->meta ?? [];
        unset($meta['form_columns']);

        $detalle->fill([
            'layout_captura' => 'tabla',
            'catalogo_tipo' => CatalogType::Prestacion->value,
            'meta' => $meta === [] ? null : $meta,
            'activo' => true,
        ])->save();

        return true;
    }
}
