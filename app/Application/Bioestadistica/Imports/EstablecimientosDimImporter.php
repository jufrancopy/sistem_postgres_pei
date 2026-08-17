<?php

namespace App\Application\Bioestadistica\Imports;

use App\Models\Bioestadistica\AreaGestion;
use App\Models\Bioestadistica\Departamento;
use App\Models\Bioestadistica\Distrito;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\GradoComplejidad;
use App\Models\Bioestadistica\Microred;
use App\Models\Bioestadistica\TipoEstablecimiento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RuntimeException;
use Throwable;

class EstablecimientosDimImporter
{
    private const DIMENSION_SHEET = 'DIM_ESTABLECIMIENTOS';

    /**
     * @return array<string, mixed>
     */
    public function import(string $filePath): array
    {
        $spreadsheet = null;
        $summary = [
            'tipo' => BioestadisticaDedicatedImporter::ESTABLECIMIENTOS_DIM,
            'archivo' => basename($filePath),
            'procesados' => 0,
            'creados' => [
                'departamentos' => 0,
                'microredes' => 0,
                'tipos_establecimiento' => 0,
                'grados_complejidad' => 0,
                'areas_gestion' => 0,
                'establecimientos' => 0,
            ],
            'advertencias' => [],
        ];

        try {
            $spreadsheet = $this->loadSpreadsheet($filePath);
            [$dimensionSheet, $systems] = $this->inspectWorkbook($spreadsheet, $summary);
            [$headerRow, $headers] = $this->findHeaders(
                $dimensionSheet,
                ['ID_ESTABLECIMIENTO']
            );

            if (! in_array('ESTABLECIMIENTO', $headers, true)
                && ! in_array('NOMBRE_ESTABLECIMIENTO', $headers, true)) {
                throw new RuntimeException(
                    "La hoja DIM ESTABLECIMIENTOS no contiene la columna ESTABLECIMIENTO."
                );
            }

            $this->importRows(
                $dimensionSheet,
                $headerRow,
                $headers,
                $systems,
                $summary
            );

            if ($summary['procesados'] === 0) {
                throw new RuntimeException(
                    'La hoja DIM ESTABLECIMIENTOS no contiene establecimientos válidos.'
                );
            }

            return $summary;
        } catch (Throwable $exception) {
            if ($exception instanceof RuntimeException
                && str_starts_with($exception->getMessage(), 'No se pudo importar')) {
                throw $exception;
            }

            throw new RuntimeException(
                "No se pudo importar el maestro de establecimientos desde '{$filePath}': "
                    . $exception->getMessage(),
                0,
                $exception
            );
        } finally {
            $this->disconnect($spreadsheet);
        }
    }

    /**
     * @param array<string, mixed> $summary
     * @return array{0: Worksheet, 1: array<string, string>}
     */
    private function inspectWorkbook(Spreadsheet $spreadsheet, array &$summary): array
    {
        $dimensionSheet = null;
        $systems = [];
        $systemSheetFound = false;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $title = $this->key($sheet->getTitle());

            if ($title === self::DIMENSION_SHEET) {
                $dimensionSheet = $sheet;
            }

            if (str_contains($title, 'CODIGO_SIH')) {
                $systemSheetFound = true;
                $systems = $this->readSystems($sheet);
            }
        }

        if (! $dimensionSheet) {
            throw new RuntimeException(
                "El archivo no contiene la hoja requerida 'DIM ESTABLECIMIENTOS'."
            );
        }

        if (! $systemSheetFound) {
            $summary['advertencias'][] =
                "No se encontró la hoja CODIGO SIH; se usará SISTEMA de la hoja DIM si existe.";
        } elseif ($systems === []) {
            $summary['advertencias'][] =
                'La hoja CODIGO SIH no contiene cruces válidos de código y sistema.';
        }

        return [$dimensionSheet, $systems];
    }

    /**
     * @param array<int, string> $headers
     * @param array<string, string> $systems
     * @param array<string, mixed> $summary
     */
    private function importRows(
        Worksheet $sheet,
        int $headerRow,
        array $headers,
        array $systems,
        array &$summary
    ): void {
        for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $data = $this->row($sheet, $row, $headers);
            $code = $this->value($data, [
                'ID_ESTABLECIMIENTO',
                'CODIGO_ESTABLECIMIENTO',
                'ID',
            ]);
            $name = $this->value($data, [
                'ESTABLECIMIENTO',
                'NOMBRE_ESTABLECIMIENTO',
            ]);

            if (! $code && ! $name) {
                continue;
            }

            if (! $code || ! $name) {
                $summary['advertencias'][] =
                    "Fila {$row}: omitida por falta de ID_ESTABLECIMIENTO o ESTABLECIMIENTO.";
                continue;
            }

            try {
                DB::transaction(function () use (
                    $data,
                    $code,
                    $name,
                    $systems,
                    &$summary
                ): void {
                    $departmentCode = $this->value($data, [
                        'ID_DEPTO',
                        'CODIGO_DEPARTAMENTO',
                    ]);
                    $departmentName = $this->value($data, [
                        'DEPARTAMENTO',
                        'DPTO',
                    ]);

                    if ($departmentName
                        && in_array($this->key($departmentName), ['ASUNCION', 'CAPITAL'], true)) {
                        $departmentCode = '18';
                        $departmentName = 'ASUNCIÓN';
                    }

                    $department = null;
                    if ($departmentCode && $departmentName) {
                        [$department, $created] = $this->upsert(
                            Departamento::class,
                            ['codigo' => (string) $departmentCode],
                            ['nombre' => $departmentName, 'activo' => true]
                        );
                        $summary['creados']['departamentos'] += (int) $created;
                    }

                    [$microred, $microredCreated] = $this->lookup(
                        Microred::class,
                        $this->value($data, ['MICRORED'])
                    );
                    [$type, $typeCreated] = $this->lookup(
                        TipoEstablecimiento::class,
                        $this->value($data, [
                            'TIPO_DE_ESTABLECIMIENTO',
                            'TIPO_ESTABLECIMIENTO',
                        ])
                    );
                    [$area, $areaCreated] = $this->lookup(
                        AreaGestion::class,
                        $this->value($data, ['AREA_DE_GESTION', 'AREA_GESTION'])
                    );

                    [$degree, $degreeCreated] = $this->degree($data);
                    $district = $this->resolveDistrict(
                        $department,
                        $this->value($data, ['DISTRITO'])
                    );
                    $sihCode = $this->value($data, ['CODIGO_SIH', 'COD_SIH']);
                    $system = $systems[$this->key($sihCode)] ??
                        $this->value($data, ['SISTEMA']);

                    $attributes = [
                        'nombre' => $name,
                        'microred_id' => $microred?->id,
                        'tipo_establecimiento_id' => $type?->id,
                        'grado_complejidad_id' => $degree?->id,
                        'area_gestion_id' => $area?->id,
                        'nivel_atencion' => $this->value($data, [
                            'NIVEL_DE_ATENCION',
                            'NIVEL_ATENCION',
                        ]),
                        'prestador' => $this->value($data, ['PRESTADOR']),
                        'situacion_inmueble' => $this->value($data, ['SITUACION_INMUEBLE']),
                        'codigo_sih' => $sihCode,
                        'sistema' => $system,
                        'latitud' => $this->decimal(
                            $this->value($data, ['LATITUD', 'LAT']),
                            90
                        ),
                        'longitud' => $this->decimal(
                            $this->value($data, ['LONGITUD', 'LONG', 'LON']),
                            180
                        ),
                        'observacion' => $this->value($data, [
                            'OBSERVACION',
                            'OBSERVACIONES',
                        ]),
                    ];

                    // El DIM normalmente no trae distrito. No borra una asignación previa
                    // cuando no hay una coincidencia explícita en el archivo.
                    if ($district) {
                        $attributes['distrito_id'] = $district->id;
                    }

                    [, $establishmentCreated] = $this->upsert(
                        Establecimiento::class,
                        ['codigo' => (string) $code],
                        $attributes
                    );

                    $summary['procesados']++;
                    $summary['creados']['microredes'] += (int) $microredCreated;
                    $summary['creados']['tipos_establecimiento'] += (int) $typeCreated;
                    $summary['creados']['grados_complejidad'] += (int) $degreeCreated;
                    $summary['creados']['areas_gestion'] += (int) $areaCreated;
                    $summary['creados']['establecimientos'] += (int) $establishmentCreated;
                });
            } catch (Throwable $exception) {
                throw new RuntimeException(
                    "Error en la fila {$row} de DIM ESTABLECIMIENTOS: {$exception->getMessage()}",
                    0,
                    $exception
                );
            }
        }
    }

    /**
     * @return array<string, string>
     */
    private function readSystems(Worksheet $sheet): array
    {
        try {
            [$headerRow, $headers] = $this->findHeaders(
                $sheet,
                ['CODIGO', 'CODIGO_SIH', 'COD_SIH']
            );
        } catch (RuntimeException) {
            return [];
        }

        $systems = [];
        for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $data = $this->row($sheet, $row, $headers);
            $code = $this->value($data, ['CODIGO', 'CODIGO_SIH', 'COD_SIH']);
            $system = $this->value($data, ['SISTEMA']);

            if ($code && $system) {
                $systems[$this->key($code)] = $system;
            }
        }

        return $systems;
    }

    /**
     * @param array<int, string> $requiredAlternatives
     * @return array{0: int, 1: array<int, string>}
     */
    private function findHeaders(
        Worksheet $sheet,
        array $requiredAlternatives
    ): array {
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        $lastRow = min(30, $sheet->getHighestDataRow());

        for ($row = 1; $row <= $lastRow; $row++) {
            $headers = [];
            for ($column = 1; $column <= $highestColumn; $column++) {
                $headers[$column] = $this->key(
                    $sheet->getCell([$column, $row])->getFormattedValue()
                );
            }

            if (array_intersect($requiredAlternatives, $headers) !== []) {
                return [$row, $headers];
            }
        }

        throw new RuntimeException(
            'No se encontró ninguna de las columnas '
                . implode(', ', $requiredAlternatives)
                . " en la hoja '{$sheet->getTitle()}'."
        );
    }

    /**
     * @param array<int, string> $headers
     * @return array<string, string>
     */
    private function row(Worksheet $sheet, int $row, array $headers): array
    {
        $data = [];
        foreach ($headers as $column => $header) {
            if ($header !== '') {
                $data[$header] = trim(
                    (string) $sheet->getCell([$column, $row])->getFormattedValue()
                );
            }
        }

        return $data;
    }

    /**
     * @param array<string, string> $data
     * @param array<int, string> $aliases
     */
    private function value(array $data, array $aliases): ?string
    {
        foreach ($aliases as $alias) {
            $value = trim((string) ($data[$alias] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param class-string<Model> $model
     * @return array{0: Model|null, 1: bool}
     */
    private function lookup(string $model, ?string $value): array
    {
        if (! $value) {
            return [null, false];
        }

        return $this->upsert(
            $model,
            ['nombre' => $value],
            ['activo' => true]
        );
    }

    /**
     * @param array<string, string> $data
     * @return array{0: GradoComplejidad|null, 1: bool}
     */
    private function degree(array $data): array
    {
        $raw = $this->value($data, [
            'GRADO_DE_COMPLEJIDAD',
            'GRADO_COMPLEJIDAD',
        ]);
        preg_match('/\d+/', (string) $raw, $matches);
        $code = $matches[0] ?? null;

        if (! $code) {
            return [null, false];
        }

        $description = $this->value($data, [
            'COMPLEJIDAD_DESCRIPCION',
            'DESCRIPCION_COMPLEJIDAD',
        ]) ?: "Grado {$code}";

        /** @var array{0: GradoComplejidad, 1: bool} */
        return $this->upsert(
            GradoComplejidad::class,
            ['codigo' => (string) $code, 'descripcion' => $description],
            ['activo' => true]
        );
    }

    private function resolveDistrict(
        ?Departamento $department,
        ?string $districtName
    ): ?Distrito {
        if (! $department || ! $districtName) {
            return null;
        }

        $wanted = $this->key($districtName);

        return Distrito::where('departamento_id', $department->id)
            ->get()
            ->first(function (Distrito $district) use ($wanted): bool {
                $candidate = $this->key($district->nombre);

                return $candidate === $wanted
                    || preg_replace('/^COLONIA_/', '', $candidate) === $wanted
                    || $candidate === preg_replace('/^COLONIA_/', '', $wanted);
            });
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

    private function decimal(?string $value, float $maximum): ?float
    {
        if (! $value) {
            return null;
        }

        $normalized = str_replace(',', '.', preg_replace('/\s+/', '', $value));
        if (! is_numeric($normalized)) {
            return null;
        }

        $number = (float) $normalized;
        while (abs($number) > $maximum) {
            $number /= 10;
        }

        return $number;
    }

    private function key(mixed $value): string
    {
        return trim(
            preg_replace('/[^A-Z0-9]+/', '_', Str::upper(Str::ascii((string) $value))),
            '_'
        );
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

    private function disconnect(?Spreadsheet $spreadsheet): void
    {
        if ($spreadsheet) {
            $spreadsheet->disconnectWorksheets();
        }
    }
}
