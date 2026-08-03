<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Admin\Planificacion\Pei\PeiProfile;

class PeiIps20262028Seeder extends Seeder
{
    private Carbon $now;
    private string $originalUuid = '766eb883-fdd0-4723-8f75-cf689aa8f0fa';
    private string $mefUuid      = 'ce99f883-fdd0-4723-8f75-cf689aa8f0fa';

    public function run(): void
    {
        $this->now = Carbon::now();
        $this->command->info('── Sembrando ambas versiones del PEI IPS 2026–2028 (Versión 6 Objetivos Original + Versión MEF 3 Objetivos)...');

        // Garantizar existencia de marcos referenciales
        $this->call(MarcoReferencialSeeder::class);

        // =========================================================================
        // 1. VERSIÓN ORIGINAL (6 OBJETIVOS ESTRATÉGICOS) - ID: 766eb883-fdd0-4723-8f75-cf689aa8f0fa
        // =========================================================================
        $masterOrig = PeiProfile::updateOrCreate(
            ['id' => $this->originalUuid],
            [
                'name'         => 'Plan Estratégico Institucional IPS 2026–2028 (6 Objetivos)',
                'year_start'   => '2026-01-01',
                'year_end'     => '2028-12-31',
                'level'        => 'master',
                'parent_id'    => null,
                'mision'       => 'Brindar protección social integral en salud, jubilaciones y pensiones a nuestros asegurados y beneficiarios, garantizando servicios universales, oportunos, solidarios y sostenibles que promuevan el bienestar y la calidad de vida en el Paraguay.',
                'vision'       => 'Ser la institución pública líder y referente en seguridad social y salud del Paraguay, reconocida por su excelencia operativa, transparencia, innovación tecnológica y compromiso con la dignidad de sus asegurados.',
                'values'       => '<p><strong>Solidaridad</strong> · <strong>Transparencia</strong> · <strong>Excelencia</strong> · <strong>Oportunidad</strong> · <strong>Calidez Humana</strong> · <strong>Eficiencia</strong></p>',
                'nivel_label'  => json_encode([
                    'axi'    => 'Objetivo Estratégico',
                    'goal'   => 'Objetivo Específico',
                    'action' => 'Acción Estratégica',
                ]),
                'type'         => 'corporative',
                'group_id'     => 50,
            ]
        );

        $existingOrigChildren = PeiProfile::where('parent_id', $masterOrig->id)->pluck('id');
        if ($existingOrigChildren->isNotEmpty()) {
            PeiProfile::whereIn('parent_id', $existingOrigChildren)->forceDelete();
            PeiProfile::whereIn('id', $existingOrigChildren)->forceDelete();
        }

        $this->sembrarPeiOriginal($masterOrig);

        // =========================================================================
        // 2. VERSIÓN REESTRUCTURADA MEF (3 OBJETIVOS ESTRATÉGICOS) - ID: ce99f883-fdd0-4723-8f75-cf689aa8f0fa
        // =========================================================================
        $masterMef = PeiProfile::updateOrCreate(
            ['id' => $this->mefUuid],
            [
                'name'         => 'Plan Estratégico Institucional IPS 2026–2028 (Propuesta Reestructurada MEF - 3 OE)',
                'year_start'   => '2026-01-01',
                'year_end'     => '2028-12-31',
                'level'        => 'master',
                'parent_id'    => null,
                'mision'       => 'Brindar protección social integral en salud, jubilaciones y pensiones a nuestros asegurados y beneficiarios, garantizando servicios universales, oportunos, solidarios y sostenibles que promuevan el bienestar y la calidad de vida en el Paraguay.',
                'vision'       => 'Ser la institución pública líder y referente en seguridad social y salud del Paraguay, reconocida por su excelencia operativa, transparencia, innovación tecnológica y compromiso con la dignidad de sus asegurados.',
                'values'       => '<p><strong>Solidaridad</strong> · <strong>Transparencia</strong> · <strong>Excelencia</strong> · <strong>Oportunidad</strong> · <strong>Calidez Humana</strong> · <strong>Eficiencia</strong></p>',
                'nivel_label'  => json_encode([
                    'axi'    => 'Objetivo Estratégico',
                    'goal'   => 'Objetivo Específico',
                    'action' => 'Acción Estratégica',
                ]),
                'type'         => 'corporative',
                'group_id'     => 50,
            ]
        );

        $existingMefChildren = PeiProfile::where('parent_id', $masterMef->id)->pluck('id');
        if ($existingMefChildren->isNotEmpty()) {
            PeiProfile::whereIn('parent_id', $existingMefChildren)->forceDelete();
            PeiProfile::whereIn('id', $existingMefChildren)->forceDelete();
        }

        $this->sembrarPeiMef($masterMef);

        PeiProfile::fixTree();
        $this->command->info('✅ Ambos PEIs (Versión 6 Objetivos y Versión MEF 3 Objetivos) fueron creados y conviven perfectamente.');
    }

    /**
     * Sembrar la versión ORIGINAL de 6 Objetivos Estratégicos con todos sus objetivos específicos y acciones reales.
     */
    private function sembrarPeiOriginal(PeiProfile $master): void
    {
        // ── EJE 1 ────────────────────────────────────────────────────────────
        $e1 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 1, 'bsc_perspectiva' => 'procesos',
            'name' => 'Alinear la planificación estratégica, gestión por procesos y control interno (MECIP) con la transformación digital de la institución.',
        ]);
        $g11 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e1->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Modernizar la infraestructura tecnológica e implementar la política de ciberseguridad institucional.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g11->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC para el resguardo de información.']);

        $g12 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e1->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Fortalecer el control interno institucional y la gestión por procesos orientada a resultados (MECIP).']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g12->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Analizar, rediseñar y documentar las estructuras organizacionales y los procesos institucionales orientados a resultados.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g12->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Implementar el Plan de Trabajo de las Normas de Requisitos Mínimos MECIP y fortalecer la cultura de control interno.']);

        // ── EJE 2 ────────────────────────────────────────────────────────────
        $e2 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 2, 'bsc_perspectiva' => 'clientes',
            'name' => 'Desarrollar la red integrada e integral de servicios de salud con enfoque preventivo y de calidad para los asegurados y beneficiarios.',
        ]);
        $g21 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e2->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Promover la investigación científica en los establecimientos de la RIISS.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g21->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Impulsar trabajos de investigación científica en los establecimientos de la RIISS.']);

        $g22 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e2->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Reducir la mortalidad materna, neonatal e infantil en los establecimientos de la RIISS.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g22->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Mejorar la atención obstétrica para disminuir la muerte materna institucional.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g22->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Mejorar la atención neonatal para disminuir la mortalidad neonatal institucional.']);

        $g23 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e2->id, 'level' => 'goal', 'order_item' => 3, 'name' => 'Fortalecer las redes temáticas de salud y la cobertura preventiva a lo largo del curso de vida.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g23->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Implementar Redes Temáticas Integradas (Salud Mental, Bucal, Diabetes, Hipertensión) y asegurar cobertura de vacunación.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g23->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Adecuar los programas y servicios de salud a las necesidades del curso de la vida, priorizando infancia, adolescencia y personas mayores.']);

        // ── EJE 3 ────────────────────────────────────────────────────────────
        $e3 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 3, 'bsc_perspectiva' => 'clientes',
            'name' => 'Optimizar la gestión médica, logística y planificación de los servicios de salud en la red de establecimientos sanitarios.',
        ]);
        $g31 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e3->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Garantizar el abastecimiento oportuno y la trazabilidad de insumos médicos en toda la red.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g31->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Asegurar la trazabilidad total y el abastecimiento oportuno mediante el sistema informático.']);

        $g32 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e3->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Actualizar protocolos de atención y expandir la digitalización de los servicios de salud.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g32->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Expandir el Expediente Electrónico, Telemedicina y Resultados Digitales en toda la red.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g32->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Actualizar protocolos de atención y planes operativos según criterios epidemiológicos.']);

        // ── EJE 4 ────────────────────────────────────────────────────────────
        $e4 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 4, 'bsc_perspectiva' => 'financiera',
            'name' => 'Asegurar la administración y el acceso a las prestaciones económicas de trabajadores, jubilados y pensionados.',
        ]);
        $g41 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e4->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Fortalecer el marco legal y financiero para el financiamiento equitativo de las prestaciones.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g41->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Elaborar proyectos de reformas legales para el financiamiento equitativo de las prestaciones.']);

        $g42 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e4->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Modernizar y descentralizar el acceso a las prestaciones económicas del seguro social.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g42->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Concesión oportuna, automatizada y descentralizada de prestaciones económicas del Seguro Social.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g42->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Garantizar acceso a información clara sobre derechos previsionales a través de canales digitales.']);

        // ── EJE 5 ────────────────────────────────────────────────────────────
        $e5 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 5, 'bsc_perspectiva' => 'financiera',
            'name' => 'Garantizar la sostenibilidad financiera de los fondos del seguro social.',
        ]);
        $g51 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e5->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Optimizar la gestión de reservas técnicas e inversiones institucionales.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g51->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Implementar el Sistema de Gestión Inmobiliaria y mejorar la fiscalización de contratos.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g51->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Diversificar e invertir las reservas técnicas bajo criterios de seguridad, liquidez y rentabilidad.']);

        $g52 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e5->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Incrementar la recaudación del Aporte Obrero Patronal y reducir la evasión.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g52->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Aumentar la recaudación del Aporte Obrero Patronal mediante vínculos interinstitucionales.']);

        // ── EJE 6 ────────────────────────────────────────────────────────────
        $e6 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 6, 'bsc_perspectiva' => 'aprendizaje',
            'name' => 'Mejorar la eficiencia de la gestión administrativa, el desarrollo del talento humano y el uso de los recursos institucionales.',
        ]);
        $g61 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e6->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Profesionalizar el talento humano e institucionalizar los procesos de contratación y adquisición.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g61->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Optimizar la ejecución del Programa Anual de Contrataciones (PAC) y simplificar los procesos de adquisición de bienes y servicios según la normativa vigente.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g61->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Implementar Concursos de Méritos para el ingreso, procesos de inducción y fortalecer el Sistema de Carrera del Talento humano del IPS.']);

        $g62 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $e6->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Reducir la morosidad, controlar la evasión y gestionar eficientemente los bienes e infraestructura institucional.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g62->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Reducir la morosidad y evasión mediante vínculos interinstitucionales y fiscalización a empresas.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $g62->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Gestionar los proyectos de infraestructura y asegurar el mantenimiento, custodia de los bienes institucionales.']);
    }

    /**
     * Sembrar la versión REESTRUCTURADA MEF de 3 Objetivos Estratégicos.
     */
    private function sembrarPeiMef(PeiProfile $master): void
    {
        $marcoPndSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.2%')->value('id');
        $marcoPndPens   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.4%')->value('id');
        $marcoPndGob    = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 4.1%')->value('id');
        $marcoOdsSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 3%')->value('id');
        $marcoOdsTrabajo= DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 8%')->value('id');
        $marcoOdsInst   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 16%')->value('id');

        // OE 1 MEF
        $oe1 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 1, 'bsc_perspectiva' => 'clientes',
            'name' => 'OE 1. Servicios de Salud Misionales: Cobertura Universal, Oportunidad, Calidez Asistencial y Gestión Médica',
            'resultado_intermedio' => 'Desarrollo de la Red Integrada e Integral de Servicios de Salud (RIISS) con enfoque preventivo, abastecimiento oportuno y trazabilidad de insumos.',
            'ri_recursos_gs' => 450000000000, 'ri_programa' => 'Programa 1: Prestaciones Sanitarias y Salud Integral IPS',
        ]);
        if ($marcoPndSalud) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoPndSalud]);
        if ($marcoOdsSalud) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoOdsSalud]);

        $m11 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $oe1->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Objetivo Específico 1.1 - Reducir la mortalidad materna, neonatal e infantil e impulsar la investigación científica en los establecimientos de la RIISS.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m11->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Mejorar la atención obstétrica para disminuir la muerte materna institucional.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m11->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Mejorar la atención neonatal para disminuir la mortalidad neonatal institucional.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m11->id, 'level' => 'action', 'order_item' => 3, 'name' => 'Impulsar trabajos de investigación científica en los establecimientos de la RIISS.']);

        $m12 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $oe1->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Objetivo Específico 1.2 - Fortalecer las redes temáticas de salud y la cobertura preventiva a lo largo del curso de vida.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m12->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Implementar Redes Temáticas Integradas (Salud Mental, Bucal, Diabetes, Hipertensión) y asegurar cobertura de vacunación.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m12->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Adecuar los programas y servicios de salud a las necesidades del curso de la vida, priorizando infancia, adolescencia y personas mayores.']);

        $m13 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $oe1->id, 'level' => 'goal', 'order_item' => 3, 'name' => 'Objetivo Específico 1.3 - Garantizar el abastecimiento oportuno, trazabilidad de insumos médicos y expansión del Expediente Electrónico.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m13->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Asegurar la trazabilidad total y el abastecimiento oportuno mediante el sistema informático.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m13->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Expandir el Expediente Electrónico, Telemedicina y Resultados Digitales en toda la red.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m13->id, 'level' => 'action', 'order_item' => 3, 'name' => 'Actualizar protocolos de atención y planes operativos según criterios epidemiológicos.']);

        // OE 2 MEF
        $oe2 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 2, 'bsc_perspectiva' => 'financiera',
            'name' => 'OE 2. Previsión Social Misional: Protección Social, Administración de Prestaciones y Sostenibilidad Financiera',
            'resultado_intermedio' => 'Sostenibilidad financiera de los fondos del seguro social, optimización de reservas e incremento de la recaudación contributiva.',
            'ri_recursos_gs' => 320000000000, 'ri_programa' => 'Programa 2: Prestaciones Económicas y Fondo de Pensiones IPS',
        ]);
        if ($marcoPndPens)    DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe2->id, 'marco_id' => $marcoPndPens]);
        if ($marcoOdsTrabajo) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe2->id, 'marco_id' => $marcoOdsTrabajo]);

        $m21 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $oe2->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Objetivo Específico 2.1 - Modernizar, descentralizar y fortalecer el marco legal y financiero de las prestaciones económicas.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m21->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Elaborar proyectos de reformas legales para el financiamiento equitativo de las prestaciones.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m21->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Concesión oportuna, automatizada y descentralizada de prestaciones económicas del Seguro Social.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m21->id, 'level' => 'action', 'order_item' => 3, 'name' => 'Garantizar acceso a información clara sobre derechos previsionales a través de canales digitales.']);

        $m22 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $oe2->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Objetivo Específico 2.2 - Optimizar la gestión de reservas técnicas, inversiones y recaudación del Aporte Obrero Patronal.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m22->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Implementar el Sistema de Gestión Inmobiliaria y mejorar la fiscalización de contratos.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m22->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Diversificar e invertir las reservas técnicas bajo criterios de seguridad, liquidez y rentabilidad.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m22->id, 'level' => 'action', 'order_item' => 3, 'name' => 'Aumentar la recaudación del Aporte Obrero Patronal mediante vínculos interinstitucionales.']);

        // OE 3 MEF
        $oe3 = PeiProfile::create([
            'id' => (string) Str::uuid(), 'parent_id' => $master->id, 'level' => 'axi', 'order_item' => 3, 'bsc_perspectiva' => 'aprendizaje',
            'name' => 'OE 3. Fortalecimiento Institucional: Transformación Digital, Gobernanza MECIP, Talento Humano y PAC',
            'resultado_intermedio' => 'Alineamiento del direccionamiento estratégico, control interno MECIP, digitalización de trámites y eficiencia administrativa.',
            'ri_recursos_gs' => 180000000000, 'ri_programa' => 'Programa 3: Gestión Administrativa y Transformación Institucional',
        ]);
        if ($marcoPndGob)  DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoPndGob]);
        if ($marcoOdsInst) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoOdsInst]);

        $m31 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $oe3->id, 'level' => 'goal', 'order_item' => 1, 'name' => 'Objetivo Específico 3.1 - Modernizar la infraestructura tecnológica, ciberseguridad (MITIC) y control interno (MECIP).']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m31->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC para el resguardo de información.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m31->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Analizar, rediseñar y documentar las estructuras organizacionales y los procesos institucionales orientados a resultados.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m31->id, 'level' => 'action', 'order_item' => 3, 'name' => 'Implementar el Plan de Trabajo de las Normas de Requisitos Mínimos MECIP y fortalecer la cultura de control interno.']);

        $m32 = PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $oe3->id, 'level' => 'goal', 'order_item' => 2, 'name' => 'Objetivo Específico 3.2 - Eficiencia en el Programa Anual de Contrataciones (PAC), profesionalización del Talento Humano e Infraestructura.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m32->id, 'level' => 'action', 'order_item' => 1, 'name' => 'Optimizar la ejecución del Programa Anual de Contrataciones (PAC) y simplificar los procesos de adquisición de bienes y servicios según la normativa vigente.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m32->id, 'level' => 'action', 'order_item' => 2, 'name' => 'Implementar Concursos de Méritos para el ingreso, procesos de inducción y fortalecer el Sistema de Carrera del Talento humano del IPS.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m32->id, 'level' => 'action', 'order_item' => 3, 'name' => 'Reducir la morosidad y evasión mediante vínculos interinstitucionales y fiscalización a empresas.']);
        PeiProfile::create(['id' => (string) Str::uuid(), 'parent_id' => $m32->id, 'level' => 'action', 'order_item' => 4, 'name' => 'Gestionar los proyectos de infraestructura y asegurar el mantenimiento, custodia de los bienes institucionales.']);
    }
}
