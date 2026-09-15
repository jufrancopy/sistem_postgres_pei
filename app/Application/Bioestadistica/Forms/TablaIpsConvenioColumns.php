<?php

namespace App\Application\Bioestadistica\Forms;

/**
 * Columnas IPS / Convenio / Total (desglose opcional + total de fila), patrón SP9.
 */
final class TablaIpsConvenioColumns
{
    /**
     * @return array<string, mixed>
     */
    public static function config(int $detalleId, string $rowLabel = 'Prestación'): array
    {
        return [
            'row_source' => 'detalle_catalogo',
            'row_detalle_id' => $detalleId,
            'row_label' => $rowLabel,
            'totals' => true,
            'columns' => [
                ['code' => 'ips', 'label' => 'IPS', 'type' => 'integer', 'min' => 0],
                ['code' => 'convenio', 'label' => 'Convenio', 'type' => 'integer', 'min' => 0],
                ['code' => 'total', 'label' => 'Total', 'type' => 'integer', 'min' => 0],
            ],
            'row_total' => [
                'code' => 'total',
                'sum_columns' => ['ips', 'convenio'],
            ],
        ];
    }

    public static function helpText(): string
    {
        return 'Por fila puede cargar solo Total, solo IPS, solo Convenio, o IPS y Convenio (el total se calcula solo). Las filas sin actividad pueden quedar vacías.';
    }
}
