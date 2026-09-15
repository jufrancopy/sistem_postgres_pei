<?php

namespace App\Console\Commands;

use App\Application\Bioestadistica\Sync\DictionarySnapshotService;
use Illuminate\Console\Command;

class BioestadisticaDictionaryExportCommand extends Command
{
    protected $signature = 'bioestadistica:dictionary-export
        {--path= : Ruta del JSON (default: storage/app/bioestadistica/dictionary-snapshot.json)}';

    protected $description = 'Exporta el diccionario actual (variables/detalles/prestaciones) a JSON, sin cargas SP';

    public function handle(DictionarySnapshotService $snapshots): int
    {
        $path = trim((string) $this->option('path'));
        if ($path === '') {
            $path = storage_path('app/bioestadistica/dictionary-snapshot.json');
        }

        $payload = $snapshots->exportToFile($path);
        $vars = count($payload['variables']);
        $detalles = collect($payload['variables'])->sum(fn ($v) => count($v['detalles'] ?? []));
        $items = collect($payload['variables'])->sum(
            fn ($v) => collect($v['detalles'] ?? [])->sum(fn ($d) => count($d['items'] ?? []))
        );

        $this->info("Snapshot escrito: {$path}");
        $this->line("  Variables: {$vars}");
        $this->line("  Detalles: {$detalles}");
        $this->line("  Prestaciones (puentes): {$items}");
        $this->newLine();
        $this->comment('En el otro entorno: php artisan bioestadistica:dictionary-import --path="'.$path.'"');

        return self::SUCCESS;
    }
}
