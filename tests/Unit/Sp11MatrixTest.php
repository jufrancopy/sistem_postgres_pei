<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Sp11Matrix;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class Sp11MatrixTest extends TestCase
{
    public function test_leap_year_february_has_29_days(): void
    {
        self::assertSame(29, Sp11Matrix::daysInPeriod(2024, 2));
        self::assertSame(28, Sp11Matrix::daysInPeriod(2025, 2));
        self::assertSame(31, Sp11Matrix::daysInPeriod(2026, 7));
    }

    public function test_normalizes_computed_rows_and_canonical_aliases(): void
    {
        $payload = Sp11Matrix::normalize([
            'rows' => [
                'principio_dia' => ['1' => 10, '2' => 12],
                'ingresos' => ['1' => 3, '2' => 1],
                'altas' => ['1' => 1, '2' => 2],
                'traslados' => ['1' => 0],
                'obitos' => ['1' => 0],
                'abandono' => ['2' => 1],
            ],
        ], 2026, 2, true);

        self::assertSame(1, $payload['rows']['total_egresos']['1']);
        self::assertSame(3, $payload['rows']['total_egresos']['2']);
        self::assertSame(12, $payload['rows']['total_pacientes_dia']['1']);
        self::assertSame(10, $payload['rows']['total_pacientes_dia']['2']);
        self::assertSame(4, Sp11Matrix::total($payload, 'total_egresos'));
        self::assertSame(22, Sp11Matrix::total($payload, 'pacientes_dia'));
        self::assertSame('principio_dia', Sp11Matrix::canonicalRow('Principio del día'));
        self::assertSame('total_pacientes_dia', Sp11Matrix::canonicalRow('Paciente día'));
        self::assertSame('obitos', Sp11Matrix::canonicalRow('Óbitos'));
        self::assertSame('altas', Sp11Matrix::canonicalRow('Altas'));
    }

    public function test_rejects_negative_pacientes_dia_in_strict_mode(): void
    {
        $this->expectException(ValidationException::class);

        Sp11Matrix::normalize([
            'rows' => [
                'principio_dia' => ['1' => 1],
                'ingresos' => ['1' => 0],
                'altas' => ['1' => 5],
            ],
        ], 2026, 7, true);
    }
}
