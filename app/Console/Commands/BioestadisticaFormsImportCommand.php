<?php

namespace App\Console\Commands;

use App\Application\Bioestadistica\Sync\FormSnapshotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BioestadisticaFormsImportCommand extends Command
{
    protected $signature = 'bioestadistica:forms-import
        {--path= : Ruta del JSON (default: database/data o storage)}
        {--dry-run : Simula en transacción y hace rollback}
        {--no-prune : No soft-delete tablas del form ausentes en el snapshot}';

    protected $description = 'Importa configuración de formularios desde snapshot JSON. No borra record_values.';

    public function handle(FormSnapshotService $snapshots): int
    {
        $path = trim((string) $this->option('path'));
        if ($path === '') {
            $seed = FormSnapshotService::seedDataPath();
            $storage = FormSnapshotService::defaultPath();
            $path = is_file($seed) ? $seed : $storage;
        }

        if (! is_file($path)) {
            $this->error("No se encontró el snapshot: {$path}");
            $this->line('Genérelo en desarrollo con: php artisan bioestadistica:forms-export');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $prune = ! (bool) $this->option('no-prune');

        $this->info('=== IMPORT FORMULARIOS (snapshot) ===');
        $this->line('Archivo: '.$path);
        $this->line('Prune fields extras: '.($prune ? 'sí' : 'no'));
        if ($dryRun) {
            $this->warn('MODO --dry-run: se revierte al final.');
        }

        DB::connection('pgsql')->beginTransaction();
        try {
            $report = $snapshots->importFromFile($path, $prune);
            if ($dryRun) {
                DB::connection('pgsql')->rollBack();
            } else {
                DB::connection('pgsql')->commit();
            }
        } catch (\Throwable $e) {
            DB::connection('pgsql')->rollBack();
            throw $e;
        }

        $this->newLine();
        $this->info('Upsert:');
        $this->line("  Formularios: {$report['formularios']}");
        $this->line("  Secciones: {$report['secciones']}");
        $this->line("  Fields: {$report['fields']}");
        $this->line("  Fields podados (soft): {$report['pruned_fields']}");

        if ($report['skipped'] !== []) {
            $this->newLine();
            $this->warn('Omitidos / advertencias:');
            foreach (array_slice($report['skipped'], 0, 40) as $line) {
                $this->line('  - '.$line);
            }
            if (count($report['skipped']) > 40) {
                $this->line('  … +'.(count($report['skipped']) - 40).' más');
            }
        }

        $this->newLine();
        $this->info($dryRun ? 'Simulación completada.' : 'Importación de formularios aplicada.');

        return self::SUCCESS;
    }
}
