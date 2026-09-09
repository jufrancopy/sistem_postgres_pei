<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp12ProgramasDictionarySync;
use Illuminate\Database\Seeder;

class BioestadisticaSp12ProgramasDictionarySeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp12ProgramasDictionarySync::class);
        $result = $sync->apply(forceSnapshot: ! $sync->snapshotExists());

        $this->call([
            BioestadisticaSp12Seeder::class,
        ]);

        $this->command?->info('Diccionario SP12/programas aplicado.');
        $this->command?->line('  Snapshot: '.$result['snapshot']);
        $this->command?->line('  Tipos SP12: '.count(Sp12ProgramasDictionarySync::SP12_TIPOS));
        $this->command?->line('  Puentes upsert: '.$result['bridges']);
        $this->command?->line('  Deduplicados/podados: '.$result['deduped']);
        $this->command?->warn('Para revertir: php artisan db:seed --class=BioestadisticaSp12ProgramasDictionaryRollbackSeeder');
    }
}
