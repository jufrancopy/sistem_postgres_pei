<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\CatalogItem;
use App\Models\Bioestadistica\Catalogo;
use App\Models\Bioestadistica\VariableDefinition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BioestadisticaVariablesSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('.docs-bio/variables salud.xls');
        if (! is_file($path)) {
            $this->command?->warn("No se encontró {$path}; se omite el diccionario de variables.");
            return;
        }

        $spreadsheet = IOFactory::createReaderForFile($path)
            ->setReadDataOnly(true)
            ->load($path);
        $count = 0;
        $duplicates = 0;
        $seen = [];
        $preferredSheet = $spreadsheet->getSheetByName('VARIABLES SALUD (2)');
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
                $prestacion = $values[4];

                if (! $current['codigo'] || ! $current['dominio'] || ! $current['tipo'] || ! $prestacion) {
                    continue;
                }

                $naturalKey = Str::upper("{$current['codigo']}|{$current['tipo']}|{$prestacion}");
                if (isset($seen[$naturalKey])) {
                    $duplicates++;
                    $this->command?->warn("Prestación duplicada omitida: {$current['tipo']} / {$prestacion}");
                    continue;
                }
                $seen[$naturalKey] = true;

                $catalogCode = Str::upper(Str::slug(
                    "VAR_{$current['codigo']}_{$current['tipo']}",
                    '_'
                ));
                $catalogCode = Str::limit($catalogCode, 80, '');

                $catalogo = Catalogo::updateOrCreate(
                    ['codigo' => $catalogCode],
                    [
                        'nombre' => "{$current['dominio']} — {$current['tipo']}",
                        'descripcion' => 'Generado desde variables salud.xls',
                        'activo' => true,
                    ]
                );

                $itemCode = Str::upper(Str::slug($prestacion, '_'));
                $itemCode = Str::limit($itemCode, 65, '') . '_' . substr(sha1($prestacion), 0, 8);

                CatalogItem::updateOrCreate(
                    ['catalogo_id' => $catalogo->id, 'codigo' => $itemCode],
                    [
                        'label' => $prestacion,
                        'orden' => $count,
                        'activo' => true,
                        'domain_code' => $current['codigo'],
                        'tipo_registro' => $current['tipo'],
                        'prestacion' => $prestacion,
                        'meta' => ['source' => basename($path), 'sheet' => $sheet->getTitle()],
                    ]
                );

                VariableDefinition::updateOrCreate(
                    [
                        'codigo_dominio' => $current['codigo'],
                        'tipo_registro' => $current['tipo'],
                        'prestacion' => $prestacion,
                    ],
                    [
                        'dominio' => $current['dominio'],
                        'catalogo_id' => $catalogo->id,
                        'meta' => ['source' => basename($path), 'sheet' => $sheet->getTitle()],
                        'activo' => true,
                    ]
                );
                $count++;
            }
        }

        $spreadsheet->disconnectWorksheets();
        $this->command?->info("Diccionario Bioestadística: {$count} prestaciones procesadas" . ($duplicates ? " ({$duplicates} duplicadas omitidas)." : '.'));
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
            || str_contains($row, 'TIPO DE REGISTRO')
            || str_contains($row, 'PRESTACIONES');
    }
}
