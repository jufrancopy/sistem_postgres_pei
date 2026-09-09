<?php

namespace Tests\Unit;

use App\Models\Bioestadistica\HospEpisodio;
use PHPUnit\Framework\TestCase;

class HospEpisodioDomainTest extends TestCase
{
    public function test_period_uses_discharge_date_when_present(): void
    {
        $period = HospEpisodio::periodFromDates('2026-06-28', '2026-07-02');

        self::assertSame(2026, $period['periodo_anio']);
        self::assertSame(7, $period['periodo_mes']);
    }

    public function test_period_falls_back_to_admission_when_still_hospitalized(): void
    {
        $period = HospEpisodio::periodFromDates('2026-08-15', null);

        self::assertSame(2026, $period['periodo_anio']);
        self::assertSame(8, $period['periodo_mes']);
    }

    public function test_stay_counts_admission_day_and_minimum_one(): void
    {
        self::assertSame(3, HospEpisodio::calculateStayDays('2026-07-01', '2026-07-04'));
        self::assertSame(1, HospEpisodio::calculateStayDays('2026-07-01', '2026-07-01'));
        self::assertNull(HospEpisodio::calculateStayDays('2026-07-01', null));
    }

    public function test_mask_hides_all_but_last_three_digits(): void
    {
        self::assertSame('••••••321', HospEpisodio::maskCedula('123456321'));
        self::assertSame('123', HospEpisodio::maskCedula('123'));
    }

    public function test_cie10_and_canonical_values(): void
    {
        self::assertSame('A09.0', HospEpisodio::normalizeCie10('a09.0'));
        self::assertTrue(HospEpisodio::isValidCie10('J18.9'));
        self::assertFalse(HospEpisodio::isValidCie10('18J'));
        self::assertSame('FALLECIDO', HospEpisodio::normalizeTipoAlta('Fallecido'));
        self::assertSame('MATERNIDAD', HospEpisodio::normalizeServicio('Obstetricia'));
        self::assertSame('F', HospEpisodio::normalizeSexo('femenino'));
        self::assertSame('MAYOR', HospEpisodio::normalizeTipoCirugia('Cirugía mayor'));
        self::assertSame('MENOR', HospEpisodio::normalizeTipoCirugia('menor'));
    }
}
