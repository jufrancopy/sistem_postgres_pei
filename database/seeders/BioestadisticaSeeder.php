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
            BioestadisticaSp2EnfermeriaPlanillaDictionarySeeder::class,
            BioestadisticaSp3Seeder::class,
            BioestadisticaSp4Seeder::class,
            BioestadisticaSp5Seeder::class,
            BioestadisticaSp6Seeder::class,
            BioestadisticaSp7Seeder::class,
            BioestadisticaSp8VacunacionInteriorDictionarySeeder::class,
            BioestadisticaSp9Seeder::class,
            BioestadisticaSp12Seeder::class,
            BioestadisticaSp13Seeder::class,
            BioestadisticaSp14Seeder::class,
            BioestadisticaFormulariosSnapshotSeeder::class,
            BioestadisticaFormulariosSpSeeder::class,
            BioestadisticaIndicadoresSeeder::class,
            BioestadisticaReportesDashboardsSeeder::class,
            BioestadisticaHospitalizacionSeeder::class,
            BioestadisticaOrganosSeeder::class,
        ]);
    }
}
