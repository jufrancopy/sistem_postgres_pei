<?php

namespace App\Console\Commands;

use App\Application\Bioestadistica\Sync\FormSnapshotService;
use Illuminate\Console\Command;

class BioestadisticaFormsExportCommand extends Command
{
    protected $signature = 'bioestadistica:forms-export
        {--path= : Ruta del JSON (default: storage + copia a database/data)}
        {--no-seed-copy : No copiar a database/data/bioestadistica/forms-snapshot.json}';

    protected $description = 'Exporta configuración de formularios SP a JSON (claves estables, sin ids locales)';

    public function handle(FormSnapshotService $snapshots): int
    {
        $path = trim((string) $this->option('path'));
        if ($path === '') {
            $path = FormSnapshotService::defaultPath();
        }

        $payload = $snapshots->exportToFile($path);
        $forms = count($payload['formularios'] ?? []);
        $fields = collect($payload['formularios'] ?? [])
            ->flatMap(fn ($f) => collect($f['secciones'] ?? [])->flatMap(fn ($s) => $s['fields'] ?? []))
            ->count();

        $this->info("Exportados {$forms} formularios / {$fields} campos → {$path}");

        if (! $this->option('no-seed-copy')) {
            $seedPath = FormSnapshotService::seedDataPath();
            $snapshots->exportToFile($seedPath);
            $this->line("Copia para seeder: {$seedPath}");
        }

        return self::SUCCESS;
    }
}
