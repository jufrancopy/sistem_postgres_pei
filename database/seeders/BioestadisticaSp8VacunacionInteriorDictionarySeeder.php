<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\Sp8VacunacionInteriorDictionarySync;
use Illuminate\Database\Seeder;

/**
 * Puebla clasificación de beneficiarios (filas) y alias de vacunas; republica SP8.
 */
class BioestadisticaSp8VacunacionInteriorDictionarySeeder extends Seeder
{
    public function run(): void
    {
        $sync = app(Sp8VacunacionInteriorDictionarySync::class);
        $result = $sync->apply();

        $this->call([
            BioestadisticaSp8Seeder::class,
        ]);

        $this->command?->info('Diccionario SP8 interior aplicado (clasificación + alias vacunas).');
        $this->command?->line('  Puentes upsert: '.$result['bridges']);
        $this->command?->line('  Puentes nuevos: '.$result['created_bridges']);
        $this->command?->line('  Tipos nuevos: '.$result['created_detalles']);
        $this->command?->line('  Layout beneficiarios: '.($result['layout_updated'] ? 'tabla' : 'sin cambio'));
        foreach (Sp8VacunacionInteriorDictionarySync::CLASIFICACION_ITEMS as $item) {
            $this->command?->line('  + clasificación: '.$item);
        }
        foreach (Sp8VacunacionInteriorDictionarySync::VACUNA_ALIASES as $item) {
            $this->command?->line('  + vacuna: '.$item);
        }
    }
}
