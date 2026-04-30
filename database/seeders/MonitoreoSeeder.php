<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MonitoreoSeeder extends Seeder
{
    // ── IDs reales de la base ──────────────────────────────────
    // Ajustá estos valores si cambian en tu entorno
    const USER_ID       = 4;
    const GROUP_ID      = 8;       // Gerencia de Desarrollo y Tecnología
    const DEPENDENCY_ID = 1;       // Gerencia de Salud
    const FODA_PERFIL_ID = '9a670613-48f6-4af7-8680-57660453ee1b'; // Río Pilcomayo

    // Responsables del organigrama existentes
    const RESP_A = 1;  // Gerencia de Salud
    const RESP_R = 2;  // Dirección de Hospitales
    const RESP_C = 3;  // Clínica Periférica Yrendague
    const RESP_I = 4;  // Clínica Periférica Isla Poí

    public function run(): void
    {
        $this->seedPeiConAcciones();
        $this->seedIeaFoda();
    }

    // ══════════════════════════════════════════════════════════
    // § 1-3  PEI master → eje → objetivo → acciones
    //        con semáforo, RACI y presupuesto
    // ══════════════════════════════════════════════════════════
    private function seedPeiConAcciones(): void
    {
        $now = Carbon::now();

        // ── Master ──────────────────────────────────────────
        $masterId = (string) Str::uuid();
        DB::table('planificacion.pei_profiles')->insert([
            'id'           => $masterId,
            'name'         => 'PEI Demo — Monitoreo 2025-2028',
            'level'        => 'master',
            'type'         => 'group',
            'year_start'   => '2025-01-01',
            'year_end'     => '2028-12-31',
            'group_id'     => self::GROUP_ID,
            'dependency_id'=> self::DEPENDENCY_ID,
            'user_id'      => self::USER_ID,
            'mision'       => '<p>Garantizar prestaciones de seguridad social con calidad, cobertura y sostenibilidad financiera.</p>',
            'vision'       => '<p>Ser la institución líder en seguridad social de Paraguay, con gestión eficiente y tecnología de vanguardia.</p>',
            'values'       => '<p>Transparencia · Eficiencia · Equidad · Innovación</p>',
            '_lft'         => 0, '_rgt' => 0,
            'created_at'   => $now, 'updated_at' => $now,
        ]);

        // ── Eje ──────────────────────────────────────────────
        $axiId = (string) Str::uuid();
        DB::table('planificacion.pei_profiles')->insert([
            'id'           => $axiId,
            'name'         => '<p><strong>Eje I — Fortalecimiento de la Red de Salud</strong></p>',
            'level'        => 'axi',
            'type'         => 'group',
            'year_start'   => '2025-01-01',
            'year_end'     => '2028-12-31',
            'parent_id'    => $masterId,
            'group_id'     => self::GROUP_ID,
            'dependency_id'=> self::DEPENDENCY_ID,
            'user_id'      => self::USER_ID,
            '_lft'         => 0, '_rgt' => 0,
            'created_at'   => $now, 'updated_at' => $now,
        ]);

        // ── Objetivo ─────────────────────────────────────────
        $goalId = (string) Str::uuid();
        DB::table('planificacion.pei_profiles')->insert([
            'id'           => $goalId,
            'name'         => '<p>Ampliar la cobertura de servicios de salud preventiva en un 30%</p>',
            'level'        => 'goal',
            'type'         => 'group',
            'year_start'   => '2025-01-01',
            'year_end'     => '2028-12-31',
            'parent_id'    => $axiId,
            'group_id'     => self::GROUP_ID,
            'dependency_id'=> self::DEPENDENCY_ID,
            'user_id'      => self::USER_ID,
            '_lft'         => 0, '_rgt' => 0,
            'created_at'   => $now, 'updated_at' => $now,
        ]);

        // ── Acciones con distintos estados de semáforo ───────
        $acciones = [
            [
                'name'                  => '<p>Implementar programa de telemedicina en 10 centros periféricos</p>',
                'indicator'             => 'Nro. de centros con telemedicina activa',
                'tipo_indicador'        => 'lead',
                'baseline'              => '0',
                'target'                => '10',
                'progress'              => '9',   // 90% → verde
                'presupuesto_asignado'  => 500000,
                'presupuesto_ejecutado' => 420000,
                'semaforo'              => 'verde',
                'responsibles'          => [
                    self::RESP_A => 'A',
                    self::RESP_R => 'R',
                    self::RESP_C => 'C',
                ],
            ],
            [
                'name'                  => '<p>Capacitar 200 funcionarios en protocolos de atención primaria</p>',
                'indicator'             => 'Nro. de funcionarios capacitados',
                'tipo_indicador'        => 'lead',
                'baseline'              => '0',
                'target'                => '200',
                'progress'              => '110',  // 55% → amarillo
                'presupuesto_asignado'  => 80000,
                'presupuesto_ejecutado' => 65000,
                'semaforo'              => 'amarillo',
                'responsibles'          => [
                    self::RESP_A => 'A',
                    self::RESP_I => 'I',
                ],
            ],
            [
                'name'                  => '<p>Reducir tiempo de espera promedio a menos de 30 minutos</p>',
                'indicator'             => 'Tiempo promedio de espera (minutos)',
                'tipo_indicador'        => 'lag',
                'baseline'              => '75',
                'target'                => '30',
                'progress'              => '8',    // 8% → rojo
                'presupuesto_asignado'  => 120000,
                'presupuesto_ejecutado' => 98000,  // 81% ejecutado con 8% meta → ALERTA
                'semaforo'              => 'rojo',
                'responsibles'          => [
                    self::RESP_A => 'A',
                    self::RESP_R => 'R',
                ],
            ],
            [
                'name'                  => '<p>Adquirir equipos de diagnóstico por imágenes para 5 hospitales</p>',
                'indicator'             => 'Nro. de hospitales con equipos nuevos',
                'tipo_indicador'        => 'lag',
                'baseline'              => '0',
                'target'                => '5',
                'progress'              => '1',    // 20% → rojo + ALERTA presupuestaria
                'presupuesto_asignado'  => 2000000,
                'presupuesto_ejecutado' => 1700000, // 85% ejecutado con 20% meta → ALERTA
                'semaforo'              => 'rojo',
                'responsibles'          => [
                    self::RESP_A => 'A',
                    self::RESP_C => 'C',
                    self::RESP_I => 'I',
                ],
            ],
        ];

        foreach ($acciones as $accion) {
            $actionId = (string) Str::uuid();
            $responsibles = $accion['responsibles'];
            unset($accion['responsibles']);

            DB::table('planificacion.pei_profiles')->insert(array_merge($accion, [
                'id'           => $actionId,
                'level'        => 'action',
                'type'         => 'group',
                'year_start'   => '2025-01-01',
                'year_end'     => '2028-12-31',
                'parent_id'    => $goalId,
                'group_id'     => self::GROUP_ID,
                'dependency_id'=> self::DEPENDENCY_ID,
                'user_id'      => self::USER_ID,
                'report_type'  => 'quantitative',
                '_lft'         => 0, '_rgt' => 0,
                'created_at'   => $now, 'updated_at' => $now,
            ]));

            // RACI
            foreach ($responsibles as $respId => $rol) {
                DB::table('planificacion.peis_profiles_has_responsibles')->insert([
                    'profile_id'     => $actionId,
                    'responsible_id' => $respId,
                    'rol'            => $rol,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            }
        }
    }

    // ══════════════════════════════════════════════════════════
    // § 4  IEA — actualiza análisis FODA existentes con datos
    //      de desempeño e inversión para calcular el IEA
    // ══════════════════════════════════════════════════════════
    private function seedIeaFoda(): void
    {
        // Toma los primeros 6 análisis del perfil FODA existente
        $analisis = DB::table('planificacion.foda_analisis')
            ->where('perfil_id', self::FODA_PERFIL_ID)
            ->limit(6)
            ->get();

        if ($analisis->isEmpty()) {
            $this->command->warn('No se encontraron análisis FODA para el perfil ' . self::FODA_PERFIL_ID . '. Saltando IEA.');
            return;
        }

        // Datos de prueba: pares (promedio_desempeno, inversion) → IEA resultante
        $datos = [
            [0.72, 0.90],  // IEA = 0.80 → neutro
            [0.35, 1.00],  // IEA = 0.35 → debilidad
            [0.90, 1.00],  // IEA = 0.90 → fortaleza
            [0.20, 0.80],  // IEA = 0.25 → debilidad
            [0.85, 0.95],  // IEA = 0.89 → fortaleza
            [0.50, 0.90],  // IEA = 0.55 → neutro
        ];

        foreach ($analisis as $i => $row) {
            if (!isset($datos[$i])) break;

            [$desempeno, $inversion] = $datos[$i];
            $iea = round($desempeno / $inversion, 4);

            $clasificacion = match(true) {
                $iea < 0.4  => 'debilidad',
                $iea > 0.8  => 'fortaleza',
                default     => 'neutro',
            };

            DB::table('planificacion.foda_analisis')
                ->where('id', $row->id)
                ->update([
                    'promedio_desempeno_6m'  => $desempeno,
                    'inversion_historica_6m' => $inversion,
                    'iea_valor'              => $iea,
                    'iea_clasificacion'      => $clasificacion,
                    'updated_at'             => Carbon::now(),
                ]);
        }

        $this->command->info('IEA calculado para ' . min(count($datos), $analisis->count()) . ' análisis FODA.');
    }
}
