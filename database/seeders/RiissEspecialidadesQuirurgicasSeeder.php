<?php

namespace Database\Seeders;

use App\Models\Riiss\Establecimiento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RiissEspecialidadesQuirurgicasSeeder extends Seeder
{
    /**
     * Seed de Especialidades Médicas para Hospitales de Especialidades Quirúrgicas (Hospital Ingavi).
     */
    public function run(): void
    {
        $especialidadesNombres = [
            'ALERGIA E INMUNOLOGIA',
            'CARDIOLOGIA',
            'CARDIOLOGIA PEDIATRICA',
            'CIRUGÍA COLOPROCTOLÓGICA',
            'CIRUGIA DE TORAX',
            'CIRUGIA GENERAL',
            'CIRUGIA MENOR',
            'CIRUGIA VASCULAR PERIFERICA',
            'CLINICA MEDICA',
            'COLOPROCTOLOGIA AUX',
            'DIABETOLOGIA',
            'ENDOCRINOLOGIA',
            'ESTUDIO DE ANALISIS CLINICO',
            'GASTROENTEROLOGIA',
            'GERIATRIA',
            'GINECO-OBSTETRICIA',
            'GINECOLOGIA',
            'HEMATOLOGIA y HEMATOLOGIA CRONICOS',
            'INFECTOLOGIA',
            'MASTOLOGIA',
            'MEDICINA FAMILIAR',
            'NEFROLOGIA',
            'NEUMOLOGIA',
            'NEUROCIRUGIA',
            'NEUROLOGIA',
            'ODONTOLOGIA CLINICA',
            'OFTALMOLOGIA',
            'ONCOLOGIA',
            'ONCOLOGIA DE CABEZA Y CUELLO',
            'PEDIATRIA',
            'PROCEDIMIENTOS - CURACIONES',
            'PROCTOLOGIA',
            'REPOSICION DE RECETAS',
            'REUMATOLOGIA',
            'TERAPIA INTENSIVA',
            'TRASCRIPCION DE RECETAS SIN EXISTENCIA',
            'TRAUMATOLOGIA PEDIATRICA',
            'TRAUMATOLOGIA Y ORTOPEDIA',
            'URGENCIAS CIRUGIA',
            'URGENCIAS CLINICA MEDICA',
            'URGENCIAS PEDIATRICAS',
            'URGENCIAS TRAUMATOLOGIA',
            'URGENCIAS UROLOGIA',
            'UROLOGIA',
        ];

        // 1. Obtener especialidades existentes en riiss_especialidades
        $riissEsps = DB::table('riiss_especialidades')->get();
        $especialidadesIds = [];

        foreach ($especialidadesNombres as $nombre) {
            $cleanTarget = trim(preg_replace('/\s+/', ' ', Str::upper(Str::ascii($nombre))));

            // Buscar coincidencia normalizada
            $esp = $riissEsps->first(function ($e) use ($cleanTarget) {
                $c = trim(preg_replace('/\s+/', ' ', Str::upper(Str::ascii($e->nombre))));
                return $c === $cleanTarget;
            });

            if ($esp) {
                $especialidadesIds[] = $esp->id;
            } else {
                // Insertar en riiss_especialidades
                $newId = DB::table('riiss_especialidades')->insertGetId([
                    'nombre'     => mb_strtoupper($nombre, 'UTF-8'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Mantener sincronizado en bioestadistica.especialidades_medicas
                try {
                    $codigo = 'ESP-' . Str::upper(Str::random(6));
                    DB::table('bioestadistica.especialidades_medicas')->updateOrInsert(
                        ['id' => $newId],
                        [
                            'codigo'             => $codigo,
                            'nombre'             => mb_strtoupper($nombre, 'UTF-8'),
                            'nombre_normalizado' => $cleanTarget,
                            'activo'             => true,
                            'created_at'         => now(),
                            'updated_at'         => now(),
                        ]
                    );
                } catch (\Throwable $e) {
                    // Ignorar si el ID ya existe en bioestadistica
                }

                $especialidadesIds[] = $newId;
                $riissEsps = DB::table('riiss_especialidades')->get();
            }
        }

        $especialidadesIds = array_unique($especialidadesIds);

        // 2. Localizar los establecimientos de Hospitales de Especialidades Quirúrgicas
        $establecimientos = Establecimiento::where('area_gestion', 'ilike', '%QUIR%')
            ->orWhere('id_establecimiento', '11-HE-02')
            ->get();

        if ($establecimientos->isEmpty()) {
            $ingavi = Establecimiento::where('id_establecimiento', '11-HE-02')
                ->orWhere('nombre_oficial', 'ilike', '%INGAVI%')
                ->first();
            if ($ingavi) {
                $establecimientos = collect([$ingavi]);
            }
        }

        // 3. Asociar en riiss_establecimiento_especialidades
        foreach ($establecimientos as $est) {
            if (empty($est->area_gestion) || !str_contains(strtoupper($est->area_gestion), 'QUIR')) {
                $est->area_gestion = 'HOSPITALES DE ESPECIALIDADES QUIRÚRGICAS';
                $est->save();
            }

            $countAsociadas = 0;
            foreach ($especialidadesIds as $espId) {
                DB::table('riiss_establecimiento_especialidades')->updateOrInsert(
                    [
                        'establecimiento_id' => $est->id_establecimiento,
                        'especialidad_id'    => $espId,
                    ],
                    [
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]
                );
                $countAsociadas++;
            }

            $this->command?->info("Establecimiento {$est->nombre_oficial} ({$est->id_establecimiento}): {$countAsociadas} especialidades asociadas.");
        }
    }
}
