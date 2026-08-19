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

    private const DIMENSION_SHEET_ALT = 'ESTABLECIMIENTOS';

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
                'distritos' => 0,
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
            [$dimensionSheet, $systems, $districtSheet] = $this->inspectWorkbook($spreadsheet, $summary);
            if ($districtSheet) {
                $this->importDistricts($districtSheet, $summary);
            }
            [$headerRow, $headers] = $this->findHeaders(
                $dimensionSheet,
                ['ID_ESTABLECIMIENTO']
            );

            if (! in_array('ESTABLECIMIENTO', $headers, true)
                && ! in_array('ESTABLECIMIENTOS', $headers, true)
                && ! in_array('NOMBRE_ESTABLECIMIENTO', $headers, true)) {
                throw new RuntimeException(
                    "La hoja de establecimientos no contiene la columna ESTABLECIMIENTO."
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
                    'La hoja de establecimientos no contiene establecimientos válidos.'
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
     * @return array{0: Worksheet, 1: array{by_code: array<string, array{codigo: string, sistema: string, nombre: string}>, by_name: array<string, array{codigo: string, sistema: string, nombre: string}>}, 2: Worksheet|null}
     */
    private function inspectWorkbook(Spreadsheet $spreadsheet, array &$summary): array
    {
        $dimensionSheet = null;
        $districtSheet = null;
        $systems = ['by_code' => [], 'by_name' => []];
        $systemSheetFound = false;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $title = $this->key($sheet->getTitle());

            if ($title === self::DIMENSION_SHEET) {
                $dimensionSheet = $sheet;
            } elseif ($title === self::DIMENSION_SHEET_ALT && ! $dimensionSheet) {
                $dimensionSheet = $sheet;
            }

            if ($title === 'DISTRITOS' || $title === 'DISTRITO') {
                $districtSheet = $sheet;
            }

            if (str_contains($title, 'CODIGO_SIH')) {
                $systemSheetFound = true;
                $systems = $this->readSihIndex($sheet);
            }
        }

        if (! $dimensionSheet) {
            throw new RuntimeException(
                "El archivo no contiene la hoja requerida 'DIM ESTABLECIMIENTOS' o 'ESTABLECIMIENTOS'."
            );
        }

        if (! $districtSheet) {
            $summary['advertencias'][] =
                'No se encontró la hoja DISTRITOS; el distrito se resolverá solo por la columna del maestro.';
        }

        if (! $systemSheetFound) {
            $summary['advertencias'][] =
                "No se encontró la hoja CODIGO SIH; se usará SISTEMA de la hoja de establecimientos si existe.";
        } elseif ($systems['by_code'] === [] && $systems['by_name'] === []) {
            $summary['advertencias'][] =
                'La hoja CODIGO SIH no contiene cruces válidos de código y sistema.';
        }

        return [$dimensionSheet, $systems, $districtSheet];
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
                        'ESTABLECIMIENTOS',
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
                        'ID_DEPARTAMENTO',
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
                    $explicitDistrict = $this->value($data, ['DISTRITO']);
                    $sih = $this->matchSih(
                        $systems,
                        $this->value($data, ['CODIGO_SIH', 'COD_SIH', 'CODIGO']),
                        $name
                    );
                    $district = $this->resolveDistrict($department, $explicitDistrict)
                        ?: $this->matchDistrictByName(
                            $department,
                            $name,
                            $departmentCode,
                            $sih['nombre'] ?? null
                        );
                    $systemFromSheet = $this->normalizeSistema(
                        $this->value($data, ['SISTEMA_HOSPITALARIO', 'SISTEMA'])
                    );
                    $sihCode = $sih['codigo'] ?? $this->value($data, ['CODIGO_SIH', 'COD_SIH']);
                    $system = $sih['sistema'] ?? $systemFromSheet;

                    $existing = Establecimiento::withTrashed()->where('codigo', (string) $code)->first();
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
                        'situacion_inmueble' => $this->value($data, [
                            'SITUACION_INMUEBLE',
                            'SITUACION',
                        ]),
                        'codigo_sih' => $sihCode ?: $existing?->codigo_sih,
                        'sistema' => $system ?: $existing?->sistema,
                        'latitud' => $this->decimal(
                            $this->value($data, ['LATITUD', 'LATITUDE', 'LAT']),
                            90
                        ),
                        'longitud' => $this->decimal(
                            $this->value($data, ['LONGITUD', 'LONGITUDE', 'LONG', 'LON']),
                            180
                        ),
                        'observacion' => $this->value($data, [
                            'OBSERVACION',
                            'OBSERVACIONES',
                        ]),
                    ];

                    if ($district && ($explicitDistrict || ! $existing?->distrito_id)) {
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
                    "Error en la fila {$row} de establecimientos: {$exception->getMessage()}",
                    0,
                    $exception
                );
            }
        }
    }

    /**
     * @return array{by_code: array<string, array{codigo: string, sistema: ?string, nombre: string}>, by_name: array<string, array{codigo: string, sistema: ?string, nombre: string}>}
     */
    private function readSihIndex(Worksheet $sheet): array
    {
        try {
            [$headerRow, $headers] = $this->findHeaders(
                $sheet,
                ['SISTEMA_HOSPITALARIO', 'SISTEMA', 'CODIGO', 'CODIGO_SIH', 'COD_SIH']
            );
        } catch (RuntimeException) {
            return ['by_code' => [], 'by_name' => []];
        }

        $byCode = [];
        $byName = [];
        for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $data = $this->row($sheet, $row, $headers);
            $code = $this->value($data, ['CODIGO', 'CODIGO_SIH', 'COD_SIH', 'ESTABLECIMIENTOS']);
            $name = $this->value($data, ['ESTABLECIMIENTO', 'NOMBRE']);
            $system = $this->normalizeSistema(
                $this->value($data, ['SISTEMA_HOSPITALARIO', 'SISTEMA'])
            );
            if (! $code && ! $name) {
                continue;
            }
            $entry = [
                'codigo' => (string) $code,
                'sistema' => $system,
                'nombre' => (string) $name,
            ];
            if ($code) {
                $byCode[$this->key($code)] = $entry;
            }
            if ($name) {
                $byName[$this->expandPlace($this->key($name))] = $entry;
            }
        }

        return ['by_code' => $byCode, 'by_name' => $byName];
    }

    /**
     * @param array{by_code: array<string, array{codigo: string, sistema: ?string, nombre: string}>, by_name: array<string, array{codigo: string, sistema: ?string, nombre: string}>} $systems
     * @return array{codigo: ?string, sistema: ?string, nombre: ?string}|null
     */
    private function matchSih(array $systems, ?string $sihCode, string $establishmentName): ?array
    {
        if ($sihCode && isset($systems['by_code'][$this->key($sihCode)])) {
            $hit = $systems['by_code'][$this->key($sihCode)];

            return [
                'codigo' => $hit['codigo'] ?: $sihCode,
                'sistema' => $hit['sistema'],
                'nombre' => $hit['nombre'] ?: null,
            ];
        }

        $want = $this->matchKey($establishmentName);
        $best = null;
        $bestScore = 0;
        foreach ($systems['by_name'] as $nameKey => $entry) {
            $candidate = $this->matchKey($entry['nombre'] ?: $nameKey);
            $score = $this->placeScore($want, $candidate);
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $entry;
            }
        }
        if ($bestScore < 70 || ! $best) {
            return $sihCode
                ? ['codigo' => $sihCode, 'sistema' => null, 'nombre' => null]
                : null;
        }

        return [
            'codigo' => $best['codigo'] ?: $sihCode,
            'sistema' => $best['sistema'],
            'nombre' => $best['nombre'] ?: null,
        ];
    }

    private function importDistricts(Worksheet $sheet, array &$summary): void
    {
        try {
            [$headerRow, $headers] = $this->findHeaders($sheet, ['DISTRITO', 'ID_DISTRITO', 'DENOMINACION']);
        } catch (RuntimeException) {
            $summary['advertencias'][] = 'La hoja DISTRITOS no tiene columnas reconocibles.';

            return;
        }

        for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $data = $this->row($sheet, $row, $headers);
            $departmentCode = $this->value($data, ['ID_DEPARTAMENTO', 'ID_DEPTO', 'ID_DPTO']);
            $districtName = $this->value($data, ['DISTRITO', 'DENOMINACION', 'NOMBRE']);
            $districtCode = $this->value($data, ['ID_DISTRITO']);
            $departmentLabel = $this->value($data, ['DEPARTAMENTO']);
            if (! $departmentCode || ! $districtName) {
                continue;
            }
            if ((string) $departmentCode === '50') {
                continue;
            }
            $departmentName = $departmentLabel
                ? trim((string) preg_replace('/^\d+\s*-\s*/', '', $departmentLabel))
                : "Departamento {$departmentCode}";
            if ($this->key($departmentName) === 'ASUNCION' || $this->key($departmentName) === 'CAPITAL') {
                $departmentCode = '18';
                $departmentName = 'ASUNCIÓN';
            }
            [$department, $createdDepartment] = $this->upsert(
                Departamento::class,
                ['codigo' => (string) $departmentCode],
                ['nombre' => $departmentName, 'activo' => true]
            );
            $summary['creados']['departamentos'] += (int) $createdDepartment;
            [, $createdDistrict] = $this->upsert(
                Distrito::class,
                ['departamento_id' => $department->id, 'nombre' => $districtName],
                ['codigo' => $districtCode !== null ? (string) $districtCode : null, 'activo' => true]
            );
            $summary['creados']['distritos'] += (int) $createdDistrict;
        }
    }

    private function matchDistrictByName(
        ?Departamento $department,
        string $establishmentName,
        ?string $departmentCode,
        ?string $sihName = null
    ): ?Distrito {
        if (! $department) {
            return null;
        }
        if (in_array((string) $departmentCode, ['18'], true) || $this->key($department->nombre) === 'ASUNCION') {
            return Distrito::where('departamento_id', $department->id)
                ->get()
                ->first(fn (Distrito $district) => $this->key($district->nombre) === 'ASUNCION')
                ?: Distrito::where('departamento_id', $department->id)->first();
        }

        $districts = Distrito::where('departamento_id', $department->id)->get();
        $best = null;
        $bestScore = 0;
        foreach (array_filter([$establishmentName, $sihName]) as $needle) {
            $want = $this->districtKey($needle);
            foreach ($districts as $district) {
                $candidate = $this->districtKey($district->nombre);
                $score = $this->placeScore($want, $candidate);
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $best = $district;
                }
            }
        }

        return $bestScore >= 70 ? $best : null;
    }

    private function matchKey(string $value): string
    {
        return $this->stripEstablishmentNoise(
            $this->expandPlace($this->normalizeAbbrev($this->key($value)))
        );
    }

    private function districtKey(string $value): string
    {
        $key = $this->matchKey($value);
        $aliases = [
            'VALLEMI' => 'SAN_LAZARO',
            'PUERTO_ROSARIO' => 'VILLA_DEL_ROSARIO',
            'PIQUETE_CUE' => 'LIMPIO',
            'M_R_A' => 'MARIANO_ROQUE_ALONSO',
            'MRA' => 'MARIANO_ROQUE_ALONSO',
        ];
        foreach ($aliases as $from => $to) {
            $key = preg_replace('/(^|_)'.$from.'(_|$)/', '$1'.$to.'$2', $key) ?? $key;
        }

        return $this->stripEstablishmentNoise($key);
    }

    private function normalizeAbbrev(string $key): string
    {
        $key = preg_replace('/(^|_)P_S(_|$)/', '$1PS$2', $key) ?? $key;
        $key = preg_replace('/(^|_)U_S(_|$)/', '$1US$2', $key) ?? $key;
        $key = preg_replace('/(^|_)H_R(_|$)/', '$1HR$2', $key) ?? $key;
        $key = preg_replace('/(^|_)C_P(_|$)/', '$1CP$2', $key) ?? $key;
        $key = str_replace(['S_R_L', 'SRL'], 'SRL', $key);

        return $key;
    }

    private function expandPlace(string $key): string
    {
        $tokens = explode('_', $key);
        $aliases = [
            'CNEL' => 'CORONEL',
            'GRAL' => 'GENERAL',
            'TTE' => 'TENIENTE',
            'MCAL' => 'MARISCAL',
            'PTO' => 'PUERTO',
            'STA' => 'SANTA',
            'PTE' => 'PRESIDENTE',
            'ANTONIO' => 'A',
            'CONV' => 'CONVENIO',
        ];
        $tokens = array_map(fn (string $token) => $aliases[$token] ?? $token, $tokens);
        $joined = implode('_', array_filter($tokens, fn (string $token) => $token !== ''));

        return str_replace(
            ['YCUAMANDY_YU', 'ITAPUA_POTY', 'ITAKYRY', 'PINASCO', 'KYHA', 'QUYQUYO', 'PO_I'],
            ['YCUAMANDIYU', 'YTAPUA_POTY', 'ITAQUYRY', 'PINAZCO', 'KYJHA', 'QUYQUYHO', 'POI'],
            $joined
        );
    }

    private function stripEstablishmentNoise(string $key): string
    {
        $noise = [
            'PS', 'US', 'HR', 'HE', 'HO', 'CE', 'CP', 'TE', 'OT', 'MP', 'CO',
            'CONVENIO', 'TERCERIZADO', 'HOSPITAL', 'SANATORIO', 'CLINICA',
            'CENTRO', 'PUESTO', 'SANITARIO', 'UNIDAD', 'SERVICIO', 'COLONIA',
            'PERIFERICA', 'DE', 'DEL', 'LA', 'EL', 'LOS', 'LAS',
        ];
        $tokens = array_values(array_filter(
            explode('_', $key),
            fn (string $token) => $token !== '' && ! in_array($token, $noise, true)
        ));

        return implode('_', $tokens);
    }

    private function placeScore(string $left, string $right): int
    {
        if ($left === '' || $right === '') {
            return 0;
        }
        if ($left === $right) {
            return 100;
        }
        if (str_contains($left, $right) && mb_strlen($right) >= 4) {
            return 70 + min(25, mb_strlen($right));
        }
        if (str_contains($right, $left) && mb_strlen($left) >= 4) {
            return 65 + min(25, mb_strlen($left));
        }
        $leftTokens = $this->significantTokens($left);
        $rightTokens = $this->significantTokens($right);
        if ($leftTokens === [] || $rightTokens === []) {
            return 0;
        }
        $shared = array_values(array_intersect($leftTokens, $rightTokens));
        $meaningful = array_values(array_diff($shared, ['SAN', 'SANTA', 'VILLA', 'NUEVA', 'MARIA']));
        if ($meaningful === [] || count($shared) < 2) {
            return 0;
        }
        if (count($shared) === count($rightTokens)) {
            return 80;
        }
        if (count($shared) === count($leftTokens)) {
            return 75;
        }

        return 0;
    }

    /**
     * @return array<int, string>
     */
    private function significantTokens(string $key): array
    {
        return array_values(array_unique(array_filter(
            explode('_', $key),
            fn (string $token): bool => mb_strlen($token) > 1
        )));
    }

    private function normalizeSistema(?string $value): ?string
    {
        if (! $value) {
            return null;
        }
        $key = $this->key($value);
        if (in_array($key, ['SIN_SISTEMA', 'NA', 'N_A', 'NO'], true)) {
            return null;
        }

        return $value;
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
            'DESCRIPCION_DE_COMPLEJIDAD',
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

        $wanted = $this->expandPlace($this->key($districtName));

        return Distrito::where('departamento_id', $department->id)
            ->get()
            ->first(function (Distrito $district) use ($wanted): bool {
                $candidate = $this->expandPlace($this->key($district->nombre));

                return $candidate === $wanted
                    || $this->placeScore($wanted, $candidate) >= 90;
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
