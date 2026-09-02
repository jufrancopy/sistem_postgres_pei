<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\HealthVariableDictionary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BioestadisticaVariablesSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('.docs-bio/variables salud.xlsx');
        if (! is_file($path)) {
            $path = base_path('.docs-bio/variables salud.xls');
        }
        if (! is_file($path)) {
            $this->command?->warn('No se encontró variables salud.xlsx ni .xls; se omite el diccionario.');

            return;
        }

        $spreadsheet = IOFactory::createReaderForFile($path)->load($path);
        $count = 0;
        $skippedColumns = 0;
        $duplicates = 0;
        $seen = [];
        $preferredSheet = $spreadsheet->getSheetByName('VARIABLES SALUD')
            ?? $spreadsheet->getSheetByName('VARIABLES SALUD (2)');
        $worksheets = $preferredSheet ? [$preferredSheet] : $spreadsheet->getAllSheets();

        foreach ($worksheets as $sheet) {
            $current = ['codigo' => null, 'dominio' => null, 'tipo' => null];

            for ($row = 1; $row <= $sheet->getHighestDataRow(); $row++) {
                $values = [];
                for ($column = 1; $column <= 4; $column++) {
                    $values[$column] = $this->clean($sheet->getCell([$column, $row])->getFormattedValue());
                }

                if ($this->isHeader($values)) {
                    continue;
                }

                if ($values[1] && ! preg_match('/^(?:[1-9]|1[0-8]|x)$/i', $values[1])) {
                    continue;
                }

                $current['codigo'] = $values[1] ?: $current['codigo'];
                $current['dominio'] = $values[2] ?: $current['dominio'];
                $current['tipo'] = $values[3] ?: $current['tipo'];
                $prestacion = $values[4] ?: ($values[3] ? $values[3] : null);

                if (! $current['codigo'] || ! $current['dominio'] || ! $current['tipo'] || ! $prestacion) {
                    continue;
                }

                $naturalKey = Str::upper("{$current['codigo']}|{$current['tipo']}|{$prestacion}");
                if (isset($seen[$naturalKey])) {
                    $duplicates++;
                    continue;
                }
                $seen[$naturalKey] = true;

                $result = (new HealthVariableDictionary)->remember(
                    $current['codigo'],
                    $current['dominio'],
                    $current['tipo'],
                    $prestacion,
                    $row
                );

                if ($result['skipped_column']) {
                    $skippedColumns++;
                } else {
                    $count++;
                }
            }
        }

        $spreadsheet->disconnectWorksheets();
        $this->command?->info("Diccionario: {$count} ítems de catálogo, {$skippedColumns} columnas de formulario omitidas".($duplicates ? ", {$duplicates} duplicadas." : '.'));
    }

    private function clean(mixed $value): ?string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }

    private function isHeader(array $values): bool
    {
        $row = Str::upper(implode(' ', array_filter($values)));

        return str_contains($row, 'CODIGO VARIABLE')
            || str_contains($row, 'CODIGO DE VARIABLE')
            || str_contains($row, 'TIPO DE REGISTRO')
            || str_contains($row, 'PRESTACIONES');
    }
}
