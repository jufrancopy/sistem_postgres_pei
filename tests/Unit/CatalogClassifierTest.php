<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Dictionary\CatalogClassifier;
use App\Application\Bioestadistica\Dictionary\CatalogType;
use PHPUnit\Framework\TestCase;

class CatalogClassifierTest extends TestCase
{
    public function test_classifies_especialidad_medica_and_sp9_columns(): void
    {
        $classifier = new CatalogClassifier;

        $this->assertSame(
            CatalogType::EspecialidadMedica,
            $classifier->classify('1', 'CONSULTA POR ESPECIALIDAD', 'CARDIOLOGÍA')
        );

        $this->assertNull($classifier->classify('4', 'ATENCION DE URGENCIAS ADULTOS', 'CONSULTA DE URGENCIAS ADULTOS'));
        $this->assertTrue($classifier->isFormColumn('4', 'ATENCION DE URGENCIAS ADULTOS', 'CONSULTA DE URGENCIAS ADULTOS'));

        $this->assertSame(
            CatalogType::Vacuna,
            $classifier->classify('16', 'CLASIFICACION DE VACUNACION', 'BCG')
        );
    }
}
