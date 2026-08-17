<?php

namespace App\Application\Bioestadistica\Imports;

use App\Application\Bioestadistica\Hospitalization\HospitalizationService;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HospEpisodioImporter
{
    public function __construct(private HospitalizationService $hospitalization)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function import(string $path, User $user, ?int $importJobId = null): array
    {
        $book = IOFactory::createReaderForFile($path)->setReadDataOnly(true)->load($path);
        try {
            $sheet = $this->sheet($book);
            [$headerRow, $headers] = $this->headers($sheet);
            $created = 0;
            $updated = 0;
            $skipped = [];
            $periods = [];

            DB::transaction(function () use (
                $sheet, $headerRow, $headers, $user, $importJobId, &$created, &$updated, &$skipped, &$periods
            ) {
                for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
                    $raw = $this->row($sheet, $row, $headers);
                    if ($this->isEmpty($raw)) {
                        continue;
                    }
                    try {
                        $payload = $this->mapRow($raw);
                        $this->assertEstablishment((int) $payload['establecimiento_id'], $user);
                        $fingerprint = HospEpisodio::fingerprint($payload);
                        $existing = HospEpisodio::withTrashed()->where('source_fingerprint', $fingerprint)->first();
                        if ($existing?->trashed()) {
                            $existing->restore();
                        }
                        $payload['source_fingerprint'] = $fingerprint;
                        $payload['source_import_job_id'] = $importJobId;
                        $payload['source_row'] = $row;
                        $wasExisting = (bool) $existing;
                        $this->hospitalization->save($payload, $user, $existing, false);
                        $wasExisting ? $updated++ : $created++;
                        $period = HospEpisodio::periodFromDates($payload['fecha_ingreso'], $payload['fecha_egreso']);
                        $periods[$payload['establecimiento_id'].'-'.$period['periodo_anio'].'-'.$period['periodo_mes']] = [
                            'establecimiento_id' => $payload['establecimiento_id'],
                            ...$period,
                        ];
                    } catch (ValidationException $exception) {
                        $skipped[] = "Fila {$row}: ".$this->firstError($exception);
                    } catch (\Throwable $exception) {
                        $skipped[] = "Fila {$row}: ".$exception->getMessage();
                    }
                }
                foreach ($periods as $period) {
                    $this->hospitalization->consolidate(
                        (int) $period['establecimiento_id'],
                        (int) $period['periodo_anio'],
                        (int) $period['periodo_mes'],
                        $user
                    );
                }
            });

            return [
                'tipo' => 'hosp_episodios',
                'registros_creados' => $created,
                'registros_actualizados' => $updated,
                'filas_omitidas' => count($skipped),
                'periodos' => array_values($periods),
                'advertencias' => array_slice($skipped, 0, 100),
            ];
        } finally {
            $book->disconnectWorksheets();
        }
    }

    private function sheet($book): Worksheet
    {
        foreach ($book->getWorksheetIterator() as $sheet) {
            if (preg_match('/SP\s*10/i', $sheet->getTitle())) {
                return $sheet;
            }
        }

        return $book->getSheet(0);
    }

    /**
     * @return array{0:int,1:array<int,string>}
     */
    private function headers(Worksheet $sheet): array
    {
        $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        $lastRow = min(30, $sheet->getHighestDataRow());
        $bestRow = 1;
        $bestScore = -1;
        $best = [];
        for ($row = 1; $row <= $lastRow; $row++) {
            $headers = [];
            $score = 0;
            for ($column = 1; $column <= $lastColumn; $column++) {
                $value = trim((string) $sheet->getCell([$column, $row])->getFormattedValue());
                $headers[$column] = $value;
                if ($this->canonical($value)) {
                    $score++;
                }
            }
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRow = $row;
                $best = $headers;
            }
        }
        if ($bestScore < 3) {
            throw ValidationException::withMessages([
                'archivo' => 'No se identificaron las columnas nominativas de SP10 (cédula, ingreso, egreso).',
            ]);
        }

        return [$bestRow, $best];
    }

    /**
     * @param  array<int, string>  $headers
     * @return array<string, mixed>
     */
    private function row(Worksheet $sheet, int $row, array $headers): array
    {
        $values = [];
        foreach ($headers as $column => $header) {
            if ($header === '') {
                continue;
            }
            $cell = $sheet->getCell([$column, $row]);
            $value = $cell->getValue();
            if (ExcelDate::isDateTime($cell)) {
                $value = ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } else {
                $value = $cell->getFormattedValue();
            }
            $values[$header] = $value;
        }

        return $values;
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function mapRow(array $raw): array
    {
        $establishment = $this->value($raw, ['codigo_establecimiento', 'establecimiento', 'id_establecimiento', 'codigo']);
        $establecimiento = Establecimiento::where('codigo', (string) $establishment)->first()
            ?? Establecimiento::where('codigo_sih', (string) $establishment)->first();
        if (! $establecimiento) {
            throw ValidationException::withMessages([
                'establecimiento_id' => "Establecimiento «{$establishment}» no encontrado.",
            ]);
        }

        return [
            'establecimiento_id' => $establecimiento->id,
            'cedula' => $this->value($raw, ['cedula', 'documento', 'nro_documento', 'ci']),
            'sexo' => $this->value($raw, ['sexo', 'genero']),
            'seguro' => $this->value($raw, ['seguro', 'aseguradora']),
            'edad' => $this->value($raw, ['edad']),
            'fecha_ingreso' => $this->date($this->value($raw, ['fecha_ingreso', 'ingreso', 'f_ingreso'])),
            'fecha_egreso' => $this->date($this->value($raw, ['fecha_egreso', 'egreso', 'f_egreso', 'fecha_alta'])),
            'servicio' => $this->value($raw, ['servicio', 'especialidad', 'sala']),
            'diagnostico' => $this->value($raw, ['diagnostico', 'diagnostico_principal']),
            'cie10' => $this->value($raw, ['cie10', 'cie_10', 'codigo_cie']),
            'tipo_alta' => $this->value($raw, ['tipo_alta', 'condicion_egreso', 'egreso_tipo']),
            'cirugia' => $this->bool($this->value($raw, ['cirugia', 'intervencion'])),
            'tipo_cirugia' => $this->value($raw, ['tipo_cirugia']),
            'recien_nacido' => $this->bool($this->value($raw, ['recien_nacido', 'rn', 'nacimiento'])),
            'cesarea' => $this->bool($this->value($raw, ['cesarea', 'cesarea_si'])),
        ];
    }

    private function canonical(string $header): ?string
    {
        $key = Str::slug(Str::ascii($header), '_');
        $key = trim($key, '_');
        $map = [
            'cedula' => 'cedula', 'documento' => 'cedula', 'ci' => 'cedula',
            'sexo' => 'sexo', 'genero' => 'sexo',
            'fecha_ingreso' => 'fecha_ingreso', 'ingreso' => 'fecha_ingreso',
            'fecha_egreso' => 'fecha_egreso', 'egreso' => 'fecha_egreso',
            'servicio' => 'servicio',
            'cie10' => 'cie10', 'cie_10' => 'cie10',
            'establecimiento' => 'establecimiento', 'codigo_establecimiento' => 'establecimiento',
        ];

        return $map[$key] ?? null;
    }

    /**
     * @param  array<string, mixed>  $raw
     */
    private function value(array $raw, array $candidates): mixed
    {
        foreach ($raw as $header => $value) {
            $code = $this->canonical((string) $header) ?? strtolower((string) $header);
            if (in_array($code, $candidates, true) || in_array(strtolower((string) $header), $candidates, true)) {
                return is_string($value) ? trim($value) : $value;
            }
        }

        return null;
    }

    private function date(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value) && (float) $value > 20000) {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }
        $value = trim((string) $value);
        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y'] as $format) {
            $date = \DateTimeImmutable::createFromFormat($format, $value);
            if ($date && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }

        return $value;
    }

    private function bool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        $key = strtoupper(trim((string) $value));

        return in_array($key, ['1', 'SI', 'S', 'TRUE', 'VERDADERO', 'X'], true);
    }

    private function isEmpty(array $row): bool
    {
        return collect($row)->every(fn ($value) => trim((string) $value) === '');
    }

    private function assertEstablishment(int $id, User $user): void
    {
        if (Record::userHasGlobalAccess($user)) {
            return;
        }
        if (! in_array($id, Record::assignedEstablishmentIds($user), true)) {
            throw ValidationException::withMessages(['establecimiento_id' => 'Sin alcance para el establecimiento.']);
        }
    }

    private function firstError(ValidationException $exception): string
    {
        return collect($exception->errors())->flatten()->first() ?: $exception->getMessage();
    }
}
