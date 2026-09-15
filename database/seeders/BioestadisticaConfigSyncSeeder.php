<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Sync\ConfigSyncService;
use Illuminate\Database\Seeder;

/**
 * Alias para producción: php artisan db:seed --class=BioestadisticaConfigSyncSeeder
 *
 * Preferible: php artisan bioestadistica:sync-config
 *             php artisan bioestadistica:sync-config --dry-run
 *             php artisan bioestadistica:sync-config --only=organos,formularios
 */
class BioestadisticaConfigSyncSeeder extends Seeder
{
    public function run(ConfigSyncService $sync): void
    {
        $report = $sync->sync(ConfigSyncService::MODULES, dryRun: false, prune: true);

        $this->command?->info('Módulos: '.implode(', ', $report['modules']));
        foreach ($report['upserted'] as $module => $label) {
            $this->command?->line("  upsert {$module}: {$label}");
        }
        foreach ($report['pruned'] as $module => $items) {
            $this->command?->warn("  prune {$module}: ".count($items));
        }
        foreach ($report['skipped'] as $module => $items) {
            $this->command?->warn("  skip {$module}: ".count($items));
        }
    }
}
