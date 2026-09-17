<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Sync\FormSnapshotService;
use Illuminate\Database\Seeder;

/**
 * Aplica la configuración canónica de formularios exportada desde desarrollo.
 * Resuelve detalle_id por dominio+nombre (no por ids de otra BD).
 * No toca record_values.
 *
 * Regenerar JSON en desarrollo:
 *   php artisan bioestadistica:forms-export
 */
class BioestadisticaFormulariosSnapshotSeeder extends Seeder
{
    public function run(): void
    {
        $path = FormSnapshotService::seedDataPath();
        if (! is_file($path)) {
            $fallback = FormSnapshotService::defaultPath();
            if (is_file($fallback)) {
                $path = $fallback;
            } else {
                $this->command?->error('No hay forms-snapshot.json. Ejecute: php artisan bioestadistica:forms-export');

                return;
            }
        }

        // Asegura shells SP1–SP14 existan.
        $this->call(BioestadisticaFormulariosSeeder::class);

        $report = app(FormSnapshotService::class)->importFromFile($path, pruneExtraFields: true);

        $this->command?->info('Formularios sincronizados desde snapshot:');
        $this->command?->line('  Archivo: '.$path);
        $this->command?->line("  Formularios: {$report['formularios']}");
        $this->command?->line("  Secciones: {$report['secciones']}");
        $this->command?->line("  Fields: {$report['fields']}");
        $this->command?->line("  Fields podados: {$report['pruned_fields']}");
        if ($report['skipped'] !== []) {
            $this->command?->warn('Advertencias: '.count($report['skipped']));
            foreach (array_slice($report['skipped'], 0, 15) as $line) {
                $this->command?->line('  - '.$line);
            }
        }
    }
}
