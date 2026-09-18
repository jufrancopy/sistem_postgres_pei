<?php

namespace App\Application\Bioestadistica;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Sp11Matrix
{
    /** Filas editables en orden de planilla (sin el grupo visual EGRESOS). */
    public const EDITABLE_ROWS = [
        'principio_dia' => 'Principio del día',
        'ingresos' => 'Ingresos',
        'altas' => 'Altas',
        'traslados' => 'Traslados',
        'obitos' => 'Óbitos',
        'abandono' => 'Abandono',
    ];

    /** Filas derivadas (solo lectura). */
    public const COMPUTED_ROWS = [
        'total_egresos' => 'Total egresos',
        'total_pacientes_dia' => 'Total pacientes día',
    ];

    /** Subfilas del bloque EGRESOS. */
    public const EGRESO_ROWS = ['altas', 'traslados', 'obitos', 'abandono'];

    /**
     * Orden de visualización / persistencia de todas las filas.
     *
     * @var array<string, string>
     */
    public const ROWS = [
        'principio_dia' => 'Principio del día',
        'ingresos' => 'Ingresos',
        'altas' => 'Altas',
        'traslados' => 'Traslados',
        'obitos' => 'Óbitos',
        'abandono' => 'Abandono',
        'total_egresos' => 'Total egresos',
        'total_pacientes_dia' => 'Total pacientes día',
    ];

    public static function canonicalRow(string $label): string
    {
        $key = Str::slug(Str::ascii($label), '_');
        $aliases = [
            'principio_del_dia' => 'principio_dia',
            'principio_dia' => 'principio_dia',
            'principio' => 'principio_dia',
            'ingreso' => 'ingresos',
            'ingresos' => 'ingresos',
            'alta' => 'altas',
            'altas' => 'altas',
            'traslado' => 'traslados',
            'traslados' => 'traslados',
            'obito' => 'obitos',
            'obitos' => 'obitos',
            'fallecidos' => 'obitos',
            'abandono' => 'abandono',
            'total_egresos' => 'total_egresos',
            'egresos' => 'total_egresos',
            'egreso' => 'total_egresos',
            'total_pacientes_dia' => 'total_pacientes_dia',
            'pacientes_dia' => 'total_pacientes_dia',
            'paciente_dia' => 'total_pacientes_dia',
            'total_pacientes' => 'total_pacientes_dia',
        ];
        foreach ($aliases as $needle => $code) {
            if ($key === $needle || str_contains($key, $needle)) {
                return $code;
            }
        }

        return $key !== '' ? $key : 'fila';
    }

    public static function daysInPeriod(int $year, int $month): int
    {
        return CarbonImmutable::create($year, $month, 1)->daysInMonth;
    }

    public static function isEditable(string $code): bool
    {
        return array_key_exists($code, self::EDITABLE_ROWS);
    }

    public static function isComputed(string $code): bool
    {
        return array_key_exists($code, self::COMPUTED_ROWS);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{rows: array<string, array<string, int>>}
     */
    public static function normalize(array $payload, int $year, int $month, bool $strict = true): array
    {
        $days = self::daysInPeriod($year, $month);
        $incoming = $payload['rows'] ?? $payload;
        if (! is_array($incoming)) {
            throw ValidationException::withMessages(['value' => 'La matriz SP11 debe enviar filas válidas.']);
        }

        $editable = [];
        foreach ($incoming as $rowKey => $cells) {
            $code = self::canonicalRow((string) $rowKey);
            if (self::isComputed($code)) {
                continue;
            }
            if (! self::isEditable($code)) {
                if ($strict) {
                    throw ValidationException::withMessages([
                        'value' => "La fila «{$code}» no forma parte de la planilla SP11.",
                    ]);
                }
                continue;
            }
            if (! is_array($cells)) {
                throw ValidationException::withMessages(['value' => "La fila «{$code}» tiene un formato inválido."]);
            }
            $editable[$code] = self::normalizeEditableRow($code, $cells, $days, $strict);
        }

        $rows = [];
        foreach (array_keys(self::EDITABLE_ROWS) as $code) {
            $rows[$code] = $editable[$code] ?? self::emptyRow();
        }

        $hasDayBreakdown = self::matrixHasDayBreakdown($rows);

        $totalEgresos = self::emptyRow();
        $totalPacientes = self::emptyRow();
        $sumEgresos = 0;
        $sumPacientes = 0;

        if ($hasDayBreakdown) {
            for ($day = 1; $day <= $days; $day++) {
                $key = (string) $day;
                $egresos = 0;
                foreach (self::EGRESO_ROWS as $code) {
                    $egresos += (int) ($rows[$code][$key] ?? 0);
                }
                $principio = (int) ($rows['principio_dia'][$key] ?? 0);
                $ingresos = (int) ($rows['ingresos'][$key] ?? 0);
                $pacientes = $principio + $ingresos - $egresos;
                if ($strict && $pacientes < 0) {
                    throw ValidationException::withMessages([
                        'value' => "El día {$day} produce pacientes día negativos (principio + ingresos − egresos).",
                    ]);
                }
                if ($egresos > 0 || self::rowHasDay($rows, $key)) {
                    $totalEgresos[$key] = $egresos;
                    $sumEgresos += $egresos;
                }
                if ($pacientes !== 0 || self::rowHasDay($rows, $key)) {
                    $totalPacientes[$key] = $strict ? $pacientes : max(0, $pacientes);
                    $sumPacientes += (int) $totalPacientes[$key];
                }
            }
            $totalEgresos['total'] = $sumEgresos;
            $totalPacientes['total'] = $sumPacientes;
        } else {
            // Solo totales del mes (sin desglose diario).
            foreach (self::EGRESO_ROWS as $code) {
                $sumEgresos += (int) ($rows[$code]['total'] ?? 0);
            }
            $sumPacientes = (int) ($rows['principio_dia']['total'] ?? 0)
                + (int) ($rows['ingresos']['total'] ?? 0)
                - $sumEgresos;
            if ($strict && $sumPacientes < 0) {
                throw ValidationException::withMessages([
                    'value' => 'Los totales del mes producen pacientes día negativos (principio + ingresos − egresos).',
                ]);
            }
            $totalEgresos['total'] = $sumEgresos;
            $totalPacientes['total'] = $strict ? $sumPacientes : max(0, $sumPacientes);
        }

        $rows['total_egresos'] = $totalEgresos;
        $rows['total_pacientes_dia'] = $totalPacientes;

        return ['rows' => $rows];
    }

    public static function total(array $payload, string $row): int
    {
        $rows = $payload['rows'] ?? $payload;
        $code = self::canonicalRow($row);

        return (int) ($rows[$code]['total'] ?? 0);
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultConfig(): array
    {
        return [
            'rows' => array_keys(self::ROWS),
            'row_labels' => array_values(self::ROWS),
            'editable_rows' => array_keys(self::EDITABLE_ROWS),
            'computed_rows' => array_keys(self::COMPUTED_ROWS),
            'egreso_rows' => self::EGRESO_ROWS,
            'cols' => 'dias_mes',
            'col_count' => 31,
            'totals' => true,
            'contract' => 'sp11_v1',
        ];
    }

    /**
     * @param  array<string, mixed>  $cells
     * @return array<string, int>
     */
    private static function normalizeEditableRow(string $code, array $cells, int $days, bool $strict): array
    {
        $row = [];
        $sum = 0;
        for ($day = 1; $day <= $days; $day++) {
            $raw = $cells[$day] ?? $cells[(string) $day] ?? null;
            if ($raw === null || $raw === '') {
                continue;
            }
            if (! is_numeric($raw) || filter_var($raw, FILTER_VALIDATE_INT) === false || (int) $raw < 0) {
                throw ValidationException::withMessages([
                    'value' => "«{$code}» día {$day} debe ser un entero mayor o igual a 0.",
                ]);
            }
            $row[(string) $day] = (int) $raw;
            $sum += (int) $raw;
        }
        if ($strict) {
            foreach (array_keys($cells) as $key) {
                if ($key === 'total') {
                    continue;
                }
                if (! ctype_digit((string) $key) || (int) $key < 1 || (int) $key > $days) {
                    throw ValidationException::withMessages([
                        'value' => "«{$code}» contiene el día inválido «{$key}».",
                    ]);
                }
            }
        }

        $manualTotal = null;
        if (isset($cells['total']) && $cells['total'] !== '') {
            if (! is_numeric($cells['total']) || filter_var($cells['total'], FILTER_VALIDATE_INT) === false || (int) $cells['total'] < 0) {
                throw ValidationException::withMessages([
                    'value' => "«{$code}» total debe ser un entero mayor o igual a 0.",
                ]);
            }
            $manualTotal = (int) $cells['total'];
        }

        if ($sum > 0) {
            // Desglose diario manda: el total se recalcula.
            $row['total'] = $sum;
        } elseif ($manualTotal !== null) {
            $row['total'] = $manualTotal;
        } else {
            $row['total'] = 0;
        }

        return $row;
    }

    private static function emptyRow(): array
    {
        return ['total' => 0];
    }

    /**
     * @param  array<string, array<string, int>>  $rows
     */
    private static function rowHasDay(array $rows, string $day): bool
    {
        foreach (array_keys(self::EDITABLE_ROWS) as $code) {
            if (isset($rows[$code][$day])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, array<string, int>>  $rows
     */
    private static function matrixHasDayBreakdown(array $rows): bool
    {
        foreach (array_keys(self::EDITABLE_ROWS) as $code) {
            foreach ($rows[$code] ?? [] as $key => $value) {
                if ($key === 'total') {
                    continue;
                }
                if (ctype_digit((string) $key) && (int) $value !== 0) {
                    return true;
                }
            }
        }

        return false;
    }
}
