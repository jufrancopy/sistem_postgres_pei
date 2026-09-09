<?php

namespace App\Application\Bioestadistica\Imports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Lectura fila a fila de la hoja «VARIABLES SALUD» con reglas de exclusión del Excel.
 */
class VariablesSaludSheetReader
{
    /** Colores de fila amarilla = columnas de formulario SP (no catálogo). */
    private const YELLOW_FILLS = [
        'FFFF00',
        'FFC000',
        'FFEB9C',
        'FFF2CC',
        'FFD966',
        'FFFF99',
    ];

    /**
     * @return \Generator<int, array{
     *     row: int,
     *     codigo: string,
     *     dominio: string,
     *     tipo: string,
     *     prestacion: string
     * }>
     */
    public function rows(Worksheet $sheet): \Generator
    {
        $current = ['codigo' => null, 'dominio' => null, 'tipo' => null];
        $lastRow = $sheet->getHighestDataRow();

        for ($row = 1; $row <= $lastRow; $row++) {
            $values = [];
            for ($column = 1; $column <= 4; $column++) {
                $values[$column] = $this->clean(
                    $sheet->getCell([$column, $row])->getFormattedValue()
                );
            }

            if ($this->isHeader($values)) {
                continue;
            }

            if ($values[1] && ! $this->isValidCodigoCell($values[1])) {
                continue;
            }

            if ($values[1] && $this->isExcludedDomainCodigo($values[1])) {
                continue;
            }

            $current['codigo'] = $values[1] ?: $current['codigo'];
            $current['dominio'] = $values[2] ?: $current['dominio'];
            $current['tipo'] = $values[3] ?: $current['tipo'];
            $prestacion = $values[4] ?: ($values[3] ?: null);

            if ($this->isExcludedDomainCodigo($current['codigo'] ?? null)) {
                continue;
            }

            if (! $current['codigo'] || ! $current['dominio'] || ! $current['tipo'] || ! $prestacion) {
                continue;
            }

            if ($this->isYellowRow($sheet, $row)) {
                continue;
            }

            yield $row => [
                'row' => $row,
                'codigo' => (string) $current['codigo'],
                'dominio' => (string) $current['dominio'],
                'tipo' => (string) $current['tipo'],
                'prestacion' => (string) $prestacion,
            ];
        }
    }

    public function isYellowRow(Worksheet $sheet, int $row): bool
    {
        for ($column = 1; $column <= 4; $column++) {
            $fill = $sheet->getCell([$column, $row])->getStyle()->getFill();
            if ($fill->getFillType() !== Fill::FILL_SOLID) {
                continue;
            }

            $color = strtoupper(substr($fill->getStartColor()->getARGB(), -6));
            if (in_array($color, self::YELLOW_FILLS, true)) {
                return true;
            }
        }

        return false;
    }

    public function isExcludedDomainCodigo(?string $codigo): bool
    {
        return $codigo !== null && strcasecmp(trim($codigo), 'x') === 0;
    }

    private function isValidCodigoCell(string $codigo): bool
    {
        return (bool) preg_match('/^(?:[1-9]|1[0-8])$/i', trim($codigo));
    }

    /**
     * @param  array<int, string|null>  $values
     */
    private function isHeader(array $values): bool
    {
        $row = strtoupper(implode(' ', array_filter($values)));

        return str_contains($row, 'CODIGO DE VARIABLE')
            || str_contains($row, 'CODIGO VARIABLE')
            || str_contains($row, 'TIPO DE REGISTRO')
            || str_contains($row, 'PRESTACIONES');
    }

    private function clean(mixed $value): ?string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }
}
