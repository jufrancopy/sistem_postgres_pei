<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Estadistica\SiessExtracto;
use App\Models\Estadistica\SiessModulo;
use App\Models\Estadistica\SiessIndicador;
use App\Models\Estadistica\SiessPeriodo;
use App\Models\Estadistica\AopTrabajador;
use App\Models\Estadistica\JuBeneficiario;
use App\Models\Estadistica\DcpPresupuesto;
use App\Models\Estadistica\PlFinanciero;

class SiessSimulacionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Sembrando datos de simulación SIESS...');

        $departamentos = [
            ['codigo' => '01', 'nombre' => 'Concepción'],
            ['codigo' => '02', 'nombre' => 'San Pedro'],
            ['codigo' => '03', 'nombre' => 'Cordillera'],
            ['codigo' => '04', 'nombre' => 'Guairá'],
            ['codigo' => '05', 'nombre' => 'Caaguazú'],
            ['codigo' => '06', 'nombre' => 'Caazapá'],
            ['codigo' => '07', 'nombre' => 'Itapúa'],
            ['codigo' => '08', 'nombre' => 'Misiones'],
            ['codigo' => '09', 'nombre' => 'Paraguarí'],
            ['codigo' => '10', 'nombre' => 'Alto Paraná'],
            ['codigo' => '11', 'nombre' => 'Central'],
            ['codigo' => '12', 'nombre' => 'Ñeembucú'],
            ['codigo' => '13', 'nombre' => 'Amambay'],
            ['codigo' => '14', 'nombre' => 'Canindeyú'],
            ['codigo' => '15', 'nombre' => 'Presidente Hayes'],
            ['codigo' => '16', 'nombre' => 'Boquerón'],
            ['codigo' => '17', 'nombre' => 'Alto Paraguay'],
            ['codigo' => '00', 'nombre' => 'Asunción'],
        ];

        $conceptosJu = ['Vejez', 'Invalidez', 'Fallecimiento', 'Ley 4290/11', 'Ley 3404/07', 'Excombatiente'];

        // Sembrar para los últimos 6 meses
        $periodos = SiessPeriodo::where('tipo', 'mensual')
            ->orderByDesc('anio')->orderByDesc('mes')
            ->limit(6)->get();

        foreach ($periodos as $periodo) {
            $this->command->line("  → Período: {$periodo->nombre}");

            // ── AOP5: Trabajadores Activos ────────────────────────────────────
            $extractoAop = $this->crearExtracto('AOP', 'AOP5', $periodo->id,
                "Trabajadores activos al cierre de {$periodo->nombre}. Datos extraídos del sistema AOP.");

            $totalTrabajadores = rand(305000, 320000);
            $batch = [];
            for ($i = 0; $i < min($totalTrabajadores, 500); $i++) { // 500 registros de muestra
                $dep = $departamentos[array_rand($departamentos)];
                $tipoEmp = rand(0, 1) ? 'publico' : 'privado';
                $salario = $tipoEmp === 'publico'
                    ? rand(2500000, 8000000)
                    : rand(2229324, 6000000); // salario mínimo PY 2026
                $batch[] = [
                    'extracto_id'          => $extractoAop->id,
                    'periodo_id'           => $periodo->id,
                    'edad'                 => rand(18, 65),
                    'sexo'                 => rand(0, 1) ? 'M' : 'F',
                    'salario'              => $salario,
                    'tipo_empleado'        => $tipoEmp,
                    'tipo_seguro_codigo'   => rand(1, 5),
                    'tipo_seguro_descripcion' => 'Seguro General',
                    'regimen_codigo'       => rand(1, 3),
                    'regimen_descripcion'  => 'Régimen General',
                    'departamento_codigo'  => $dep['codigo'],
                    'departamento_nombre'  => $dep['nombre'],
                    'zona'                 => rand(0, 1) ? 'Urbana' : 'Rural',
                    'aporte_empleado'      => round($salario * 0.09, 2),
                    'aporte_patronal'      => round($salario * 0.145, 2),
                    'complemento_salud'    => round($salario * 0.015, 2),
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            }
            DB::connection('pgsql')->table('estadistica.aop_trabajadores')->insert($batch);

            // Recaudación
            DB::connection('pgsql')->table('estadistica.aop_recaudacion')->insert(
                array_map(fn($dep) => [
                    'extracto_id'          => $extractoAop->id,
                    'periodo_id'           => $periodo->id,
                    'departamento_codigo'  => $dep['codigo'],
                    'departamento_nombre'  => $dep['nombre'],
                    'distrito'             => $dep['nombre'] . ' Capital',
                    'monto_empleado'       => rand(500000000, 5000000000),
                    'monto_empleador'      => rand(800000000, 8000000000),
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ], $departamentos)
            );

            // Mora
            for ($m = 0; $m < rand(50, 150); $m++) {
                DB::connection('pgsql')->table('estadistica.aop_mora')->insert([
                    'extracto_id'               => $extractoAop->id,
                    'periodo_id'                => $periodo->id,
                    'empleador_ruc'             => rand(10000000, 99999999) . '-' . rand(0, 9),
                    'empleador_descripcion'     => 'Empresa Simulada ' . ($m + 1),
                    'monto_planillas_normales'  => rand(1000000, 50000000),
                    'monto_complementarias'     => rand(0, 5000000),
                    'monto_fraccionamiento'     => rand(0, 10000000),
                    'dias_mora'                 => rand(30, 365),
                    'created_at'               => now(),
                    'updated_at'               => now(),
                ]);
            }

            $extractoAop->aprobar(1, 'Datos simulados aprobados para pruebas.');

            // ── JU1: Beneficiarios ────────────────────────────────────────────
            $extractoJu = $this->crearExtracto('JU', 'JU1', $periodo->id,
                "Beneficiarios de jubilaciones y pensiones al cierre de {$periodo->nombre}.");

            $batchJu = [];
            for ($i = 0; $i < 300; $i++) {
                $dep = $departamentos[array_rand($departamentos)];
                $concepto = $conceptosJu[array_rand($conceptosJu)];
                $batchJu[] = [
                    'extracto_id'          => $extractoJu->id,
                    'periodo_id'           => $periodo->id,
                    'sexo'                 => rand(0, 1) ? 'M' : 'F',
                    'edad'                 => rand(55, 90),
                    'ciudad'               => $dep['nombre'],
                    'departamento_codigo'  => $dep['codigo'],
                    'departamento_nombre'  => $dep['nombre'],
                    'monto_bruto'          => rand(2229324, 15000000),
                    'concepto'             => $concepto,
                    'fecha_concesion'      => now()->subYears(rand(1, 20))->format('Y-m-d'),
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ];
            }
            DB::connection('pgsql')->table('estadistica.ju_beneficiarios')->insert($batchJu);

            // Relación activo/pasivo
            $activos = rand(300000, 320000);
            $pasivos = rand(75000, 85000);
            PlFinanciero::create([
                'extracto_id'   => $extractoJu->id,
                'periodo_id'    => $periodo->id,
                'total_activos' => $activos,
                'total_pasivos' => $pasivos,
            ]);

            // Altas y solicitudes
            foreach (['alta', 'solicitud'] as $tipo) {
                foreach (['Vejez', 'Invalidez', 'Fallecimiento'] as $concepto) {
                    DB::connection('pgsql')->table('estadistica.ju_altas_solicitudes')->insert([
                        'extracto_id'          => $extractoJu->id,
                        'periodo_id'           => $periodo->id,
                        'tipo'                 => $tipo,
                        'concepto'             => $concepto,
                        'cantidad'             => rand(50, 500),
                        'tiempo_promedio_dias' => rand(30, 180),
                        'tiempo_minimo_dias'   => rand(15, 30),
                        'tiempo_maximo_dias'   => rand(180, 365),
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ]);
                }
            }

            $extractoJu->aprobar(1, 'Datos simulados aprobados para pruebas.');

            // ── DCP: Ejecución Presupuestaria ─────────────────────────────────
            $extractoDt = $this->crearExtracto('DT', 'DCP1', $periodo->id,
                "Ejecución presupuestaria al cierre de {$periodo->nombre}.");

            $conceptosIngreso = ['Aportes Patronales', 'Aportes Personales', 'Rendimientos de Inversiones', 'Arrendamientos', 'Otros Ingresos'];
            $conceptosEgreso  = ['Prestaciones de Salud', 'Jubilaciones y Pensiones', 'Subsidios', 'Gastos Administrativos', 'Inversiones', 'Servicios Personales'];

            foreach ($conceptosIngreso as $concepto) {
                $presupuestado = rand(50000000000, 200000000000);
                DcpPresupuesto::create([
                    'extracto_id'   => $extractoDt->id,
                    'periodo_id'    => $periodo->id,
                    'tipo'          => 'ingreso',
                    'concepto'      => $concepto,
                    'objeto_gasto'  => null,
                    'presupuestado' => $presupuestado,
                    'ejecutado'     => round($presupuestado * (rand(60, 98) / 100)),
                ]);
            }

            foreach ($conceptosEgreso as $concepto) {
                $presupuestado = rand(30000000000, 180000000000);
                DcpPresupuesto::create([
                    'extracto_id'   => $extractoDt->id,
                    'periodo_id'    => $periodo->id,
                    'tipo'          => 'egreso',
                    'concepto'      => $concepto,
                    'objeto_gasto'  => null,
                    'presupuestado' => $presupuestado,
                    'ejecutado'     => round($presupuestado * (rand(55, 95) / 100)),
                ]);
            }

            // Tesorería
            foreach (['subsidios', 'proveedores', 'jubilados', 'depositos'] as $tipo) {
                DB::connection('pgsql')->table('estadistica.dt_tesoreria')->insert([
                    'extracto_id'         => $extractoDt->id,
                    'periodo_id'          => $periodo->id,
                    'tipo'                => $tipo,
                    'descripcion'         => 'Pagos ' . ucfirst($tipo) . ' - ' . $periodo->nombre,
                    'monto'               => rand(5000000000, 80000000000),
                    'cantidad_operaciones'=> rand(100, 50000),
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);
            }

            $extractoDt->aprobar(1, 'Datos simulados aprobados para pruebas.');
        }

        $this->command->info('✅ Simulación completada.');
        $this->command->table(
            ['Tabla', 'Registros'],
            [
                ['siess_extractos',    SiessExtracto::count()],
                ['aop_trabajadores',   DB::connection('pgsql')->table('estadistica.aop_trabajadores')->count()],
                ['ju_beneficiarios',   DB::connection('pgsql')->table('estadistica.ju_beneficiarios')->count()],
                ['dcp_presupuesto',    DB::connection('pgsql')->table('estadistica.dcp_presupuesto')->count()],
                ['pl_financiero',      DB::connection('pgsql')->table('estadistica.pl_financiero')->count()],
                ['dt_tesoreria',       DB::connection('pgsql')->table('estadistica.dt_tesoreria')->count()],
            ]
        );
    }

    private function crearExtracto(string $moduloCodigo, string $indicadorCodigo, int $periodoId, string $resumen): SiessExtracto
    {
        $modulo    = SiessModulo::where('codigo', $moduloCodigo)->first();
        $indicador = SiessIndicador::where('codigo', $indicadorCodigo)->first();

        // Evitar duplicados
        $existente = SiessExtracto::where('indicador_id', $indicador->id)
            ->where('periodo_id', $periodoId)
            ->first();

        if ($existente) {
            return $existente;
        }

        $extracto = SiessExtracto::create([
            'modulo_id'    => $modulo->id,
            'indicador_id' => $indicador->id,
            'periodo_id'   => $periodoId,
            'cargado_por'  => 1,
            'estado'       => SiessExtracto::ESTADO_BORRADOR,
            'resumen'      => $resumen,
        ]);

        $extracto->enviarAValidacion(1);

        return $extracto;
    }
}
