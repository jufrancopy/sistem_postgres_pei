<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp2EnfermeriaPlanillaDictionarySync;
use Illuminate\Database\Seeder;

/**
 * Agrega LAVADO DE OIDO, OBSTETRICIA, SUTURA y TEST DEL PIECITO al dominio 13
 * (sin OTROS) y republica SP2. Guarda snapshot para rollback.
 */
class BioestadisticaSp2EnfermeriaPlanillaDictionarySeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp2EnfermeriaPlanillaDictionarySync::class);
        $result = $sync->apply(forceSnapshot: ! $sync->snapshotExists());

        $this->call([
            BioestadisticaSp2Seeder::class,
        ]);

        $this->command?->info('Diccionario SP2/enfermería planilla aplicado (ítems interior + previos, sin OTROS).');
        $this->command?->line('  Snapshot: '.$result['snapshot']);
        $this->command?->line('  Puentes upsert: '.$result['bridges']);
        $this->command?->line('  Puentes nuevos: '.$result['created_bridges']);
        $this->command?->line('  Tipos nuevos: '.$result['created_detalles']);
        foreach (Sp2EnfermeriaPlanillaDictionarySync::ADDITIONS as $row) {
            $this->command?->line('  + '.$row['prestacion'].' → '.$row['tipo']);
        }
        $this->command?->warn('Para revertir: php artisan db:seed --class=BioestadisticaSp2EnfermeriaPlanillaDictionaryRollbackSeeder');
    }
}
