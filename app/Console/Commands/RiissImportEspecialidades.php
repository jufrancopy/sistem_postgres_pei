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
        $nombreTrim = trim($nombreEmpresa);

        // Diccionario de homologación directa para discrepancias de nombres entre Excel y DB
        $aliasMap = [
            'CAACUPE U.S' => '03-US-05', // CAACUPE US
            'CAAGUAZU U.S' => '05-US-09', // CAAGUAZU US
            'CAAZAPA U.S' => '06-US-10', // CAAZAPA US
            'CHORE PS' => '02-PS-02', // CHORE CONVENIO PS
            'CLINICA NANAWA' => '18-CP-04', // NANAWA C.P.
            'CLINICA PERIFERICA BOQUERON' => '18-CP-03', // BOQUERON C.P.
            'CLINICA PERIFERICA CAMPO VIA CAPIATA' => '11-CP-01', // CAMPO VIA C.P.
            'CLINICA PERIFERICA ISLA POI' => '18-CP-05', // ISLA PO'I PS
            'CLINICA PERIFERICA YRENDAGUE' => '11-CP-02', // YRENDAGUE C.P.
            'CNEL. BOGADO PS' => '07-PS-29', // CORONEL BOGADO PS CONVENIO
            'CNEL. OVIEDO H.R' => '05-HR-05', // CNEL. OVIEDO HR
            'CNEL. OVIEDO HR' => '05-HR-05', // CNEL. OVIEDO HR
            'COLONIA INDEPENDENCIA US' => '04-US-06', // INDEPENDENCIA US
            'CONCEPCION HR' => '01-HR-01', // CONCEPCIÓN HR
            'DPTO DE MEDICINA FISICA Y REHABILITACION' => '18-CE-02', // CENTRO DE MEDICINA FISICA Y REHABILITACION
            'DPTO PSIQUITRIA Y PSICOLOGIA' => '18-CE-03', // CENTRO DE PSIQUIATRIA Y PSICOTERAPIA
            'FRAM U.S' => '07-US-32', // FRAM US
            'HERNANDARIAS U.S' => '10-US-16', // HERNANDARIAS US
            'HOHENAU U.S' => '07-US-12', // HOHENAU US
            'HORQUETA US' => '01-US-01', // HORQUETA US
            'HOSPITAL 12 DE JUNIO' => '18-HO-04', // 12 DE JUNIO H.
            'HOSPITAL BUONGERMINI - GERIATRICO' => '18-HE-03', // DR.GERARDO BUONGERMINI HOSPITAL
            'HOSPITAL DE LUQUE' => '11-HO-01', // LUQUE HOSPITAL
            'P.S. SANTA RITA' => '10-US-18', // SANTA RITA US
            'PRESIDENTE FRANCO US' => '10-US-17', // PTO. PTE. FRANCO US
            'PTO. ROSARIO U.S' => '02-US-03', // PUERTO ROSARIO US
            'PUENTE KYJHA US' => '14-US-23', // PUENTE KYHA US
            'SAN IGNACIO U.S' => '08-US-13', // SAN IGNACIO US
            'SAN ISIDRO DEL CURUGUATY US' => '14-US-24', // SAN ISIDRO DE CURUGUATY US
            'SAN JUAN BAUTISTA PS' => '08-US-14', // SAN JUAN BAUTISTA US
            'SAN PEDRO DEL YCUAMANDIYU' => '02-HR-02', // SAN PEDRO DEL YCUAMANDY YU HR
            'UBAS  ITÀ' => '11-PS-61', // UBAS ITA
            'UBAS ITÀ' => '11-PS-61', // UBAS ITA
            'VALLEMI U.S.' => '01-US-02', // VALLEMI US
            'VILLETA U.S' => '11-US-20', // VILLETA US
        ];

        if (isset($aliasMap[$nombreTrim])) {
            return Establecimiento::where('id_establecimiento', $aliasMap[$nombreTrim])->first();
        }

        // Intento 1: Match exacto
        $est = Establecimiento::where('nombre_oficial', $nombreTrim)->first();
        if ($est) return $est;
        
        // Intento 2: Match exacto ignorando mayúsculas/minúsculas
        $est = Establecimiento::whereRaw('LOWER(nombre_oficial) = ?', [strtolower($nombreTrim)])->first();
        if ($est) return $est;
        
        // Intento 3: LIKE
        $est = Establecimiento::where('nombre_oficial', 'ILIKE', '%' . $nombreTrim . '%')->first();
        if ($est) return $est;
        
        // Intento 4: Normalizando puntos y abreviaciones comunes
        $nombreLimpio = trim(str_replace(['U.S.', 'U.S', 'H.R', 'H.D', 'P.S', 'USF', 'U.S.F', 'C.S', '.'], '', $nombreTrim));
        if (strlen($nombreLimpio) > 3) {
            $est = Establecimiento::where('nombre_oficial', 'ILIKE', '%' . $nombreLimpio . '%')->first();
            if ($est) return $est;
        }

        return null;
    }
}
