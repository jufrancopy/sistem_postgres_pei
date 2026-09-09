<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Imports\EspecialidadesCatalogMerger;
use Illuminate\Database\Seeder;

class BioestadisticaEspecialidadesMergeSeeder extends Seeder
{
    public function run(): void
    {
        $merger = new EspecialidadesCatalogMerger;
        // Idempotente: aplica alias iniciales + seguimiento (psicología niños, hematología, borrar otras).
        $summary = $merger->applyAgreedAliases();

        $this->command?->info(sprintf(
            'Fusiones especialidades: merged=%d renamed=%d codigo_only=%d soft_deleted=%d',
            $summary['merged'],
            $summary['renamed'],
            $summary['codigo_only'],
            $summary['soft_deleted']
        ));

        foreach ($summary['details'] as $line) {
            $this->command?->line('  · '.$line);
        }
    }
}
