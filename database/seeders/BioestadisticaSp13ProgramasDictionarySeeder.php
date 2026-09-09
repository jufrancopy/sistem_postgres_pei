<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp13ProgramasDictionarySync;
use Illuminate\Database\Seeder;

/**
 * Aplica el catálogo SP13 (planilla Formularios) al dominio 17 y republica SP12/SP13.
 * Guarda snapshot en storage para rollback.
 */
class BioestadisticaSp13ProgramasDictionarySeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp13ProgramasDictionarySync::class);
        $result = $sync->apply(forceSnapshot: ! $sync->snapshotExists());

        $this->call([
            BioestadisticaSp12Seeder::class,
            BioestadisticaSp13Seeder::class,
        ]);

        $this->command?->info('Diccionario SP13/programas aplicado.');
        $this->command?->line('  Snapshot: '.$result['snapshot']);
        $this->command?->line('  Tipos SP13 objetivo: '.count(Sp13ProgramasDictionarySync::SP13_TIPOS));
        $this->command?->line('  Puentes upsert: '.$result['bridges']);
        $this->command?->line('  Puentes deduplicados: '.$result['deduped']);
        $this->command?->warn('Para revertir: php artisan db:seed --class=BioestadisticaSp13ProgramasDictionaryRollbackSeeder');
    }
}
