<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\PgnEstructura;
use App\Models\Planificacion\PgnNodo;
use App\Models\Planificacion\PeiAccionPgn;

class PeiDemoSeeder extends Seeder
{
    public function run(): void
    {
        $anio = 2026;

        // ── 1. Estructura PGN ─────────────────────────────────────────────────
        $niveles = [
            ['anio' => $anio, 'orden' => 1, 'nombre' => 'Programa',     'descripcion' => 'Nivel 1 del PGN'],
            ['anio' => $anio, 'orden' => 2, 'nombre' => 'Subprograma',  'descripcion' => 'Nivel 2 del PGN'],
            ['anio' => $anio, 'orden' => 3, 'nombre' => 'Proyecto',     'descripcion' => 'Nivel 3 del PGN'],
            ['anio' => $anio, 'orden' => 4, 'nombre' => 'Actividad',    'descripcion' => 'Nivel hoja — vinculable al PEI'],
        ];

        foreach ($niveles as $n) {
            PgnEstructura::firstOrCreate(['anio' => $n['anio'], 'nombre' => $n['nombre']], $n);
        }

        $ePrograma    = PgnEstructura::where(['anio' => $anio, 'nombre' => 'Programa'])->first();
        $eSubprograma = PgnEstructura::where(['anio' => $anio, 'nombre' => 'Subprograma'])->first();
        $eProyecto    = PgnEstructura::where(['anio' => $anio, 'nombre' => 'Proyecto'])->first();
        $eActividad   = PgnEstructura::where(['anio' => $anio, 'nombre' => 'Actividad'])->first();

        // ── 2. Árbol PGN ──────────────────────────────────────────────────────
        // Programa 14 — Salud
        $p14 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14'],
            ['pgn_estructura_id' => $ePrograma->id, 'parent_id' => null,
             'nombre' => 'Salud', 'monto_asignado_gs' => 1_200_000_000_000]
        );

        // Subprograma 14-01 — Atención Médica
        $sp1401 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-01'],
            ['pgn_estructura_id' => $eSubprograma->id, 'parent_id' => $p14->id,
             'nombre' => 'Atención Médica', 'monto_asignado_gs' => 800_000_000_000]
        );

        // Subprograma 14-02 — Administración y Finanzas
        $sp1402 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-02'],
            ['pgn_estructura_id' => $eSubprograma->id, 'parent_id' => $p14->id,
             'nombre' => 'Administración y Finanzas', 'monto_asignado_gs' => 400_000_000_000]
        );

        // Proyectos bajo 14-01
        $pr140101 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-01-01'],
            ['pgn_estructura_id' => $eProyecto->id, 'parent_id' => $sp1401->id,
             'nombre' => 'Fortalecimiento de la Red Hospitalaria', 'monto_asignado_gs' => 500_000_000_000]
        );
        $pr140102 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-01-02'],
            ['pgn_estructura_id' => $eProyecto->id, 'parent_id' => $sp1401->id,
             'nombre' => 'Atención Primaria de Salud', 'monto_asignado_gs' => 300_000_000_000]
        );

        // Proyectos bajo 14-02
        $pr140201 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-02-01'],
            ['pgn_estructura_id' => $eProyecto->id, 'parent_id' => $sp1402->id,
             'nombre' => 'Modernización de Sistemas de Gestión', 'monto_asignado_gs' => 250_000_000_000]
        );

        // Actividades (nodos hoja — vinculables al PEI)
        $act001 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-01-01-001'],
            ['pgn_estructura_id' => $eActividad->id, 'parent_id' => $pr140101->id,
             'nombre' => 'Equipamiento de Hospitales Regionales', 'monto_asignado_gs' => 200_000_000_000]
        );
        $act002 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-01-01-002'],
            ['pgn_estructura_id' => $eActividad->id, 'parent_id' => $pr140101->id,
             'nombre' => 'Infraestructura Hospitalaria — Ampliaciones', 'monto_asignado_gs' => 300_000_000_000]
        );
        $act003 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-01-02-001'],
            ['pgn_estructura_id' => $eActividad->id, 'parent_id' => $pr140102->id,
             'nombre' => 'Clínicas Periféricas — Fortalecimiento', 'monto_asignado_gs' => 180_000_000_000]
        );
        $act004 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-01-02-002'],
            ['pgn_estructura_id' => $eActividad->id, 'parent_id' => $pr140102->id,
             'nombre' => 'Programas de Salud Preventiva', 'monto_asignado_gs' => 120_000_000_000]
        );
        $act005 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-02-01-001'],
            ['pgn_estructura_id' => $eActividad->id, 'parent_id' => $pr140201->id,
             'nombre' => 'Digitalización de Expedientes Clínicos', 'monto_asignado_gs' => 150_000_000_000]
        );
        $act006 = PgnNodo::firstOrCreate(
            ['anio' => $anio, 'codigo' => '14-02-01-002'],
            ['pgn_estructura_id' => $eActividad->id, 'parent_id' => $pr140201->id,
             'nombre' => 'Sistema de Gestión de RRHH', 'monto_asignado_gs' => 100_000_000_000]
        );

        // ── 3. PEI raíz ───────────────────────────────────────────────────────
        $nivelLabel = json_encode(PeiProfile::modelosDeNiveles()['MECIP']);

        $peiRaiz = PeiProfile::firstOrCreate(
            ['level' => 'master', 'type' => 'institucional', 'parent_id' => null,
             'year_start' => '2024-01-01', 'year_end' => '2028-12-31'],
            [
                'name'        => 'Plan Estratégico Institucional IPS 2024–2028',
                'mision'      => 'Garantizar, oportuna y eficientemente, las prestaciones del Seguro Social, con calidad y calidez en el servicio, a nuestros asegurados y beneficiarios.',
                'vision'      => 'Ser la institución líder en la administración del Seguro Social, con amplia cobertura, sostenibilidad financiera y excelencia en la gestión.',
                'values'      => '<ul><li>Transparencia</li><li>Eficiencia</li><li>Equidad</li><li>Compromiso Social</li><li>Innovación</li></ul>',
                'type'        => 'institucional',
                'level'       => 'master',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
                'order_item'  => 1,
            ]
        );

        // ── 4. Objetivos Estratégicos (axi) ───────────────────────────────────
        $eje1 = PeiProfile::firstOrCreate(
            ['level' => 'axi', 'parent_id' => $peiRaiz->id, 'order_item' => 1],
            [
                'name'        => 'Fortalecer la Red de Servicios de Salud con cobertura universal y calidad asistencial',
                'type'        => 'institucional',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
            ]
        );

        $eje2 = PeiProfile::firstOrCreate(
            ['level' => 'axi', 'parent_id' => $peiRaiz->id, 'order_item' => 2],
            [
                'name'        => 'Modernizar la gestión institucional mediante tecnología e innovación',
                'type'        => 'institucional',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
            ]
        );

        $eje3 = PeiProfile::firstOrCreate(
            ['level' => 'axi', 'parent_id' => $peiRaiz->id, 'order_item' => 3],
            [
                'name'        => 'Garantizar la sostenibilidad financiera y la transparencia en el uso de los recursos',
                'type'        => 'institucional',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
            ]
        );

        // ── 5. Metas (goal) ───────────────────────────────────────────────────
        $meta11 = PeiProfile::firstOrCreate(
            ['level' => 'goal', 'parent_id' => $eje1->id, 'order_item' => 1],
            [
                'name'        => 'Ampliar la capacidad instalada hospitalaria en un 30% al 2028',
                'type'        => 'institucional',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
            ]
        );

        $meta12 = PeiProfile::firstOrCreate(
            ['level' => 'goal', 'parent_id' => $eje1->id, 'order_item' => 2],
            [
                'name'        => 'Incrementar la cobertura de atención primaria en zonas periféricas',
                'type'        => 'institucional',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
            ]
        );

        $meta21 = PeiProfile::firstOrCreate(
            ['level' => 'goal', 'parent_id' => $eje2->id, 'order_item' => 1],
            [
                'name'        => 'Digitalizar el 100% de los expedientes clínicos al 2027',
                'type'        => 'institucional',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
            ]
        );

        $meta31 = PeiProfile::firstOrCreate(
            ['level' => 'goal', 'parent_id' => $eje3->id, 'order_item' => 1],
            [
                'name'        => 'Optimizar la ejecución presupuestaria con índice ≥ 85% anual',
                'type'        => 'institucional',
                'nivel_label' => $nivelLabel,
                'dependency_id' => 1,
                'user_id'     => 1,
            ]
        );

        // ── 6. Acciones (action) ──────────────────────────────────────────────
        $acciones = [
            // Meta 1.1 — Capacidad hospitalaria
            [
                'parent' => $meta11, 'order' => 1,
                'name'   => 'Adquisición e instalación de equipamiento médico de alta complejidad en hospitales regionales',
                'indicator' => 'N° de equipos instalados / N° de equipos planificados',
                'baseline'  => '45 equipos instalados (2023)',
                'target'    => '120 equipos al 2028',
                'progress'  => 60, 'denominator' => 120, 'numerator' => 60,
                'semaforo'  => 'amarillo',
                'pgn_nodo'  => $act001, 'monto_vinculado' => 200_000_000_000, 'monto_ejecutado' => 95_000_000_000,
                'resultado' => 'Resultado 1.1.1 — Equipamiento hospitalario modernizado',
            ],
            [
                'parent' => $meta11, 'order' => 2,
                'name'   => 'Construcción y ampliación de infraestructura en hospitales de referencia',
                'indicator' => 'M² construidos / M² planificados',
                'baseline'  => '0 m² (2023)',
                'target'    => '15.000 m² al 2028',
                'progress'  => 30, 'denominator' => 100, 'numerator' => 30,
                'semaforo'  => 'rojo',
                'pgn_nodo'  => $act002, 'monto_vinculado' => 300_000_000_000, 'monto_ejecutado' => 45_000_000_000,
                'resultado' => 'Resultado 1.1.2 — Infraestructura hospitalaria ampliada',
            ],
            // Meta 1.2 — Atención primaria
            [
                'parent' => $meta12, 'order' => 1,
                'name'   => 'Fortalecimiento de clínicas periféricas con personal especializado y equipamiento básico',
                'indicator' => 'N° de clínicas fortalecidas / Total de clínicas periféricas',
                'baseline'  => '12 clínicas (2023)',
                'target'    => '35 clínicas al 2028',
                'progress'  => 88, 'denominator' => 35, 'numerator' => 31,
                'semaforo'  => 'verde',
                'pgn_nodo'  => $act003, 'monto_vinculado' => 180_000_000_000, 'monto_ejecutado' => 160_000_000_000,
                'resultado' => 'Resultado 1.2.1 — Clínicas periféricas fortalecidas',
            ],
            [
                'parent' => $meta12, 'order' => 2,
                'name'   => 'Implementación de programas de salud preventiva y vacunación masiva',
                'indicator' => '% de población asegurada con cobertura preventiva',
                'baseline'  => '42% (2023)',
                'target'    => '75% al 2028',
                'progress'  => 55, 'denominator' => 75, 'numerator' => 41,
                'semaforo'  => 'amarillo',
                'pgn_nodo'  => $act004, 'monto_vinculado' => 120_000_000_000, 'monto_ejecutado' => 66_000_000_000,
                'resultado' => 'Resultado 1.2.2 — Cobertura preventiva ampliada',
            ],
            // Meta 2.1 — Digitalización
            [
                'parent' => $meta21, 'order' => 1,
                'name'   => 'Implementación del sistema de Historia Clínica Electrónica (HCE) en todos los centros',
                'indicator' => '% de centros con HCE operativa',
                'baseline'  => '10% (2023)',
                'target'    => '100% al 2027',
                'progress'  => 90, 'denominator' => 100, 'numerator' => 90,
                'semaforo'  => 'verde',
                'pgn_nodo'  => $act005, 'monto_vinculado' => 150_000_000_000, 'monto_ejecutado' => 138_000_000_000,
                'resultado' => 'Resultado 2.1.1 — HCE implementada en red de salud',
            ],
            [
                'parent' => $meta21, 'order' => 2,
                'name'   => 'Modernización del sistema de gestión de Recursos Humanos y nómina',
                'indicator' => '% de procesos de RRHH digitalizados',
                'baseline'  => '25% (2023)',
                'target'    => '90% al 2027',
                'progress'  => 70, 'denominator' => 90, 'numerator' => 63,
                'semaforo'  => 'amarillo',
                'pgn_nodo'  => $act006, 'monto_vinculado' => 100_000_000_000, 'monto_ejecutado' => 70_000_000_000,
                'resultado' => 'Resultado 2.1.2 — Gestión de RRHH modernizada',
            ],
            // Meta 3.1 — Ejecución presupuestaria
            [
                'parent' => $meta31, 'order' => 1,
                'name'   => 'Implementación de tablero de control presupuestario con alertas tempranas',
                'indicator' => '% de ejecución presupuestaria anual',
                'baseline'  => '72% (2023)',
                'target'    => '≥ 85% anual',
                'progress'  => 85, 'denominator' => 85, 'numerator' => 85,
                'semaforo'  => 'verde',
                'pgn_nodo'  => null, 'monto_vinculado' => null, 'monto_ejecutado' => null,
                'resultado' => null,
            ],
        ];

        foreach ($acciones as $a) {
            $accion = PeiProfile::firstOrCreate(
                ['level' => 'action', 'parent_id' => $a['parent']->id, 'order_item' => $a['order']],
                [
                    'name'        => $a['name'],
                    'indicator'   => $a['indicator'],
                    'baseline'    => $a['baseline'],
                    'target'      => $a['target'],
                    'progress'    => $a['progress'],
                    'denominator' => $a['denominator'],
                    'numerator'   => $a['numerator'],
                    'semaforo'    => $a['semaforo'],
                    'type'        => 'institucional',
                    'nivel_label' => $nivelLabel,
                    'dependency_id' => 1,
                    'user_id'     => 1,
                ]
            );

            // Vincular al PGN si tiene nodo
            if ($a['pgn_nodo']) {
                PeiAccionPgn::firstOrCreate(
                    ['pei_profile_id' => $accion->id, 'pgn_nodo_id' => $a['pgn_nodo']->id],
                    [
                        'resultado'          => $a['resultado'],
                        'monto_vinculado_gs' => $a['monto_vinculado'],
                        'monto_ejecutado_gs' => $a['monto_ejecutado'],
                    ]
                );
            }
        }

        $this->command->info('✅ PEI Demo creado: ' . $peiRaiz->name);
        $this->command->info('   Objetivos: 3 | Metas: 4 | Acciones: 7 | Nodos PGN: 10');
    }
}
