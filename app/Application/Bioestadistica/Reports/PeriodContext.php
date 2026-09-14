<?php

namespace App\Application\Bioestadistica\Reports;

use Carbon\CarbonImmutable;

class PeriodContext
{
    public const MONTHS = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    /** Años hacia atrás desde el calendario actual (incluido). */
    public const SELECTABLE_YEARS_BACK = 5;

    public static function lastClosed(): array
    {
        $date = now()->subMonthNoOverflow();

        return ['anio' => (int) $date->year, 'mes' => (int) $date->month];
    }

    public static function maxSelectableYear(?int $referenceYear = null): int
    {
        return $referenceYear ?? (int) now()->year;
    }

    public static function minSelectableYear(?int $referenceYear = null): int
    {
        return self::maxSelectableYear($referenceYear) - self::SELECTABLE_YEARS_BACK;
    }

    /**
     * Años para desplegables (descendente). Incluye un año fuera de rango si hace falta (registros viejos).
     *
     * @return list<int>
     */
    public static function selectableYears(?int $includeYear = null, ?int $referenceYear = null): array
    {
        $max = self::maxSelectableYear($referenceYear);
        $min = self::minSelectableYear($referenceYear);
        $years = range($max, $min);
        $include = $includeYear !== null ? (int) $includeYear : 0;
        if ($include >= 1990 && $include <= 2100 && ! in_array($include, $years, true)) {
            $years[] = $include;
            rsort($years, SORT_NUMERIC);
        }

        return array_values($years);
    }

    /**
     * @return list<string>
     */
    public static function yearValidationRules(bool $required = true): array
    {
        return [
            $required ? 'required' : 'nullable',
            'integer',
            'between:'.self::minSelectableYear().','.self::maxSelectableYear(),
        ];
    }

    public static function isSelectableYear(int $year): bool
    {
        return $year >= self::minSelectableYear() && $year <= self::maxSelectableYear();
    }

    public static function monthsBack(array $until, int $months): array
    {
        $date = CarbonImmutable::create($until['anio'], $until['mes'], 1)
            ->subMonthsNoOverflow(max(0, $months - 1));

        return ['anio' => (int) $date->year, 'mes' => (int) $date->month];
    }

    public static function label(array $from, array $to): string
    {
        $fromLabel = (self::MONTHS[$from['mes']] ?? $from['mes']).' '.$from['anio'];
        $toLabel = (self::MONTHS[$to['mes']] ?? $to['mes']).' '.$to['anio'];

        return $from === $to ? $fromLabel : "{$fromLabel} — {$toLabel}";
    }

    /** Último día del mes estadístico como dd/mm/yyyy (p. ej. 31/01/2026). */
    public static function endOfMonthDate(array $period): string
    {
        return CarbonImmutable::create((int) $period['anio'], (int) $period['mes'], 1)
            ->endOfMonth()
            ->format('d/m/Y');
    }

    public static function normalize(?array $value, ?array $fallback = null): array
    {
        $year = $value['anio'] ?? $value[0] ?? null;
        $month = $value['mes'] ?? $value[1] ?? null;
        if (
            ! is_numeric($year)
            || ! is_numeric($month)
            || ! self::isSelectableYear((int) $year)
            || $month < 1
            || $month > 12
        ) {
            return $fallback ?? self::lastClosed();
        }

        return ['anio' => (int) $year, 'mes' => (int) $month];
    }
}
