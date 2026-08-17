<?php

namespace App\Application\Bioestadistica\Imports;

use App\Models\Bioestadistica\CatalogItem;
use App\Models\Bioestadistica\Catalogo;
use App\Models\Bioestadistica\VariableDefinition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

class VariablesSaludImporter
{
    private const SHEET_NAME = 'VARIABLES SALUD (2)';

    /**
     * @return array<string, mixed>
     */
    public function import(string $filePath): array
    {
        $spreadsheet = null;
        $summary = [
            'tipo' => BioestadisticaDedicatedImporter::VARIABLES_SALUD,
            'archivo' => basename($filePath),
            'procesados' => 0,
            'creados' => [
                'catalogos' => 0,
                'catalog_items' => 0,
                'variable_definitions' => 0,
            ],
            'advertencias' => [],
        ];

        try {
            $spreadsheet = $this->loadSpreadsheet($filePath);
            $sheet = $spreadsheet->getSheetByName(self::SHEET_NAME);

            if (! $sheet) {
                throw new RuntimeException(
                    "El archivo no contiene la hoja canónica '" . self::SHEET_NAME . "'."
                );
            }

            $this->assertExpectedHeaders($sheet);
            $this->importRows($sheet, $filePath, $summary);

            if ($summary['procesados'] === 0) {
                throw new RuntimeException(
                    "La hoja '" . self::SHEET_NAME . "' no contiene prestaciones válidas."
                );
            }

            return $summary;
        } catch (Throwable $exception) {
            if ($exception instanceof RuntimeException
                && str_starts_with($exception->getMessage(), 'No se pudo importar')) {
                throw $exception;
            }

            throw new RuntimeException(
                "No se pudo importar variables de salud desde '{$filePath}': {$exception->getMessage()}",
                0,
                $exception
            );
        } finally {
            $this->disconnect($spreadsheet);
        }
    }

    /**
     * @param array<string, mixed> $summary
     */
    private function importRows(
        Worksheet $sheet,
        string $filePath,
        array &$summary
    ): void {
        $current = ['codigo' => null, 'dominio' => null, 'tipo' => null];
        $seen = [];

        for ($row = 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $values = [];
            for ($column = 1; $column <= 4; $column++) {
                $values[$column] = $this->clean(
                    $sheet->getCell([$column, $row])->getFormattedValue()
                );
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

            $naturalKey = $this->key(
                "{$current['codigo']}|{$current['tipo']}|{$prestacion}"
            );
            if (isset($seen[$naturalKey])) {
                $summary['advertencias'][] =
                    "Fila {$row}: prestación duplicada omitida ({$current['tipo']} / {$prestacion}).";
                continue;
            }
            $seen[$naturalKey] = true;

            try {
                DB::transaction(function () use (
                    $current,
                    $prestacion,
                    $filePath,
                    $sheet,
                    &$summary
                ): void {
                    $catalogCode = Str::limit(
                        Str::upper(Str::slug(
                            "VAR_{$current['codigo']}_{$current['tipo']}",
                            '_'
                        )),
                        80,
                        ''
                    );

                    [$catalogo, $catalogCreated] = $this->upsert(
                        Catalogo::class,
                        ['codigo' => $catalogCode],
                        [
                            'nombre' => "{$current['dominio']} — {$current['tipo']}",
                            'descripcion' => 'Generado desde ' . basename($filePath),
                            'activo' => true,
                        ]
                    );

                    $itemCode = Str::limit(
                        Str::upper(Str::slug($prestacion, '_')),
                        65,
                        ''
                    ) . '_' . substr(sha1($prestacion), 0, 8);

                    [, $itemCreated] = $this->upsert(
                        CatalogItem::class,
                        ['catalogo_id' => $catalogo->id, 'codigo' => $itemCode],
                        [
                            'label' => $prestacion,
                            'orden' => $summary['procesados'],
                            'activo' => true,
                            'domain_code' => $current['codigo'],
                            'tipo_registro' => $current['tipo'],
                            'prestacion' => $prestacion,
                            'meta' => [
                                'source' => basename($filePath),
                                'sheet' => $sheet->getTitle(),
                            ],
                        ]
                    );

                    [, $definitionCreated] = $this->upsert(
                        VariableDefinition::class,
                        [
                            'codigo_dominio' => $current['codigo'],
                            'tipo_registro' => $current['tipo'],
                            'prestacion' => $prestacion,
                        ],
                        [
                            'dominio' => $current['dominio'],
                            'catalogo_id' => $catalogo->id,
                            'meta' => [
                                'source' => basename($filePath),
                                'sheet' => $sheet->getTitle(),
                            ],
                            'activo' => true,
                        ]
                    );

                    $summary['procesados']++;
                    $summary['creados']['catalogos'] += (int) $catalogCreated;
                    $summary['creados']['catalog_items'] += (int) $itemCreated;
                    $summary['creados']['variable_definitions'] += (int) $definitionCreated;
                });
            } catch (Throwable $exception) {
                throw new RuntimeException(
                    "Error en la fila {$row} de '" . self::SHEET_NAME . "': {$exception->getMessage()}",
                    0,
                    $exception
                );
            }
        }
    }

    private function assertExpectedHeaders(Worksheet $sheet): void
    {
        $found = false;
        $lastRow = min(30, $sheet->getHighestDataRow());

        for ($row = 1; $row <= $lastRow; $row++) {
            $cells = [];
            for ($column = 1; $column <= 4; $column++) {
                $cells[] = $this->key($sheet->getCell([$column, $row])->getFormattedValue());
            }

            $joined = implode('|', $cells);
            if (str_contains($joined, 'CODIGO_DE_VARIABLE')
                && str_contains($joined, 'DESCRIPCION_DE_VARIABLE')
                && str_contains($joined, 'TIPO_DE_REGISTRO')
                && str_contains($joined, 'PRESTACIONES')) {
                $found = true;
                break;
            }
        }

        if (! $found) {
            throw new RuntimeException(
                "La hoja '" . self::SHEET_NAME . "' no tiene los encabezados esperados."
            );
        }
    }

    /**
     * @param array<int, string|null> $values
     */
    private function isHeader(array $values): bool
    {
        $row = $this->key(implode(' ', array_filter($values)));

        return str_contains($row, 'CODIGO_DE_VARIABLE')
            || str_contains($row, 'TIPO_DE_REGISTRO')
            || str_contains($row, 'PRESTACIONES');
    }

    private function loadSpreadsheet(string $filePath): Spreadsheet
    {
        try {
            return IOFactory::createReaderForFile($filePath)
                ->setReadDataOnly(true)
                ->load($filePath);
        } catch (Throwable $exception) {
            throw new RuntimeException(
                "El archivo no pudo abrirse como Excel: {$exception->getMessage()}",
                0,
                $exception
            );
        }
    }

    /**
     * @template T of Model
     * @param class-string<T> $model
     * @param array<string, mixed> $identity
     * @param array<string, mixed> $attributes
     * @return array{0: T, 1: bool}
     */
    private function upsert(
        string $model,
        array $identity,
        array $attributes
    ): array {
        /** @var T $instance */
        $instance = $model::withTrashed()->firstOrNew($identity);
        $created = ! $instance->exists;

        if ($instance->exists && method_exists($instance, 'trashed') && $instance->trashed()) {
            $instance->restore();
        }

        $instance->fill($attributes);
        $instance->save();

        return [$instance, $created];
    }

    private function clean(mixed $value): ?string
    {
        $value = preg_replace('/\s+/u', ' ', trim((string) $value));

        return $value === '' ? null : $value;
    }

    private function key(mixed $value): string
    {
        return trim(
            preg_replace('/[^A-Z0-9]+/', '_', Str::upper(Str::ascii((string) $value))),
            '_'
        );
    }

    private function disconnect(?Spreadsheet $spreadsheet): void
    {
        if ($spreadsheet) {
            $spreadsheet->disconnectWorksheets();
        }
    }
}
