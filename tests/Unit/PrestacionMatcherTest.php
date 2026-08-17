<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\PrestacionMatcher;
use PHPUnit\Framework\TestCase;

class PrestacionMatcherTest extends TestCase
{
    public function test_normalize_strips_accents_access_ids_and_punctuation(): void
    {
        $matcher = new PrestacionMatcher();

        self::assertSame(
            'CLINICA MEDICA',
            $matcher->normalize('Clínica Médica (12)')
        );
        self::assertSame(
            'CONSULTA PRENATAL',
            $matcher->normalize('  consulta   prenatal  ')
        );
    }
}
