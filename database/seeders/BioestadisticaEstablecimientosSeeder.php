<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Imports\EstablecimientosDimImporter;
use App\Models\Bioestadistica\Establecimiento;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BioestadisticaEstablecimientosSeeder extends Seeder
{
    public function run(): void
    {
        $path = $this->sourcePath();
        if (! $path || ! is_file($path)) {
            $this->command?->warn('No hay un maestro de establecimientos completo (mínimo 100 filas); se preservan los datos existentes.');

            return;
        }

        $this->command?->info('Importando maestro desde '.basename($path));
        $summary = app(EstablecimientosDimImporter::class)->import($path);
        $this->command?->info("Maestro Bioestadística: {$summary['procesados']} establecimientos procesados.");
        foreach ($summary['advertencias'] ?? [] as $warning) {
            $this->command?->warn($warning);
        }
        $pending = Establecimiento::whereNull('distrito_id')->count();
        $withoutSih = Establecimiento::where(function ($query) {
            $query->whereNull('codigo_sih')->orWhere('codigo_sih', '');
        })->count();
        $withoutSystem = Establecimiento::where(function ($query) {
            $query->whereNull('sistema')->orWhere('sistema', '');
        })->count();
        $this->command?->info("Pendientes: {$pending} sin distrito, {$withoutSih} sin código SIH, {$withoutSystem} sin sistema.");
    }

    private function sourcePath(): ?string
    {
        $dir = base_path('.docs-bio');
        $preferred = [];
        $fallback = [];
        foreach (glob($dir.DIRECTORY_SEPARATOR.'*') ?: [] as $file) {
            if (! is_file($file) || ! preg_match('/establecimiento_con_id/i', basename($file))) {
                continue;
            }
            if (str_contains(basename($file), ' 2.') || preg_match('/_2\./', basename($file))) {
                $preferred[] = $file;
            } else {
                $fallback[] = $file;
            }
        }

        foreach (array_merge($preferred, $fallback) as $path) {
            if ($this->hasAtLeastEstablishments($path, 100)) {
                return $path;
            }
        }

        return null;
    }

    private function hasAtLeastEstablishments(string $path, int $minimum): bool
    {
        $book = IOFactory::createReaderForFile($path)->setReadDataOnly(true)->load($path);
        $sheet = $book->getSheetByName('ESTABLECIMIENTOS')
            ?? $book->getSheetByName('DIM ESTABLECIMIENTOS')
            ?? $book->getActiveSheet();
        $count = 0;
        for ($row = 2; $row <= $sheet->getHighestDataRow() && $count < $minimum; $row++) {
            if (trim((string) $sheet->getCell([1, $row])->getValue()) !== '') {
                $count++;
            }
        }
        $book->disconnectWorksheets();

        return $count >= $minimum;
    }
}
