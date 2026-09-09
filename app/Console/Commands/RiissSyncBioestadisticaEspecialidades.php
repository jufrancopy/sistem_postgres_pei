<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Bioestadistica\EspecialidadMedica;
use App\Models\RiissEspecialidad;

class RiissSyncBioestadisticaEspecialidades extends Command
{
    protected $signature = 'riiss:sync-bioestadistica-especialidades';
    protected $description = 'Sincroniza y unifica las especialidades médicas de RIISS con el catálogo canónico de Bioestadística (Diccionario #1)';

    public function handle()
    {
        $this->info("Iniciando sincronización de especialidades RIISS con Bioestadística...");

        function normalizarStr($str) {
            $str = mb_strtoupper(trim($str), 'UTF-8');
            $str = str_replace(['Á','É','Í','Ó','Ú','Ñ','Ü'], ['A','E','I','O','U','N','U'], $str);
            return preg_replace('/\s+/', ' ', $str);
        }

        $sinonimos = [
            'SIQUIATRIA' => 'PSIQUIATRÍA',
            'TRAUMATOLOGIA GENERAL' => 'TRAUMATOLOGÍA',
            'TRAUMATOLOGIA Y ORTOPEDIA' => 'TRAUMATOLOGÍA',
            'CARDIOLOGIA PEDIATRICA' => 'CARDIOLOGÍA INFANTIL',
            'ODONTOLOGIA CLINICA' => 'ODONTOLOGÍA',
            'ODONTOLOGIA (HASTA 12 A.)' => 'ODONTOLOGÍA',
            'ODONTOLOGIA EXTRACCION' => 'ODONTOLOGÍA',
            'ODONTOLOGIA PERIODONCIA' => 'ODONTOLOGÍA',
            'ODONTOLOGIA PREV. (HASTA 12 A.)' => 'ODONTOLOGÍA',
            'URGENCIAS CLINICA MEDICA' => 'URGENCIA CLINICA MEDICA',
            'URGENCIAS PEDIATRICAS' => 'ATENCION DE URGENCIAS PEDIATRICAS',
            'URGENCIAS TRAUMATOLOGIA' => 'URGENCIA TRAUMATOLOGIA',
            'URGENCIAS CIRUGIA' => 'URGENCIA CIRUGIA',
            'URGENCIAS GINECO-OBSTETRICIA' => 'URGENCIA GINECO-OBSTETRICIA',
            'URGENCIAS OBSTETRICIA' => 'URGENCIA OBSTETRICIA',
            'URGENCIAS CIR. RECONST. Y QUEMADO' => 'URGENCIAS CIRUGIA RECONSTRUCTIVA Y QUEMADOS',
            'FISIATRIA ADULTO' => 'FISIATRIA',
            'PSICOLOGIA ADOLESCENTE-NIÑOS' => 'PSICOLOGIA PEDIATRICA',
            'HEMATOLOGIA Y HEMATOLOGIA CRONICOS' => 'HEMATOLOGIA CRONICOS',
            'MEDICINA PALIATIVA' => 'TERAPIA DEL DOLOR Y MEDICINA PALIATIVA',
        ];

        DB::beginTransaction();
        try {
            $riissEsp = RiissEspecialidad::all();
            $mapeoId = []; // [old_riiss_id => bio_id]

            $this->info("1. Mapeando e incorporando especialidades a Bioestadística...");
            foreach ($riissEsp as $r) {
                $nombreNorm = normalizarStr($r->nombre);
                $nombreBusqueda = $sinonimos[$nombreNorm] ?? $r->nombre;
                $nombreBusquedaNorm = normalizarStr($nombreBusqueda);

                $bio = EspecialidadMedica::all()->first(function($b) use ($nombreBusquedaNorm) {
                    return normalizarStr($b->nombre) === $nombreBusquedaNorm;
                });

                if (!$bio) {
                    $bio = EspecialidadMedica::create([
                        'nombre' => mb_strtoupper(trim($r->nombre), 'UTF-8'),
                        'activo' => true,
                    ]);
                    $this->line("  [+] Alta en Bioestadística: #{$bio->id} {$bio->nombre}");
                } else {
                    $this->line("  [=] Match: RIISS #{$r->id} '{$r->nombre}' => Bioestadística #{$bio->id} '{$bio->nombre}'");
                }

                $mapeoId[$r->id] = $bio->id;
            }

            $this->info("2. Respaldando relaciones temporales de establecimientos y medicamentos...");
            $pivotEstRows = DB::table('riiss_establecimiento_especialidades')->get();
            $pivotMedRows = DB::table('riiss_est_esp_medicamentos')->get();

            $nuevasRelacionesEst = [];
            foreach ($pivotEstRows as $row) {
                $nuevoEspId = $mapeoId[$row->especialidad_id] ?? $row->especialidad_id;
                $key = $row->establecimiento_id . '_' . $nuevoEspId;
                $nuevasRelacionesEst[$key] = [
                    'establecimiento_id' => $row->establecimiento_id,
                    'especialidad_id'    => $nuevoEspId,
                    'created_at'         => $row->created_at ?? now(),
                    'updated_at'         => $row->updated_at ?? now(),
                ];
            }

            $nuevasRelacionesMed = [];
            foreach ($pivotMedRows as $row) {
                $nuevoEspId = $mapeoId[$row->especialidad_id] ?? $row->especialidad_id;
                $key = $row->establecimiento_id . '_' . $nuevoEspId . '_' . $row->medicamento_id;
                $nuevasRelacionesMed[$key] = [
                    'establecimiento_id' => $row->establecimiento_id,
                    'especialidad_id'    => $nuevoEspId,
                    'medicamento_id'     => $row->medicamento_id,
                    'created_at'         => $row->created_at ?? now(),
                    'updated_at'         => $row->updated_at ?? now(),
                ];
            }

            $this->info("3. Reconstruyendo tabla riiss_especialidades clonada desde Bioestadística...");
            DB::statement('TRUNCATE TABLE riiss_est_esp_medicamentos RESTART IDENTITY CASCADE');
            DB::statement('TRUNCATE TABLE riiss_establecimiento_especialidades RESTART IDENTITY CASCADE');
            DB::statement('TRUNCATE TABLE riiss_especialidades RESTART IDENTITY CASCADE');

            $allBio = EspecialidadMedica::orderBy('id')->get();
            foreach ($allBio as $b) {
                DB::table('riiss_especialidades')->insert([
                    'id'         => $b->id,
                    'nombre'     => $b->nombre,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Sincronizar secuencia en PostgreSQL si aplica
            try {
                $maxId = DB::table('riiss_especialidades')->max('id') ?: 1;
                DB::statement("SELECT setval('riiss_especialidades_id_seq', {$maxId}, true)");
            } catch (\Exception $e) {
                // Ignore if sequence name differs
            }

            $this->info("4. Reinsertando relaciones de establecimientos y medicamentos con los IDs unificados...");
            foreach ($nuevasRelacionesEst as $rel) {
                DB::table('riiss_establecimiento_especialidades')->insert($rel);
            }

            foreach ($nuevasRelacionesMed as $rel) {
                DB::table('riiss_est_esp_medicamentos')->insert($rel);
            }

            DB::commit();
            $this->info("¡Sincronización completada exitosamente! Todas las especialidades de RIISS ahora coinciden exactamente con los IDs de Bioestadística (#1 Diccionario).");
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error durante la sincronización: " . $e->getMessage());
            return 1;
        }
    }
}
