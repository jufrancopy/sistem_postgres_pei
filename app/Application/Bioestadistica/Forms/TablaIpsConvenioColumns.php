<?php

namespace App\Application\Bioestadistica\Forms;

/**
 * Esquema completo de series por prestador (IPS / Convenio / Tercerizado / Total).
 * La UI y el guardado filtran columnas según establecimientos.prestador.
 */
final class TablaIpsConvenioColumns
{
    /**
     * @return array<string, mixed>
     */
    public static function config(int $detalleId, string $rowLabel = 'Prestación', string $totalCode = 'total'): array
    {
        return [
            'row_source' => 'detalle_catalogo',
            'row_detalle_id' => $detalleId,
            'row_label' => $rowLabel,
            'totals' => true,
            'metric_by_prestador' => true,
            'columns' => [
                ['code' => 'ips', 'label' => 'IPS', 'type' => 'integer', 'min' => 0],
                ['code' => 'convenio', 'label' => 'Convenio', 'type' => 'integer', 'min' => 0],
                ['code' => 'tercerizado', 'label' => 'Tercerizado', 'type' => 'integer', 'min' => 0],
                ['code' => $totalCode, 'label' => 'Total', 'type' => 'integer', 'min' => 0],
            ],
            'row_total' => [
                'code' => $totalCode,
                'sum_columns' => ['ips', 'convenio', 'tercerizado'],
            ],
        ];
    }

    public static function helpText(): string
    {
        return 'Las columnas visibles dependen del prestador (IPS: Total; IPS con producción tercerizada: Tercerizado; Convenio: IPS+Convenio; Tercerizado: Tercerizado). El Total se calcula desde las series visibles.';
    }
}
