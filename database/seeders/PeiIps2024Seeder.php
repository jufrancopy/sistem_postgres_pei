<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * PeiIps2024Seeder — PEI IPS 2024-2028
 * Basado en la Hoja 12 — Formulación Estratégica Integrada
 * 3 Resultados Intermedios · 6 Objetivos Estratégicos · 26 Acciones
 */
class PeiIps2024Seeder extends Seeder
{
    private Carbon $now;
    private string $nivelLabel;

    const ORG_IPS   = 26;
    const USER_ID   = 201;
    // Se genera dinámicamente — ver crearOObtenerMaster()
    private string $masterId;

    public function run(): void
    {
        $this->now = Carbon::now();
        $this->nivelLabel = json_encode([
            'master' => 'PEI',
            'axi'    => 'Objetivo Estratégico',
            'goal'   => 'Acción Estratégica',
            'action' => 'Acción',
        ]);

        $this->masterId = $this->crearOObtenerMaster();
        $this->command->info('── Construyendo PEI IPS 2024-2028 (Hoja 12)...');
        $this->command->info('   Master ID: ' . $this->masterId);

        $this->actualizarMaster();
        $this->ri1_salud();
        $this->ri2_prestaciones();
        $this->ri3_gestion();
        \App\Admin\Planificacion\Pei\PeiProfile::fixTree();
        $this->command->info('✅ PEI IPS 2024-2028 completo.');
    }

    private function crearOObtenerMaster(): string
    {
        // Buscar si ya existe un perfil raíz con este nombre exacto
        $existe = DB::table('planificacion.pei_profiles')
            ->where('level', 'master')
            ->where('name', 'like', '%Plan Estratégico Institucional IPS 2024%')
            ->whereNull('deleted_at')
            ->whereNull('parent_id')
            ->first();

        if ($existe) {
            $this->command->info('   Perfil existente encontrado: ' . $existe->id);
            return $existe->id;
        }

        // Crear uno nuevo
        $id = (string) \Illuminate\Support\Str::uuid();
        DB::table('planificacion.pei_profiles')->insert([
            'id'             => $id,
            'name'           => 'Plan Estratégico Institucional IPS 2024–2028',
            'level'          => 'master',
            'type'           => 'corporative',
            'year_start'     => '2024-01-01',
            'year_end'       => '2028-12-31',
            'report_type'    => 'quantitative',
            'user_id'        => self::USER_ID,
            'dependency_id'  => self::ORG_IPS,
            'group_id'       => 50,
            'foda_perfil_id' => '9d7aa6a5-badb-488a-89a7-19a40c35107c',
            'nivel_label'    => $this->nivelLabel,
            'ri_metas'       => '[]',
            '_lft'           => 0, '_rgt' => 0,
            'created_at'     => $this->now,
            'updated_at'     => $this->now,
        ]);
        $this->command->info('   Nuevo perfil creado: ' . $id);
        return $id;
    }

    private function actualizarMaster(): void
    {
        DB::table('planificacion.pei_profiles')->where('id', $this->masterId)->update([
            'nivel_label' => $this->nivelLabel,
            'mision'      => '<p>Garantizar, oportuna y eficientemente, las prestaciones del Seguro Social con calidad y calidez, contribuyendo al bienestar de los asegurados, jubilados, pensionados y sus familias.</p>',
            'vision'      => '<p>Ser la institución líder en seguridad social del Paraguay, con amplia cobertura, gestión eficiente y tecnología de vanguardia al servicio de la ciudadanía.</p>',
            'values'      => '<p><strong>Transparencia</strong> · <strong>Eficiencia</strong> · <strong>Equidad</strong> · <strong>Innovación</strong> · <strong>Integridad</strong> · <strong>Compromiso Social</strong></p>',
            'updated_at'  => $this->now,
        ]);
    }

    // ══ RI 1: Salud ════════════════════════════════════════════════════════════
    private function ri1_salud(): void
    {
        $riMetas1 = json_encode([
            ['anio'=>2024,'valor'=>'Reducir 5% vs LB'],['anio'=>2025,'valor'=>'Reducir 8% vs LB'],
            ['anio'=>2026,'valor'=>'Reducir 12% vs LB'],['anio'=>2027,'valor'=>'Reducir 15% vs LB'],
            ['anio'=>2028,'valor'=>'Reducir 20% vs LB'],
        ]);
        $riComun1 = [
            'resultado_intermedio' => 'Asegurados y beneficiarios acceden a servicios de salud',
            'ri_presupuestario'    => 'Trabajadores dependientes que aportan al seguro social, reciben prestaciones sanitarias, así como prestaciones económicas a corto plazo (reposos, maternidad, accidente de trabajo)',
            'ri_programa'          => 'Programa, Central — Actividad: Servicios de Prestaciones Sanitarias',
            'ri_recursos_gs'       => 6341255316165,
        ];

        // ── OE 1 ──────────────────────────────────────────────────────────────
        $oe1 = $this->perfil(array_merge($riComun1, [
            'name'            => '<p><strong>Desarrollar la red integrada e integral de servicios de salud con enfoque preventivo y de calidad para los asegurados y beneficiarios.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 1,
            'bsc_perspectiva' => 'clientes',
            'ri_metas'        => $riMetas1,
        ]));

        // Acción 1 — IPS-001
        $this->accion($oe1, 1, 1,
            'Mejorar la atención obstétrica para disminuir la muerte materna institucional.');
        // Acción 2 — IPS-002
        $this->accion($oe1, 2, 2,
            'Mejorar la atención neonatal para disminuir la mortalidad neonatal institucional.');
        // Acciones 3 y 4 comparten la misma acción estratégica — IPS-003 e IPS-004
        $this->accion($oe1, 3, 3,
            'Implementar Redes Temáticas Integradas (Salud Mental, Bucal, Diabetes, Hipertensión) y asegurar cobertura de vacunación.');
        $this->accion($oe1, 4, 4,
            'Implementar Redes Temáticas Integradas (Salud Mental, Bucal, Diabetes, Hipertensión) y asegurar cobertura de vacunación.');
        // Acción 5 — IPS-005
        $this->accion($oe1, 5, 5,
            'Adecuar los programas y servicios de salud a las necesidades del curso de la vida, priorizando infancia, adolescencia y personas mayores.');
        // Acción 6 — IPS-006
        $this->accion($oe1, 6, 6,
            'Impulsar trabajos de investigación científica en los establecimientos de la RIISS.');

        // ── OE 2 ──────────────────────────────────────────────────────────────
        $riMetas2 = json_encode([
            ['anio'=>2024,'valor'=>'70%'],['anio'=>2025,'valor'=>'78%'],
            ['anio'=>2026,'valor'=>'85%'],['anio'=>2027,'valor'=>'90%'],['anio'=>2028,'valor'=>'95%'],
        ]);
        $oe2 = $this->perfil(array_merge($riComun1, [
            'name'            => '<p><strong>Optimizar la gestión médica, logística y planificación de los servicios de salud en la red de establecimientos sanitarios.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 2,
            'bsc_perspectiva' => 'clientes',
            'ri_metas'        => $riMetas2,
        ]));

        // Acción 7 — IPS-007
        $this->accion($oe2, 1, 7,
            'Asegurar la trazabilidad total y el abastecimiento oportuno mediante el sistema informático.');
        // Acción 8 — IPS-008
        $this->accion($oe2, 2, 8,
            'Actualizar protocolos de atención y planes operativos según criterios epidemiológicos.');
        // Acción 9 — IPS-009
        $this->accion($oe2, 3, 9,
            'Expandir el Expediente Electrónico, Telemedicina y Resultados Digitales en toda la red.');
    }

    // ══ RI 2: Prestaciones Económicas ══════════════════════════════════════════
    private function ri2_prestaciones(): void
    {
        $riComun2 = [
            'resultado_intermedio' => 'Jubilados y Pensionados reciben sus haberes jubilatorios',
            'ri_presupuestario'    => 'Jubilados y Pensionados reciben sus haberes jubilatorios',
            'ri_programa'          => 'Programa, Central — Actividad: Gestión para Jubilados y Pensionados',
            'ri_recursos_gs'       => 6385106032434,
        ];

        // ── OE 3 ──────────────────────────────────────────────────────────────
        $riMetas3 = json_encode([
            ['anio'=>2024,'valor'=>'75%'],['anio'=>2025,'valor'=>'80%'],
            ['anio'=>2026,'valor'=>'85%'],['anio'=>2027,'valor'=>'90%'],['anio'=>2028,'valor'=>'95%'],
        ]);
        $oe3 = $this->perfil(array_merge($riComun2, [
            'name'            => '<p><strong>Asegurar la administración y el acceso a las prestaciones económicas de trabajadores, jubilados y pensionados.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 3,
            'bsc_perspectiva' => 'financiera',
            'ri_metas'        => $riMetas3,
        ]));

        $this->accion($oe3, 1, 10, 'Concesión oportuna, automatizada y descentralizada de prestaciones económicas del Seguro Social.');
        $this->accion($oe3, 2, 11, 'Elaborar proyectos de reformas legales para el financiamiento equitativo de las prestaciones.');
        $this->accion($oe3, 3, 12, 'Garantizar acceso a información clara sobre derechos previsionales a través de canales digitales.');

        // ── OE 4 ──────────────────────────────────────────────────────────────
        $riMetas4 = json_encode([
            ['anio'=>2024,'valor'=>'Rentabilidad real positiva'],['anio'=>2025,'valor'=>'LB + 0,5 p.p.'],
            ['anio'=>2026,'valor'=>'LB + 1,0 p.p.'],['anio'=>2027,'valor'=>'LB + 1,5 p.p.'],['anio'=>2028,'valor'=>'Mantener rentabilidad real positiva'],
        ]);
        $oe4 = $this->perfil(array_merge($riComun2, [
            'name'            => '<p><strong>Garantizar la sostenibilidad financiera de los fondos del seguro social.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 4,
            'bsc_perspectiva' => 'financiera',
            'ri_metas'        => $riMetas4,
        ]));

        $this->accion($oe4, 1, 13, 'Diversificar e invertir las reservas técnicas bajo criterios de seguridad, liquidez y rentabilidad.');
        $this->accion($oe4, 2, 14, 'Implementar el Sistema de Gestión Inmobiliaria y mejorar la fiscalización de contratos.');
        $this->accion($oe4, 3, 15, 'Aumentar la recaudación del Aporte Obrero Patronal mediante vínculos interinstitucionales.');
    }

    // ══ RI 3: Gestión Administrativa ═══════════════════════════════════════════
    private function ri3_gestion(): void
    {
        $riComun3 = [
            'resultado_intermedio' => 'Administración institucional eficiente con talento humano profesionalizado y recursos optimizados',
            'ri_presupuestario'    => 'Administración institucional eficiente',
            'ri_programa'          => 'Programa, Central — Actividad: GESTIÓN ADMINISTRATIVA INSTITUCIONAL',
            'ri_recursos_gs'       => 467065260973,
        ];

        // ── OE 5 ──────────────────────────────────────────────────────────────
        $riMetas5 = json_encode([
            ['anio'=>2024,'valor'=>'40%'],['anio'=>2025,'valor'=>'55%'],
            ['anio'=>2026,'valor'=>'70%'],['anio'=>2027,'valor'=>'85%'],['anio'=>2028,'valor'=>'100%'],
        ]);
        $oe5 = $this->perfil(array_merge($riComun3, [
            'name'            => '<p><strong>Alinear la planificación estratégica, gestión por procesos y control interno (MECIP) con la transformación digital de la institución.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 5,
            'bsc_perspectiva' => 'procesos',
            'ri_metas'        => $riMetas5,
        ]));

        $this->accion($oe5, 1, 16, 'Implementar el Plan de Trabajo de las Normas de Requisitos Mínimos MECIP y fortalecer la cultura de control interno.');
        $this->accion($oe5, 2, 17, 'Analizar, rediseñar y documentar las estructuras organizacionales y los procesos institucionales orientados a resultados.');
        // Acciones 18 y 19 comparten la misma acción estratégica
        $this->accion($oe5, 3, 18, 'Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC para el resguardo de información.');
        $this->accion($oe5, 4, 19, 'Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC para el resguardo de información.');

        // ── OE 6 ──────────────────────────────────────────────────────────────
        $riMetas6 = json_encode([
            ['anio'=>2024,'valor'=>'40%'],['anio'=>2025,'valor'=>'60%'],
            ['anio'=>2026,'valor'=>'75%'],['anio'=>2027,'valor'=>'90%'],['anio'=>2028,'valor'=>'100%'],
        ]);
        $oe6 = $this->perfil(array_merge($riComun3, [
            'name'            => '<p><strong>Mejorar la eficiencia de la gestión administrativa, el desarrollo del talento humano y el uso de los recursos institucionales.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 6,
            'bsc_perspectiva' => 'aprendizaje',
            'ri_metas'        => $riMetas6,
        ]));

        $this->accion($oe6, 1, 20, 'Implementar Concursos de Méritos para el ingreso, procesos de inducción y fortalecer el Sistema de Carrera del Talento humano del IPS.');
        $this->accion($oe6, 2, 21, 'Optimizar la ejecución del Programa Anual de Contrataciones (PAC) y simplificar los procesos de adquisición de bienes y servicios según la normativa vigente.');
        // Acciones 22 y 23 comparten la misma acción estratégica
        $this->accion($oe6, 3, 22, 'Reducir la morosidad y evasión mediante vínculos interinstitucionales y fiscalización a empresas.');
        $this->accion($oe6, 4, 23, 'Reducir la morosidad y evasión mediante vínculos interinstitucionales y fiscalización a empresas.');
        $this->accion($oe6, 5, 24, 'Gestionar los proyectos de infraestructura y asegurar el mantenimiento, custodia de los bienes institucionales.');
    }

    // ── HELPERS ───────────────────────────────────────────────────────────────
    private function perfil(array $data): string
    {
        $id = (string) Str::uuid();
        DB::table('planificacion.pei_profiles')->insert(array_merge([
            'id'           => $id,
            'type'         => 'corporative',
            'year_start'   => '2024-01-01',
            'year_end'     => '2028-12-31',
            'report_type'  => 'quantitative',
            'user_id'      => self::USER_ID,
            'dependency_id'=> 26,
            'nivel_label'  => $this->nivelLabel,
            'ri_metas'     => '[]',
            '_lft'         => 0,
            '_rgt'         => 0,
            'created_at'   => $this->now,
            'updated_at'   => $this->now,
        ], $data));
        return $id;
    }

    private function accion(string $parentId, int $orden, int $indicadorId, string $nombre): void
    {
        // 1. Crear goal (Acción Estratégica — nivel 2)
        $goalId = $this->perfil([
            'name'       => '<p>' . $nombre . '</p>',
            'level'      => 'goal',
            'parent_id'  => $parentId,
            'order_item' => $orden,
        ]);

        // 2. Crear action hijo (nivel hoja — donde vive el indicador y el reporte)
        $actionId = $this->perfil([
            'name'       => '<p>' . $nombre . '</p>',
            'level'      => 'action',
            'parent_id'  => $goalId,
            'order_item' => 1,
        ]);

        // 3. Vincular indicador solo si existe
        $existe = \DB::table('planificacion.indicadores')
            ->where('id', $indicadorId)->exists();
        if ($existe) {
            \DB::table('planificacion.pei_profiles')
                ->where('id', $actionId)
                ->update(['indicador_id' => $indicadorId]);
        }
    }
}
