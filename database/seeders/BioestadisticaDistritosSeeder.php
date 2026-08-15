<?php

namespace Database\Seeders;

use App\Models\Bioestadistica\Departamento;
use App\Models\Bioestadistica\Distrito;
use App\Models\Bioestadistica\Establecimiento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BioestadisticaDistritosSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('.docs-bio/codigo distrito.xlsx');
        if (! is_file($path)) {
            $this->command?->warn("No se encontró {$path}; se omite el catálogo de distritos.");

            return;
        }

        $spreadsheet = IOFactory::createReaderForFile($path)
            ->setReadDataOnly(true)
            ->load($path);
        $sheet = $spreadsheet->getActiveSheet();
        [$headerRow, $headers] = $this->findHeaders($sheet, 'ID_DISTRITO');

        $created = 0;
        $skippedForeign = 0;

        for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $data = $this->row($sheet, $row, $headers);
            $departmentCode = $this->value($data, ['ID_DPTO', 'ID_DEPTO']);
            $districtCode = $this->value($data, ['ID_DISTRITO']);
            $districtName = $this->value($data, ['DENOMINACION', 'DENOMINACIÓN', 'DISTRITO', 'NOMBRE']);
            $departmentLabel = $this->value($data, ['DEPARTAMENTO']);

            if (! $departmentCode || ! $districtName) {
                continue;
            }

            // Código 50 = EXTRANJERO (no aplica a la red IPS local).
            if ((string) $departmentCode === '50') {
                $skippedForeign++;
                continue;
            }

            $departmentName = $this->departmentName($departmentCode, $departmentLabel);
            $departamento = Departamento::updateOrCreate(
                ['codigo' => (string) $departmentCode],
                ['nombre' => $departmentName, 'activo' => true]
            );

            Distrito::updateOrCreate(
                [
                    'departamento_id' => $departamento->id,
                    'nombre' => $districtName,
                ],
                [
                    'codigo' => $districtCode !== null ? (string) $districtCode : null,
                    'activo' => true,
                ]
            );
            $created++;
        }

        $spreadsheet->disconnectWorksheets();
        $this->command?->info("Distritos Bioestadística: {$created} procesados" . ($skippedForeign ? " ({$skippedForeign} extranjeros omitidos)." : '.'));

        $assigned = $this->assignEstablecimientos();
        $this->command?->info("Asignación asistida: {$assigned} establecimientos enlazados a distrito.");
    }

    private function assignEstablecimientos(): int
    {
        $path = $this->establishmentSourcePath();
        if (! $path || ! is_file($path)) {
            return 0;
        }

        $spreadsheet = IOFactory::createReaderForFile($path)
            ->setReadDataOnly(true)
            ->load($path);
        $dimensionSheet = null;
        foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
            if (str_contains($this->key($sheet->getTitle()), 'DIM_ESTABLECIMIENTOS')) {
                $dimensionSheet = $sheet;
                break;
            }
        }
        if (! $dimensionSheet) {
            $spreadsheet->disconnectWorksheets();

            return 0;
        }

        [$headerRow, $headers] = $this->findHeaders($dimensionSheet, 'ID_ESTABLECIMIENTO');
        $departments = Departamento::pluck('id', 'codigo');
        $districtsByDepartment = Distrito::query()
            ->get(['id', 'departamento_id', 'nombre'])
            ->groupBy('departamento_id')
            ->map(fn ($group) => $group->sortByDesc(fn ($d) => mb_strlen($d->nombre))->values());

        $assigned = 0;
        for ($row = $headerRow + 1; $row <= $dimensionSheet->getHighestDataRow(); $row++) {
            $data = $this->row($dimensionSheet, $row, $headers);
            $code = $this->value($data, ['ID_ESTABLECIMIENTO', 'CODIGO_ESTABLECIMIENTO', 'ID']);
            $name = $this->value($data, ['ESTABLECIMIENTO', 'NOMBRE_ESTABLECIMIENTO']);
            $departmentCode = $this->value($data, ['ID_DEPTO', 'CODIGO_DEPARTAMENTO']);
            if (! $code || ! $name || ! $departmentCode) {
                continue;
            }

            if (in_array($this->key($this->value($data, ['DEPARTAMENTO', 'DPTO']) ?? ''), ['ASUNCION', 'CAPITAL'], true)) {
                $departmentCode = '18';
            }

            $departamentoId = $departments[(string) $departmentCode] ?? null;
            $establecimiento = Establecimiento::where('codigo', (string) $code)->first();
            if (! $departamentoId || ! $establecimiento) {
                continue;
            }

            $districtName = $this->value($data, ['DISTRITO']);
            $match = $districtName
                ? $this->matchDistrictName($districtName, $districtsByDepartment[$departamentoId] ?? collect())
                : $this->matchDistrict($name, $districtsByDepartment[$departamentoId] ?? collect());
            if (! $match) {
                continue;
            }

            if ($establecimiento->distrito_id !== $match->id) {
                $establecimiento->update(['distrito_id' => $match->id]);
                $assigned++;
            }
        }

        $spreadsheet->disconnectWorksheets();

        return $assigned;
    }

    private function matchDistrict(string $establishmentName, $districts)
    {
        $normalizedEstablishment = $this->key($establishmentName);
        $best = null;
        $bestScore = 0;

        foreach ($districts as $distrito) {
            $normalizedDistrict = $this->key($distrito->nombre);
            $aliases = array_unique(array_filter([
                $normalizedDistrict,
                preg_replace('/^COLONIA_/', '', $normalizedDistrict),
                preg_replace('/^CIUDAD_/', '', $normalizedDistrict),
            ]));

            foreach ($aliases as $alias) {
                if ($alias === '' || mb_strlen($alias) < 3) {
                    continue;
                }

                $score = 0;
                if ($normalizedEstablishment === $alias) {
                    $score = 100 + mb_strlen($alias);
                } elseif (str_contains($normalizedEstablishment, $alias)) {
                    $score = 50 + mb_strlen($alias);
                } elseif (str_contains($alias, $normalizedEstablishment) && mb_strlen($normalizedEstablishment) >= 5) {
                    $score = 40 + mb_strlen($normalizedEstablishment);
                }

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $best = $distrito;
                }
            }
        }

        return $bestScore >= 50 ? $best : null;
    }

    private function matchDistrictName(string $districtName, $districts)
    {
        $wanted = $this->key($districtName);

        return $districts->first(function ($district) use ($wanted) {
            $candidate = $this->key($district->nombre);

            return $candidate === $wanted
                || preg_replace('/^COLONIA_/', '', $candidate) === $wanted
                || $candidate === preg_replace('/^COLONIA_/', '', $wanted);
        });
    }

    private function establishmentSourcePath(): ?string
    {
        $preferred = base_path('.docs-bio/ESTABLECIMIENTO_CON_ID 2.xlsx');
        $fallback = base_path('.docs-bio/ESTABLECIMIENTO_CON_ID.xlsx');

        foreach ([$preferred, $fallback] as $path) {
            if (! is_file($path)) {
                continue;
            }
            $book = IOFactory::createReaderForFile($path)->setReadDataOnly(true)->load($path);
            $sheet = $book->getSheetByName('DIM ESTABLECIMIENTOS') ?? $book->getActiveSheet();
            $count = 0;
            for ($row = 2; $row <= $sheet->getHighestDataRow() && $count < 100; $row++) {
                if (trim((string) $sheet->getCell([1, $row])->getValue()) !== '') {
                    $count++;
                }
            }
            $book->disconnectWorksheets();
            if ($count >= 100) {
                return $path;
            }
        }

        return null;
    }

    private function departmentName(string $code, ?string $label): string
    {
        $existing = Departamento::where('codigo', $code)->value('nombre');
        if ($existing) {
            return $existing;
        }

        $fromLabel = $label ? trim((string) preg_replace('/^\d+\s*-\s*/', '', $label)) : null;
        if ($code === '18') {
            return 'ASUNCIÓN';
        }
        if ($fromLabel && $this->key($fromLabel) === 'NEMBUCU') {
            return 'ÑEEMBUCU';
        }

        return $fromLabel ?: "Departamento {$code}";
    }

    private function findHeaders(Worksheet $sheet, string $required): array
    {
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        for ($row = 1; $row <= min(30, $sheet->getHighestDataRow()); $row++) {
            $headers = [];
            for ($column = 1; $column <= $highestColumn; $column++) {
                $headers[$column] = $this->key($sheet->getCell([$column, $row])->getFormattedValue());
            }
            if (in_array($required, $headers, true) || in_array('DENOMINACION', $headers, true)) {
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
                $data[$header] = trim((string) $sheet->getCell([$column, $row])->getFormattedValue());
            }
        }

        return $data;
    }

    private function value(array $data, array $aliases): ?string
    {
        foreach ($aliases as $alias) {
            $value = trim((string) ($data[$this->key($alias)] ?? $data[$alias] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function key(mixed $value): string
    {
        return trim(preg_replace('/[^A-Z0-9]+/', '_', Str::upper(Str::ascii((string) $value))), '_');
    }
}
