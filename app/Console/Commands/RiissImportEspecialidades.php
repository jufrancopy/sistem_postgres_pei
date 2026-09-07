<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Riiss\Establecimiento;
use App\Models\RiissEspecialidad;
use App\Models\RiissMedicamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RiissImportEspecialidades extends Command
{
    protected $signature = 'riiss:import-especialidades {--file=backups/RIISS/RIISS_especialidades/PRODUCTOS  por establecimientos y especialidad.xlsx}';
    protected $description = 'Importa especialidades y medicamentos desde Excel para los establecimientos de RIISS';

    public function handle()
    {
        $file = base_path($this->option('file'));
        if (!file_exists($file)) {
            $this->error("Archivo no encontrado: {$file}");
            return 1;
        }

        $this->info("Limpiando tablas antiguas...");
        DB::statement('TRUNCATE TABLE riiss_est_esp_medicamentos RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE riiss_establecimiento_especialidades RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE riiss_medicamentos RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE TABLE riiss_especialidades RESTART IDENTITY CASCADE');

        $this->info("Cargando archivo Excel...");
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $currentEstablecimientoId = null;
        $currentEspecialidadId = null;
        
        $notFound = [];
        $countMedicamentos = 0;

        foreach ($worksheet->getRowIterator(3) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            
            // Col 1: Nm Empresa (Establecimiento)
            if (!empty(trim($rowData[1]))) {
                $nombreEmpresa = trim($rowData[1]);
                $establecimiento = $this->findEstablecimiento($nombreEmpresa);
                
                if ($establecimiento) {
                    $currentEstablecimientoId = $establecimiento->id_establecimiento;
                } else {
                    $currentEstablecimientoId = null;
                    if (!in_array($nombreEmpresa, $notFound)) {
                        $notFound[] = $nombreEmpresa;
                        $this->warn("No se encontró el establecimiento: {$nombreEmpresa}");
                    }
                }
            }

            // Si no tenemos un establecimiento actual válido, saltamos
            if (!$currentEstablecimientoId) {
                continue;
            }

            // Col 2: Nm Espec Med
            if (!empty(trim($rowData[2]))) {
                $nombreEspecialidad = trim($rowData[2]);
                $especialidad = RiissEspecialidad::firstOrCreate(['nombre' => $nombreEspecialidad]);
                $currentEspecialidadId = $especialidad->id;

                // Asociar al establecimiento
                DB::table('riiss_establecimiento_especialidades')->updateOrInsert([
                    'establecimiento_id' => $currentEstablecimientoId,
                    'especialidad_id' => $currentEspecialidadId
                ]);
            }

            // Col 4: Cod Produto
            if (!empty(trim($rowData[4])) && $currentEspecialidadId) {
                $codProducto = trim($rowData[4]);
                $nombreProducto = trim($rowData[3] ?? '');

                $medicamento = RiissMedicamento::firstOrCreate(
                    ['codigo' => $codProducto],
                    ['nombre' => $nombreProducto]
                );

                DB::table('riiss_est_esp_medicamentos')->updateOrInsert([
                    'establecimiento_id' => $currentEstablecimientoId,
                    'especialidad_id' => $currentEspecialidadId,
                    'medicamento_id' => $medicamento->id
                ]);
                $countMedicamentos++;
            }
        }

        $this->info("Importación finalizada. {$countMedicamentos} asociaciones de medicamentos creadas.");
        if (count($notFound) > 0) {
            $this->error(count($notFound) . " establecimientos no fueron encontrados en la base de datos.");
        }

        return 0;
    }

    private function findEstablecimiento($nombreEmpresa)
    {
        // Intento 1: Match exacto
        $est = Establecimiento::where('nombre_oficial', $nombreEmpresa)->first();
        if ($est) return $est;
        
        // Intento 2: Match exacto ignorando mayúsculas/minúsculas
        $est = Establecimiento::whereRaw('LOWER(nombre_oficial) = ?', [strtolower($nombreEmpresa)])->first();
        if ($est) return $est;
        
        // Intento 3: LIKE
        $est = Establecimiento::where('nombre_oficial', 'ILIKE', '%' . $nombreEmpresa . '%')->first();
        if ($est) return $est;
        
        // Intento 4: Quitando abreviaciones comunes y buscando LIKE
        $nombreLimpio = trim(str_replace(['H.R', 'H.D', 'P.S', 'USF', 'U.S.F', 'C.S'], '', $nombreEmpresa));
        if (strlen($nombreLimpio) > 3) {
            $est = Establecimiento::where('nombre_oficial', 'ILIKE', '%' . $nombreLimpio . '%')->first();
            if ($est) return $est;
        }

        return null;
    }
}
