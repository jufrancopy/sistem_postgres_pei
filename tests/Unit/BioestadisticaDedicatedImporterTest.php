<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Imports\BioestadisticaDedicatedImporter;
use App\Application\Bioestadistica\Imports\EstablecimientosDimImporter;
use App\Application\Bioestadistica\Imports\FormulariosSpImporter;
use App\Application\Bioestadistica\Imports\PrestacionMatcher;
use App\Application\Bioestadistica\Imports\VariablesSaludImporter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BioestadisticaDedicatedImporterTest extends TestCase
{
    public function test_rejects_missing_and_non_excel_files(): void
    {
        $importer = new BioestadisticaDedicatedImporter(
            new VariablesSaludImporter(),
            new EstablecimientosDimImporter(),
            new FormulariosSpImporter(new PrestacionMatcher())
        );

        $this->expectException(InvalidArgumentException::class);
        $importer->import(__DIR__.DIRECTORY_SEPARATOR.'no-existe.xlsx', 'variables_salud');
    }

    public function test_rejects_unknown_import_type(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'bio_imp_');
        rename($path, $path.'.xlsx');
        $path .= '.xlsx';
        file_put_contents($path, 'xlsx');

        try {
            $importer = new BioestadisticaDedicatedImporter(
                new VariablesSaludImporter(),
                new EstablecimientosDimImporter(),
                new FormulariosSpImporter(new PrestacionMatcher())
            );
            $this->expectException(InvalidArgumentException::class);
            $importer->import($path, 'desconocido');
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
