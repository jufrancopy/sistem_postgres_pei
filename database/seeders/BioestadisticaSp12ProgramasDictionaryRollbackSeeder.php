<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp12ProgramasDictionarySync;
use Illuminate\Database\Seeder;

class BioestadisticaSp12ProgramasDictionaryRollbackSeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp12ProgramasDictionarySync::class);
        if (! $sync->snapshotExists()) {
            $this->command?->error('No hay snapshot en storage/app/bioestadistica/sp12_programas_snapshot.json');

            return;
        }

        if (! $sync->rollback()) {
            $this->command?->error('No se pudo restaurar el snapshot SP12.');

            return;
        }

        $this->call([
            BioestadisticaSp12Seeder::class,
        ]);

        $this->command?->info('Rollback de diccionario SP12/programas completado.');
        $this->command?->warn('Si el código de BioestadisticaSp12Seeder sigue con la allowlist nueva, revierta ese archivo en git y vuelva a ejecutar este rollback o Sp12Seeder.');
    }
}
