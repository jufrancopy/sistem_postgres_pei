<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp2EnfermeriaPlanillaDictionarySync;
use Illuminate\Database\Seeder;

/**
 * Revierte el sync de ítems planilla SP2 usando el snapshot en storage.
 */
class BioestadisticaSp2EnfermeriaPlanillaDictionaryRollbackSeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp2EnfermeriaPlanillaDictionarySync::class);
        if (! $sync->snapshotExists()) {
            $this->command?->error('No hay snapshot en storage/app/bioestadistica/sp2_enfermeria_planilla_snapshot.json');

            return;
        }

        if (! $sync->rollback()) {
            $this->command?->error('No se pudo restaurar el snapshot.');

            return;
        }

        $this->call([
            BioestadisticaSp2Seeder::class,
        ]);

        $this->command?->info('Rollback de diccionario SP2/enfermería planilla completado.');
    }
}
