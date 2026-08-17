<?php

namespace App\Application\Bioestadistica;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Sp11Matrix
{
    public const ROWS = [
        'ingresos' => 'Ingresos',
        'egresos' => 'Egresos',
        'obitos' => 'Óbitos',
        'pacientes_dia' => 'Pacientes día',
        'camas_disponibles' => 'Camas disponibles',
        'camas_operativas' => 'Camas operativas',
    ];

    public static function canonicalRow(string $label): string
    {
        $key = Str::slug(Str::ascii($label), '_');
        $aliases = [
            'paciente_dia' => 'pacientes_dia',
            'pacientes_dia' => 'pacientes_dia',
            'camas_operativa' => 'camas_operativas',
            'camas_operativas' => 'camas_operativas',
            'camas_disponible' => 'camas_disponibles',
            'camas_disponibles' => 'camas_disponibles',
            'obito' => 'obitos',
            'obitos' => 'obitos',
            'fallecidos' => 'obitos',
            'ingreso' => 'ingresos',
            'ingresos' => 'ingresos',
            'egreso' => 'egresos',
            'egresos' => 'egresos',
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

        $rows = [];
        foreach ($incoming as $rowKey => $cells) {
            $code = self::canonicalRow((string) $rowKey);
            if (! is_array($cells)) {
                throw ValidationException::withMessages(['value' => "La fila «{$code}» tiene un formato inválido."]);
            }
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
                            'value' => "«{$code}» contiene el día inválido «{$key}» para {$month}/{$year}.",
                        ]);
                    }
                }
            }
            $row['total'] = $sum;
            $rows[$code] = $row;
        }

        if ($strict) {
            foreach (['pacientes_dia', 'camas_operativas'] as $required) {
                if (! isset($rows[$required])) {
                    throw ValidationException::withMessages([
                        'value' => "La matriz SP11 requiere la fila «{$required}».",
                    ]);
                }
            }
        }

        return ['rows' => $rows];
    }

    public static function total(array $payload, string $row): int
    {
        $rows = $payload['rows'] ?? $payload;

        return (int) ($rows[$row]['total'] ?? 0);
    }

    /**
     * @return array<string, string>
     */
    public static function defaultConfig(): array
    {
        return [
            'rows' => array_keys(self::ROWS),
            'row_labels' => array_values(self::ROWS),
            'cols' => 'dias_mes',
            'col_count' => 31,
            'totals' => true,
            'contract' => 'sp11_v1',
        ];
    }
}
