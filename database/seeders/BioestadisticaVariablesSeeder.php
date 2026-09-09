<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Imports\VariablesSaludImporter;
use Illuminate\Database\Seeder;

class BioestadisticaVariablesSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('.docs-bio/variables salud.xlsx');
        if (! is_file($path)) {
            $path = base_path('.docs-bio/variables salud.xls');
        }
        if (! is_file($path)) {
            $this->command?->warn('No se encontró variables salud.xlsx ni .xls; se omite el diccionario.');

            return;
        }

        $summary = (new VariablesSaludImporter)->import($path);

        $omitidos = $summary['omitidos'] ?? [];
        $this->command?->info(sprintf(
            'Diccionario: %d ítems importados (%d variables nuevas, %d detalles, %d ítems de catálogo). Omitidos: dominio x=%d, filas amarillas=%d, duplicados=%d.',
            $summary['procesados'] ?? 0,
            $summary['creados']['variables'] ?? 0,
            $summary['creados']['variable_detalles'] ?? 0,
            $summary['creados']['prestaciones'] ?? 0,
            $omitidos['dominio_x'] ?? 0,
            $omitidos['filas_amarillas'] ?? 0,
            $omitidos['duplicados'] ?? 0,
        ));
    }
}
