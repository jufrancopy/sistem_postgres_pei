<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp13ProgramasDictionarySync;
use Illuminate\Database\Seeder;

/**
 * Revierte el sync de programas SP13 usando el snapshot guardado en storage.
 */
class BioestadisticaSp13ProgramasDictionaryRollbackSeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp13ProgramasDictionarySync::class);
        if (! $sync->snapshotExists()) {
            $this->command?->error('No hay snapshot en storage/app/bioestadistica/sp13_programas_snapshot.json');

            return;
        }

        if (! $sync->rollback()) {
            $this->command?->error('No se pudo restaurar el snapshot.');

            return;
        }

        // Tras restaurar diccionario, republicar formularios con la lógica vigente del seeder SP13.
        // Si también se revirtió el código de Sp13Seeder, volverá al layout anterior.
        $this->call([
            BioestadisticaSp12Seeder::class,
            BioestadisticaSp13Seeder::class,
        ]);

        $this->command?->info('Rollback de diccionario SP13/programas completado.');
        $this->command?->warn('Si el código de BioestadisticaSp13Seeder sigue con la allowlist nueva, revierta ese archivo en git y vuelva a ejecutar este rollback o Sp13Seeder.');
    }
}
