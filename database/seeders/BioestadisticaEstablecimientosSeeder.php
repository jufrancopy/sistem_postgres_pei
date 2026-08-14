<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\AreaGestion;
use App\Models\Bioestadistica\Departamento;
use App\Models\Bioestadistica\Distrito;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\GradoComplejidad;
use App\Models\Bioestadistica\Microred;
use App\Models\Bioestadistica\TipoEstablecimiento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BioestadisticaEstablecimientosSeeder extends Seeder
{
    public function run(): void
    {
        $path = $this->sourcePath();
        if (! $path || ! is_file($path)) {
            $this->command?->warn('No hay un maestro de establecimientos completo (mínimo 100 filas); se preservan los datos existentes.');
            return;
        }

        $spreadsheet = IOFactory::createReaderForFile($path)
            ->setReadDataOnly(true)
            ->load($path);
        $systems = [];
        $dimensionSheet = null;

        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            $title = $this->key($sheet->getTitle());
            if (str_contains($title, 'CODIGO_SIH')) {
                $systems = $this->readSystems($sheet);
            }
            if (str_contains($title, 'DIM_ESTABLECIMIENTOS')) {
                $dimensionSheet = $sheet;
            }
        }

        if (! $dimensionSheet) {
            $spreadsheet->disconnectWorksheets();
            $this->command?->error('No se encontró la hoja DIM ESTABLECIMIENTOS.');
            return;
        }

        [$headerRow, $headers] = $this->findHeaders($dimensionSheet, 'ID_ESTABLECIMIENTO');
        $count = 0;

        for ($row = $headerRow + 1; $row <= $dimensionSheet->getHighestDataRow(); $row++) {
            $data = $this->row($dimensionSheet, $row, $headers);
            $code = $this->value($data, ['ID_ESTABLECIMIENTO', 'CODIGO_ESTABLECIMIENTO', 'ID']);
            $name = $this->value($data, ['ESTABLECIMIENTO', 'NOMBRE_ESTABLECIMIENTO']);
            if (! $code || ! $name) {
                continue;
            }

            $departmentCode = $this->value($data, ['ID_DEPTO', 'CODIGO_DEPARTAMENTO']);
            $departmentName = $this->value($data, ['DEPARTAMENTO', 'DPTO']);
            if ($departmentName && in_array($this->key($departmentName), ['ASUNCION', 'CAPITAL'], true)) {
                $departmentCode = '18';
                $departmentName = 'ASUNCIÓN';
            }
            if ($departmentCode && $departmentName) {
                Departamento::updateOrCreate(
                    ['codigo' => (string) $departmentCode],
                    ['nombre' => $departmentName, 'activo' => true]
                );
            }

            $microred = $this->lookup(Microred::class, 'nombre', $this->value($data, ['MICRORED']));
            $tipo = $this->lookup(
                TipoEstablecimiento::class,
                'nombre',
                $this->value($data, ['TIPO_DE_ESTABLECIMIENTO', 'TIPO_ESTABLECIMIENTO'])
            );
            $area = $this->lookup(
                AreaGestion::class,
                'nombre',
                $this->value($data, ['AREA_DE_GESTION', 'AREA_GESTION'])
            );

            $degreeRaw = $this->value($data, ['GRADO_DE_COMPLEJIDAD', 'GRADO_COMPLEJIDAD']);
            preg_match('/\d+/', (string) $degreeRaw, $degreeMatch);
            $degreeCode = $degreeMatch[0] ?? null;
            $degreeDescription = $this->value($data, ['COMPLEJIDAD_DESCRIPCION', 'DESCRIPCION_COMPLEJIDAD']);
            $degree = null;
            if ($degreeCode) {
                $degree = GradoComplejidad::updateOrCreate(
                    [
                        'codigo' => (string) $degreeCode,
                        'descripcion' => $degreeDescription ?: "Grado {$degreeCode}",
                    ],
                    ['activo' => true]
                );
            }

            $sihCode = $this->value($data, ['CODIGO_SIH', 'COD_SIH']);
            $district = $this->resolveDistrict(
                (string) $departmentCode,
                $this->value($data, ['DISTRITO'])
            );
            $attributes = [
                    'nombre' => $name,
                    'microred_id' => $microred?->id,
                    'tipo_establecimiento_id' => $tipo?->id,
                    'grado_complejidad_id' => $degree?->id,
                    'area_gestion_id' => $area?->id,
                    'nivel_atencion' => $this->value($data, ['NIVEL_DE_ATENCION', 'NIVEL_ATENCION']),
                    'prestador' => $this->value($data, ['PRESTADOR']),
                    'situacion_inmueble' => $this->value($data, ['SITUACION_INMUEBLE']),
                    'codigo_sih' => $sihCode,
                    'latitud' => $this->decimal($this->value($data, ['LATITUD', 'LAT']), 90),
                    'longitud' => $this->decimal($this->value($data, ['LONGITUD', 'LONG', 'LON']), 180),
                    'observacion' => $this->value($data, ['OBSERVACION', 'OBSERVACIONES']),
            ];
            if ($district) {
                $attributes['distrito_id'] = $district->id;
            }
            $system = $systems[$this->key($sihCode)] ?? $this->value($data, ['SISTEMA']);
            if ($system) {
                $attributes['sistema'] = $system;
            }
            Establecimiento::updateOrCreate(['codigo' => (string) $code], $attributes);
            $count++;
        }

        $spreadsheet->disconnectWorksheets();
        $this->command?->info("Maestro Bioestadística: {$count} establecimientos procesados.");
        $pending = Establecimiento::whereNull('distrito_id')->count();
        $this->command?->info("Distritos explícitos aplicados; {$pending} establecimientos quedan pendientes.");
    }

    private function readSystems(Worksheet $sheet): array
    {
        [$headerRow, $headers] = $this->findHeaders($sheet, 'CODIGO');
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

    private function findHeaders(Worksheet $sheet, string $required): array
    {
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        for ($row = 1; $row <= min(30, $sheet->getHighestDataRow()); $row++) {
            $headers = [];
            for ($column = 1; $column <= $highestColumn; $column++) {
                $headers[$column] = $this->key($sheet->getCell([$column, $row])->getFormattedValue());
            }
            if (in_array($required, $headers, true)) {
                return [$row, $headers];
            }
        }

        throw new \RuntimeException("No se encontró la columna {$required} en {$sheet->getTitle()}.");
    }

    private function row(Worksheet $sheet, int $row, array $headers): array
    {
        $data = [];
        foreach ($headers as $column => $header) {
            if ($header !== '') {
                $data[$header] = trim((string) $sheet->getCell([$column, $row])->getValue());
            }
        }

        return $data;
    }

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

    private function lookup(string $model, string $field, ?string $value): mixed
    {
        return $value ? $model::updateOrCreate([$field => $value], ['activo' => true]) : null;
    }

    private function resolveDistrict(string $departmentCode, ?string $districtName): ?Distrito
    {
        if (! $districtName) {
            return null;
        }

        $department = Departamento::where('codigo', $departmentCode)->first();
        if (! $department) {
            return null;
        }

        $wanted = $this->key($districtName);

        return Distrito::where('departamento_id', $department->id)
            ->get()
            ->first(function (Distrito $district) use ($wanted) {
                $candidate = $this->key($district->nombre);

                return $candidate === $wanted
                    || preg_replace('/^COLONIA_/', '', $candidate) === $wanted
                    || $candidate === preg_replace('/^COLONIA_/', '', $wanted);
            });
    }

    private function sourcePath(): ?string
    {
        $preferred = base_path('.docs-bio/ESTABLECIMIENTO_CON_ID 2.xlsx');
        $fallback = base_path('.docs-bio/ESTABLECIMIENTO_CON_ID.xlsx');

        if ($this->hasAtLeastEstablishments($preferred, 100)) {
            return $preferred;
        }
        if ($this->hasAtLeastEstablishments($fallback, 100)) {
            return $fallback;
        }

        return null;
    }

    private function hasAtLeastEstablishments(string $path, int $minimum): bool
    {
        if (! is_file($path)) {
            return false;
        }

        $book = IOFactory::createReaderForFile($path)->setReadDataOnly(true)->load($path);
        $sheet = $book->getSheetByName('DIM ESTABLECIMIENTOS') ?? $book->getActiveSheet();
        $count = 0;
        for ($row = 2; $row <= $sheet->getHighestDataRow() && $count < $minimum; $row++) {
            if (trim((string) $sheet->getCell([1, $row])->getValue()) !== '') {
                $count++;
            }
        }
        $book->disconnectWorksheets();

        return $count >= $minimum;
    }

    private function key(mixed $value): string
    {
        return trim(preg_replace('/[^A-Z0-9]+/', '_', Str::upper(Str::ascii((string) $value))), '_');
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
}
