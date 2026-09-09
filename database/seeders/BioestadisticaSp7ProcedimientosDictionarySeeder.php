<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp7ProcedimientosDictionarySync;
use Illuminate\Database\Seeder;

class BioestadisticaSp7ProcedimientosDictionarySeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp7ProcedimientosDictionarySync::class);
        $result = $sync->apply(forceSnapshot: ! $sync->snapshotExists());

        $this->call([
            BioestadisticaSp7Seeder::class,
        ]);

        $this->command?->info('Diccionario SP7/procedimientos aplicado.');
        $this->command?->line('  Snapshot: '.$result['snapshot']);
        $this->command?->line('  Grupos: '.count(Sp7ProcedimientosDictionarySync::SP7_TIPOS));
        $this->command?->line('  Puentes upsert: '.$result['bridges']);
        $this->command?->line('  Podados: '.$result['pruned']);
        $this->command?->warn('Para revertir: php artisan db:seed --class=BioestadisticaSp7ProcedimientosDictionaryRollbackSeeder');
    }
}
