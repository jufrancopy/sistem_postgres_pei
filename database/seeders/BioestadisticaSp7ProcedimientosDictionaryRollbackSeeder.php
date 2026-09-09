<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp7ProcedimientosDictionarySync;
use Illuminate\Database\Seeder;

class BioestadisticaSp7ProcedimientosDictionaryRollbackSeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp7ProcedimientosDictionarySync::class);
        if (! $sync->snapshotExists()) {
            $this->command?->error('No hay snapshot en storage/app/bioestadistica/sp7_procedimientos_snapshot.json');

            return;
        }

        if (! $sync->rollback()) {
            $this->command?->error('No se pudo restaurar el snapshot SP7.');

            return;
        }

        $this->call([
            BioestadisticaSp7Seeder::class,
        ]);

        $this->command?->info('Rollback de diccionario SP7/procedimientos completado.');
        $this->command?->warn('Si el código de BioestadisticaSp7Seeder sigue con la allowlist nueva, revierta ese archivo en git y vuelva a ejecutar este rollback o Sp7Seeder.');
    }
}
