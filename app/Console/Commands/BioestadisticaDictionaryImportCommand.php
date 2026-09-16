<?php

namespace App\Console\Commands;

use App\Application\Bioestadistica\Sync\DictionarySnapshotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BioestadisticaDictionaryImportCommand extends Command
{
    protected $signature = 'bioestadistica:dictionary-import
        {--path= : Ruta del JSON exportado desde desarrollo}
        {--dry-run : Simula en transacción y hace rollback}
        {--no-prune-x : No elimina variables dominio x ausentes en el snapshot}';

    protected $description = 'Importa diccionario desde snapshot JSON (upsert). No borra record_values / cargas SP';

    public function handle(DictionarySnapshotService $snapshots): int
    {
        $path = trim((string) $this->option('path'));
        if ($path === '') {
            $path = storage_path('app/bioestadistica/dictionary-snapshot.json');
        }
        if (! is_file($path)) {
            $this->error("No se encontró el snapshot: {$path}");
            $this->line('Genérelo en desarrollo con: php artisan bioestadistica:dictionary-export');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $pruneX = ! (bool) $this->option('no-prune-x');

        $this->info('=== IMPORT DICCIONARIO (snapshot) ===');
        $this->line('Archivo: '.$path);
        $this->line('Prune x extras: '.($pruneX ? 'sí' : 'no'));
        if ($dryRun) {
            $this->warn('MODO --dry-run: se revierte al final.');
        }

        DB::connection('pgsql')->beginTransaction();
        try {
            $report = $snapshots->importFromFile($path, $pruneX);
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
        $this->line("  Variables tocadas: {$report['variables']}");
        $this->line("  Detalles tocados: {$report['detalles']}");
        $this->line("  Prestaciones upsert: {$report['items']}");

        if ($report['pruned'] !== []) {
            $this->newLine();
            $this->info('Podados (soft, solo dominio x):');
            foreach (array_slice($report['pruned'], 0, 40) as $line) {
                $this->line('  - '.$line);
            }
            if (count($report['pruned']) > 40) {
                $this->line('  … +'.(count($report['pruned']) - 40).' más');
            }
        }

        if ($report['skipped'] !== []) {
            $this->newLine();
            $this->warn('Omitidos:');
            foreach (array_slice($report['skipped'], 0, 20) as $line) {
                $this->line('  - '.$line);
            }
        }

        $this->newLine();
        $this->info($dryRun ? 'Simulación completada.' : 'Importación aplicada. Luego: php artisan bioestadistica:sync-config --only=formularios');

        return self::SUCCESS;
    }
}
