<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Sp11Matrix;
use PHPUnit\Framework\TestCase;

class Sp11MatrixTest extends TestCase
{
    public function test_leap_year_february_has_29_days(): void
    {
        self::assertSame(29, Sp11Matrix::daysInPeriod(2024, 2));
        self::assertSame(28, Sp11Matrix::daysInPeriod(2025, 2));
        self::assertSame(31, Sp11Matrix::daysInPeriod(2026, 7));
    }

    public function test_normalizes_totals_and_canonical_rows(): void
    {
        $payload = Sp11Matrix::normalize([
            'rows' => [
                'pacientes_dia' => ['1' => 10, '2' => 5],
                'camas_operativas' => ['1' => 20, '2' => 20],
            ],
        ], 2026, 2, true);

        self::assertSame(15, $payload['rows']['pacientes_dia']['total']);
        self::assertSame(40, Sp11Matrix::total($payload, 'camas_operativas'));
        self::assertSame('pacientes_dia', Sp11Matrix::canonicalRow('Paciente día'));
        self::assertSame('obitos', Sp11Matrix::canonicalRow('Óbitos'));
    }
}
