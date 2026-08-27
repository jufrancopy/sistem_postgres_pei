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

    public static function lastClosed(): array
    {
        $date = now()->subMonthNoOverflow();

        return ['anio' => (int) $date->year, 'mes' => (int) $date->month];
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
        if (! is_numeric($year) || ! is_numeric($month) || $year < 1990 || $year > 2100 || $month < 1 || $month > 12) {
            return $fallback ?? self::lastClosed();
        }

        return ['anio' => (int) $year, 'mes' => (int) $month];
    }
}
