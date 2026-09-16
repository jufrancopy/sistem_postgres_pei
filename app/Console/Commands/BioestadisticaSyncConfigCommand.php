<?php

namespace App\Console\Commands;

use App\Application\Bioestadistica\Sync\ConfigSyncService;
use Illuminate\Console\Command;

class BioestadisticaSyncConfigCommand extends Command
{
    protected $signature = 'bioestadistica:sync-config
        {--only= : Módulos separados por coma (roles,geografia,variables,formularios,indicadores,dashboards,organos)}
        {--dry-run : Simula: aplica en transacción y hace rollback}
        {--no-prune : Solo upsert; no elimina/desactiva lo ausente en origen}';

    protected $description = 'Sincroniza Configuraciones bioestadística (Excel + diccionarios Sp2/Sp7/Sp12/Sp13 + formularios). No borra record_values / cargas SP.';

    public function handle(ConfigSyncService $sync): int
    {
        $only = trim((string) $this->option('only'));
        $modules = $only === ''
            ? ConfigSyncService::MODULES
            : array_values(array_filter(array_map('trim', explode(',', $only))));

        $unknown = array_diff($modules, ConfigSyncService::MODULES);
        if ($unknown !== []) {
            $this->error('Módulos desconocidos: '.implode(', ', $unknown));
            $this->line('Válidos: '.implode(', ', ConfigSyncService::MODULES));

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $prune = ! (bool) $this->option('no-prune');

        $this->info('=== SYNC CONFIGURACIONES BIOESTADÍSTICA ===');
        $this->line('Módulos: '.implode(', ', $modules));
        $this->line('Prune: '.($prune ? 'sí (soft-delete/desactivar huérfanos seguros)' : 'no'));
        if ($dryRun) {
            $this->warn('MODO --dry-run: los cambios se revierten al final.');
        }

        $started = microtime(true);
        $report = $sync->sync($modules, $dryRun, $prune);
        $elapsed = round(microtime(true) - $started, 2);

        $this->newLine();
        $this->info('Upsert:');
        foreach ($report['upserted'] as $module => $label) {
            $this->line("  · {$module}: {$label}");
        }

        if ($report['pruned'] !== []) {
            $this->newLine();
            $this->info('Podados / eliminados (soft):');
            foreach ($report['pruned'] as $module => $items) {
                $this->line("  [{$module}] ".count($items).' ítem(s)');
                foreach (array_slice($items, 0, 30) as $item) {
                    $this->line("    - {$item}");
                }
                if (count($items) > 30) {
                    $this->line('    … +'.(count($items) - 30).' más');
                }
            }
        }

        if ($report['skipped'] !== []) {
            $this->newLine();
            $this->warn('Omitidos (vinculados u otra razón):');
            foreach ($report['skipped'] as $module => $items) {
                foreach (array_slice($items, 0, 20) as $item) {
                    $this->line("  [{$module}] {$item}");
                }
                if (count($items) > 20) {
                    $this->line('  … +'.(count($items) - 20).' más en '.$module);
                }
            }
        }

        $this->newLine();
        $this->info(($dryRun ? 'Simulación completada' : 'Sincronización aplicada')." en {$elapsed}s.");

        return self::SUCCESS;
    }
}
