<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * PeiIps2024Seeder — PEI IPS 2024-2028
 * Jerarquía: master → axi (Objetivo Estratégico) → goal (Objetivo Específico) → action (Acción Estratégica)
 */
class PeiIps2024Seeder extends Seeder
{
    private Carbon $now;
    private string $nivelLabel;

    const ORG_IPS  = 26;
    const USER_ID  = 201;
    private string $masterId;

    public function run(): void
    {
        $this->now = Carbon::now();
        $this->nivelLabel = json_encode([
            'master' => 'PEI',
            'axi'    => 'Objetivo Estratégico',
            'goal'   => 'Objetivo Específico',
            'action' => 'Acción Estratégica',
        ]);

        $this->masterId = $this->crearOObtenerMaster();
        $this->command->info('── Construyendo PEI IPS 2024-2028...');
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

        $id = (string) Str::uuid();
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
            'ri_metas'        => json_encode([
                ['anio'=>2024,'valor'=>'Reducir 5% vs LB'],['anio'=>2025,'valor'=>'Reducir 8% vs LB'],
                ['anio'=>2026,'valor'=>'Reducir 12% vs LB'],['anio'=>2027,'valor'=>'Reducir 15% vs LB'],
                ['anio'=>2028,'valor'=>'Reducir 20% vs LB'],
            ]),
        ]));

        // OEsp 1.1 — Mortalidad materna y neonatal
        $oesp11 = $this->goal($oe1, 1, 'Reducir la mortalidad materna, neonatal e infantil en los establecimientos de la RIISS.');
        $this->accion($oesp11, 1, 1, 'Mejorar la atención obstétrica para disminuir la muerte materna institucional.');
        $this->accion($oesp11, 2, 2, 'Mejorar la atención neonatal para disminuir la mortalidad neonatal institucional.');

        // OEsp 1.2 — Redes temáticas y cobertura preventiva
        $oesp12 = $this->goal($oe1, 2, 'Fortalecer las redes temáticas de salud y la cobertura preventiva a lo largo del curso de vida.');
        $this->accion($oesp12, 1, 3, 'Implementar Redes Temáticas Integradas (Salud Mental, Bucal, Diabetes, Hipertensión) y asegurar cobertura de vacunación.');
        $this->accion($oesp12, 2, 4, 'Implementar Redes Temáticas Integradas (Salud Mental, Bucal, Diabetes, Hipertensión) y asegurar cobertura de vacunación.');
        $this->accion($oesp12, 3, 5, 'Adecuar los programas y servicios de salud a las necesidades del curso de la vida, priorizando infancia, adolescencia y personas mayores.');

        // OEsp 1.3 — Investigación científica
        $oesp13 = $this->goal($oe1, 3, 'Promover la investigación científica en los establecimientos de la RIISS.');
        $this->accion($oesp13, 1, 6, 'Impulsar trabajos de investigación científica en los establecimientos de la RIISS.');

        // ── OE 2 ──────────────────────────────────────────────────────────────
        $oe2 = $this->perfil(array_merge($riComun1, [
            'name'            => '<p><strong>Optimizar la gestión médica, logística y planificación de los servicios de salud en la red de establecimientos sanitarios.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 2,
            'bsc_perspectiva' => 'clientes',
            'ri_metas'        => json_encode([
                ['anio'=>2024,'valor'=>'70%'],['anio'=>2025,'valor'=>'78%'],
                ['anio'=>2026,'valor'=>'85%'],['anio'=>2027,'valor'=>'90%'],['anio'=>2028,'valor'=>'95%'],
            ]),
        ]));

        // OEsp 2.1 — Abastecimiento y trazabilidad
        $oesp21 = $this->goal($oe2, 1, 'Garantizar el abastecimiento oportuno y la trazabilidad de insumos médicos en toda la red.');
        $this->accion($oesp21, 1, 7, 'Asegurar la trazabilidad total y el abastecimiento oportuno mediante el sistema informático.');

        // OEsp 2.2 — Protocolos y digitalización
        $oesp22 = $this->goal($oe2, 2, 'Actualizar protocolos de atención y expandir la digitalización de los servicios de salud.');
        $this->accion($oesp22, 1, 8, 'Actualizar protocolos de atención y planes operativos según criterios epidemiológicos.');
        $this->accion($oesp22, 2, 9, 'Expandir el Expediente Electrónico, Telemedicina y Resultados Digitales en toda la red.');
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
        $oe3 = $this->perfil(array_merge($riComun2, [
            'name'            => '<p><strong>Asegurar la administración y el acceso a las prestaciones económicas de trabajadores, jubilados y pensionados.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 3,
            'bsc_perspectiva' => 'financiera',
            'ri_metas'        => json_encode([
                ['anio'=>2024,'valor'=>'75%'],['anio'=>2025,'valor'=>'80%'],
                ['anio'=>2026,'valor'=>'85%'],['anio'=>2027,'valor'=>'90%'],['anio'=>2028,'valor'=>'95%'],
            ]),
        ]));

        // OEsp 3.1 — Modernizar acceso a prestaciones
        $oesp31 = $this->goal($oe3, 1, 'Modernizar y descentralizar el acceso a las prestaciones económicas del seguro social.');
        $this->accion($oesp31, 1, 10, 'Concesión oportuna, automatizada y descentralizada de prestaciones económicas del Seguro Social.');
        $this->accion($oesp31, 2, 12, 'Garantizar acceso a información clara sobre derechos previsionales a través de canales digitales.');

        // OEsp 3.2 — Marco legal y financiero
        $oesp32 = $this->goal($oe3, 2, 'Fortalecer el marco legal y financiero para el financiamiento equitativo de las prestaciones.');
        $this->accion($oesp32, 1, 11, 'Elaborar proyectos de reformas legales para el financiamiento equitativo de las prestaciones.');

        // ── OE 4 ──────────────────────────────────────────────────────────────
        $oe4 = $this->perfil(array_merge($riComun2, [
            'name'            => '<p><strong>Garantizar la sostenibilidad financiera de los fondos del seguro social.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 4,
            'bsc_perspectiva' => 'financiera',
            'ri_metas'        => json_encode([
                ['anio'=>2024,'valor'=>'Rentabilidad real positiva'],['anio'=>2025,'valor'=>'LB + 0,5 p.p.'],
                ['anio'=>2026,'valor'=>'LB + 1,0 p.p.'],['anio'=>2027,'valor'=>'LB + 1,5 p.p.'],['anio'=>2028,'valor'=>'Mantener rentabilidad real positiva'],
            ]),
        ]));

        // OEsp 4.1 — Gestión de reservas e inversiones
        $oesp41 = $this->goal($oe4, 1, 'Optimizar la gestión de reservas técnicas e inversiones institucionales.');
        $this->accion($oesp41, 1, 13, 'Diversificar e invertir las reservas técnicas bajo criterios de seguridad, liquidez y rentabilidad.');
        $this->accion($oesp41, 2, 14, 'Implementar el Sistema de Gestión Inmobiliaria y mejorar la fiscalización de contratos.');

        // OEsp 4.2 — Recaudación y evasión
        $oesp42 = $this->goal($oe4, 2, 'Incrementar la recaudación del Aporte Obrero Patronal y reducir la evasión.');
        $this->accion($oesp42, 1, 15, 'Aumentar la recaudación del Aporte Obrero Patronal mediante vínculos interinstitucionales.');
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
        $oe5 = $this->perfil(array_merge($riComun3, [
            'name'            => '<p><strong>Alinear la planificación estratégica, gestión por procesos y control interno (MECIP) con la transformación digital de la institución.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 5,
            'bsc_perspectiva' => 'procesos',
            'ri_metas'        => json_encode([
                ['anio'=>2024,'valor'=>'40%'],['anio'=>2025,'valor'=>'55%'],
                ['anio'=>2026,'valor'=>'70%'],['anio'=>2027,'valor'=>'85%'],['anio'=>2028,'valor'=>'100%'],
            ]),
        ]));

        // OEsp 5.1 — Control interno y gestión por procesos
        $oesp51 = $this->goal($oe5, 1, 'Fortalecer el control interno institucional y la gestión por procesos orientada a resultados (MECIP).');
        $this->accion($oesp51, 1, 16, 'Implementar el Plan de Trabajo de las Normas de Requisitos Mínimos MECIP y fortalecer la cultura de control interno.');
        $this->accion($oesp51, 2, 17, 'Analizar, rediseñar y documentar las estructuras organizacionales y los procesos institucionales orientados a resultados.');

        // OEsp 5.2 — Infraestructura tecnológica y ciberseguridad
        $oesp52 = $this->goal($oe5, 2, 'Modernizar la infraestructura tecnológica e implementar la política de ciberseguridad institucional.');
        $this->accion($oesp52, 1, 18, 'Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC para el resguardo de información.');
        $this->accion($oesp52, 2, 19, 'Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC para el resguardo de información.');

        // ── OE 6 ──────────────────────────────────────────────────────────────
        $oe6 = $this->perfil(array_merge($riComun3, [
            'name'            => '<p><strong>Mejorar la eficiencia de la gestión administrativa, el desarrollo del talento humano y el uso de los recursos institucionales.</strong></p>',
            'level'           => 'axi',
            'parent_id'       => $this->masterId,
            'order_item'      => 6,
            'bsc_perspectiva' => 'aprendizaje',
            'ri_metas'        => json_encode([
                ['anio'=>2024,'valor'=>'40%'],['anio'=>2025,'valor'=>'60%'],
                ['anio'=>2026,'valor'=>'75%'],['anio'=>2027,'valor'=>'90%'],['anio'=>2028,'valor'=>'100%'],
            ]),
        ]));

        // OEsp 6.1 — Talento humano y contrataciones
        $oesp61 = $this->goal($oe6, 1, 'Profesionalizar el talento humano e institucionalizar los procesos de contratación y adquisición.');
        $this->accion($oesp61, 1, 20, 'Implementar Concursos de Méritos para el ingreso, procesos de inducción y fortalecer el Sistema de Carrera del Talento humano del IPS.');
        $this->accion($oesp61, 2, 21, 'Optimizar la ejecución del Programa Anual de Contrataciones (PAC) y simplificar los procesos de adquisición de bienes y servicios según la normativa vigente.');

        // OEsp 6.2 — Morosidad, evasión y bienes institucionales
        $oesp62 = $this->goal($oe6, 2, 'Reducir la morosidad, controlar la evasión y gestionar eficientemente los bienes e infraestructura institucional.');
        $this->accion($oesp62, 1, 22, 'Reducir la morosidad y evasión mediante vínculos interinstitucionales y fiscalización a empresas.');
        $this->accion($oesp62, 2, 23, 'Reducir la morosidad y evasión mediante vínculos interinstitucionales y fiscalización a empresas.');
        $this->accion($oesp62, 3, 24, 'Gestionar los proyectos de infraestructura y asegurar el mantenimiento, custodia de los bienes institucionales.');
    }

    // ── HELPERS ───────────────────────────────────────────────────────────────

    private function perfil(array $data): string
    {
        $id = (string) Str::uuid();
        DB::table('planificacion.pei_profiles')->insert(array_merge([
            'id'            => $id,
            'type'          => 'corporative',
            'year_start'    => '2024-01-01',
            'year_end'      => '2028-12-31',
            'report_type'   => 'quantitative',
            'user_id'       => self::USER_ID,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'ri_metas'      => '[]',
            '_lft'          => 0,
            '_rgt'          => 0,
            'created_at'    => $this->now,
            'updated_at'    => $this->now,
        ], $data));
        return $id;
    }

    /** Crea un nodo goal (Objetivo Específico) hijo de un axi. */
    private function goal(string $axiId, int $orden, string $nombre): string
    {
        return $this->perfil([
            'name'       => '<p>' . $nombre . '</p>',
            'level'      => 'goal',
            'parent_id'  => $axiId,
            'order_item' => $orden,
        ]);
    }

    /** Crea un nodo action (Acción Estratégica) hijo de un goal y vincula el indicador. */
    private function accion(string $goalId, int $orden, int $indicadorId, string $nombre): void
    {
        $actionId = $this->perfil([
            'name'       => '<p>' . $nombre . '</p>',
            'level'      => 'action',
            'parent_id'  => $goalId,
            'order_item' => $orden,
        ]);

        $existe = DB::table('planificacion.indicadores')->where('id', $indicadorId)->exists();
        if ($existe) {
            DB::table('planificacion.pei_profiles')
                ->where('id', $actionId)
                ->update(['indicador_id' => $indicadorId]);
        }
    }
}
