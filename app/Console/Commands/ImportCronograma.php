<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\CronogramaImporter;

class ImportCronograma extends Command
{
    protected $signature = 'import:cronograma {file}';

    protected $description = 'Importa un JSON de cronograma y crea periodo, schedules y items';

    public function handle()
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error('File not found: ' . $file);
            return 1;
        }

        $json = json_decode(file_get_contents($file), true);
        if (!$json) {
            $this->error('Invalid JSON');
            return 1;
        }

        $importer = new CronogramaImporter();
        $period = $importer->import($json);

        $this->info('Import completed. Period id: ' . $period->id);
        return 0;
    }
}
