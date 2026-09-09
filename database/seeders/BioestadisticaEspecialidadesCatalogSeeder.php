<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Imports\EspecialidadesCatalogImporter;
use Illuminate\Database\Seeder;

class BioestadisticaEspecialidadesCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('.docs-bio/Especialidades.xlsx');
        if (! is_file($path)) {
            $this->command?->warn('No se encontró .docs-bio/Especialidades.xlsx; se omite.');

            return;
        }

        $summary = (new EspecialidadesCatalogImporter)->import($path);

        $this->command?->info(sprintf(
            'Especialidades: %d procesadas (obs vacías), %d actualizadas, %d creadas, %d omitidas por obs.',
            $summary['procesados'],
            $summary['actualizados'],
            $summary['creados'],
            $summary['omitidos_obs']
        ));

        if ($summary['multi_contexto'] !== []) {
            $this->command?->warn('Matches multi-contexto (código aplicado a todas): '.count($summary['multi_contexto']));
            foreach (array_slice($summary['multi_contexto'], 0, 15) as $line) {
                $this->command?->line('  · '.$line);
            }
        }

        if ($summary['advertencias'] !== []) {
            foreach ($summary['advertencias'] as $w) {
                $this->command?->warn($w);
            }
        }
    }
}
