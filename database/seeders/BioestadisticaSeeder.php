<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BioestadisticaSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BioestadisticaRolesSeeder::class,
            BioestadisticaVariablesSeeder::class,
            BioestadisticaDistritosSeeder::class,
            BioestadisticaEstablecimientosSeeder::class,
            BioestadisticaFormulariosSeeder::class,
            BioestadisticaSp1Seeder::class,
            BioestadisticaIndicadoresSeeder::class,
            BioestadisticaReportesDashboardsSeeder::class,
            BioestadisticaHospitalizacionSeeder::class,
        ]);
    }
}
