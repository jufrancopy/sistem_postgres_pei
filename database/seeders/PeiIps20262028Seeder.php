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
    private string $targetUuid = '766eb883-fdd0-4723-8f75-cf689aa8f0fa';

    public function run(): void
    {
        $this->now = Carbon::now();
        $this->command->info('── Reestructurando el PEI IPS 2026–2028 (ID: ' . $this->targetUuid . ') con datos reales del usuario y rótulo MEF...');

        // Garantizar existencia de marcos referenciales
        $this->call(MarcoReferencialSeeder::class);

        // 1. Crear o actualizar el perfil Master con nivel_label MEF
        $master = PeiProfile::updateOrCreate(
            ['id' => $this->targetUuid],
            [
                'name'         => 'Plan Estratégico Institucional IPS 2026–2028',
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
                'group_id'     => 50,
            ]
        );

        $this->command->info('   Master actualizado con rótulos MEF: ' . $master->name);

        // 2. Limpiar descendientes anteriores para regenerar la jerarquía limpia
        $existingChildren = PeiProfile::where('parent_id', $master->id)->pluck('id');
        if ($existingChildren->isNotEmpty()) {
            PeiProfile::whereIn('parent_id', $existingChildren)->forceDelete();
            PeiProfile::whereIn('id', $existingChildren)->forceDelete();
        }

        // Obtener IDs de Marcos Referenciales clave
        $marcoPndSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.2%')->value('id');
        $marcoPndPens   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.4%')->value('id');
        $marcoPndGob    = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 4.1%')->value('id');
        $marcoOdsSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 3%')->value('id');
        $marcoOdsTrabajo= DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 8%')->value('id');
        $marcoOdsInst   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 16%')->value('id');

        // =====================================================================================
        // OE 1. SERVICIOS MISIONALES DE SALUD (Misional 1 - Salud)
        // =====================================================================================
        $oe1 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 1. Servicios de Salud Misionales: Cobertura Universal, Oportunidad, Calidez Asistencial y Gestión Médica',
            'level'               => 'axi',
            'order_item'          => 1,
            'bsc_perspectiva'     => 'clientes',
            'resultado_intermedio'=> 'Desarrollo de la Red Integrada e Integral de Servicios de Salud (RIISS) con enfoque preventivo, abastecimiento oportuno y trazabilidad de insumos.',
            'ri_recursos_gs'      => 450000000000,
            'ri_programa'         => 'Programa 1: Prestaciones Sanitarias y Salud Integral IPS',
        ]);
        if ($marcoPndSalud) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoPndSalud]);
        if ($marcoOdsSalud) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoOdsSalud]);

        // OE 1.1 (Goal 1)
        $m11 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe1->id,
            'name'        => 'Objetivo Específico 1.1 - Reducir la mortalidad materna, neonatal e infantil e impulsar la investigación científica en los establecimientos de la RIISS.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m11->id,
            'name'        => 'Mejorar la atención obstétrica para disminuir la muerte materna institucional.',
            'level'       => 'action',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m11->id,
            'name'        => 'Mejorar la atención neonatal para disminuir la mortalidad neonatal institucional.',
            'level'       => 'action',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m11->id,
            'name'        => 'Impulsar trabajos de investigación científica en los establecimientos de la RIISS.',
            'level'       => 'action',
            'order_item'  => 3,
        ]);

        // OE 1.2 (Goal 2)
        $m12 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe1->id,
            'name'        => 'Objetivo Específico 1.2 - Fortalecer las redes temáticas de salud y la cobertura preventiva a lo largo del curso de vida.',
            'level'       => 'goal',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m12->id,
            'name'        => 'Implementar Redes Temáticas Integradas (Salud Mental, Bucal, Diabetes, Hipertensión) y asegurar cobertura de vacunación.',
            'level'       => 'action',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m12->id,
            'name'        => 'Adecuar los programas y servicios de salud a las necesidades del curso de la vida, priorizando infancia, adolescencia y personas mayores.',
            'level'       => 'action',
            'order_item'  => 2,
        ]);

        // OE 1.3 (Goal 3)
        $m13 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe1->id,
            'name'        => 'Objetivo Específico 1.3 - Garantizar el abastecimiento oportuno, trazabilidad de insumos médicos y expansión del Expediente Electrónico.',
            'level'       => 'goal',
            'order_item'  => 3,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m13->id,
            'name'        => 'Asegurar la trazabilidad total y el abastecimiento oportuno mediante el sistema informático.',
            'level'       => 'action',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m13->id,
            'name'        => 'Expandir el Expediente Electrónico, Telemedicina y Resultados Digitales en toda la red.',
            'level'       => 'action',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m13->id,
            'name'        => 'Actualizar protocolos de atención y planes operativos según criterios epidemiológicos.',
            'level'       => 'action',
            'order_item'  => 3,
        ]);

        // =====================================================================================
        // OE 2. PREVISIÓN SOCIAL MISIONAL (Misional 2 - Previsión Social)
        // =====================================================================================
        $oe2 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 2. Previsión Social Misional: Protección Social, Administración de Prestaciones y Sostenibilidad Financiera',
            'level'               => 'axi',
            'order_item'          => 2,
            'bsc_perspectiva'     => 'financiera',
            'resultado_intermedio'=> 'Sostenibilidad financiera de los fondos del seguro social, optimización de reservas e incremento de la recaudación contributiva.',
            'ri_recursos_gs'      => 320000000000,
            'ri_programa'         => 'Programa 2: Prestaciones Económicas y Fondo de Pensiones IPS',
        ]);
        if ($marcoPndPens)    DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe2->id, 'marco_id' => $marcoPndPens]);
        if ($marcoOdsTrabajo) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe2->id, 'marco_id' => $marcoOdsTrabajo]);

        // OE 2.1 (Goal 1)
        $m21 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe2->id,
            'name'        => 'Objetivo Específico 2.1 - Modernizar, descentralizar y fortalecer el marco legal y financiero de las prestaciones económicas.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m21->id,
            'name'        => 'Elaborar proyectos de reformas legales para el financiamiento equitativo de las prestaciones.',
            'level'       => 'action',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m21->id,
            'name'        => 'Concesión oportuna, automatizada y descentralizada de prestaciones económicas del Seguro Social.',
            'level'       => 'action',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m21->id,
            'name'        => 'Garantizar acceso a información clara sobre derechos previsionales a través de canales digitales.',
            'level'       => 'action',
            'order_item'  => 3,
        ]);

        // OE 2.2 (Goal 2)
        $m22 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe2->id,
            'name'        => 'Objetivo Específico 2.2 - Optimizar la gestión de reservas técnicas, inversiones y recaudación del Aporte Obrero Patronal.',
            'level'       => 'goal',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m22->id,
            'name'        => 'Implementar el Sistema de Gestión Inmobiliaria y mejorar la fiscalización de contratos.',
            'level'       => 'action',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m22->id,
            'name'        => 'Diversificar e invertir las reservas técnicas bajo criterios de seguridad, liquidez y rentabilidad.',
            'level'       => 'action',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m22->id,
            'name'        => 'Aumentar la recaudación del Aporte Obrero Patronal mediante vínculos interinstitucionales.',
            'level'       => 'action',
            'order_item'  => 3,
        ]);

        // =====================================================================================
        // OE 3. FORTALECIMIENTO INSTITUCIONAL (Fortalecimiento Institucional)
        // =====================================================================================
        $oe3 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 3. Fortalecimiento Institucional: Transformación Digital, Gobernanza MECIP, Talento Humano y PAC',
            'level'               => 'axi',
            'order_item'          => 3,
            'bsc_perspectiva'     => 'aprendizaje',
            'resultado_intermedio'=> 'Alineamiento del direccionamiento estratégico, control interno MECIP, digitalización de trámites y eficiencia administrativa.',
            'ri_recursos_gs'      => 180000000000,
            'ri_programa'         => 'Programa 3: Gestión Administrativa y Transformación Institucional',
        ]);
        if ($marcoPndGob)  DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoPndGob]);
        if ($marcoOdsInst) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoOdsInst]);

        // OE 3.1 (Goal 1)
        $m31 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe3->id,
            'name'        => 'Objetivo Específico 3.1 - Modernizar la infraestructura tecnológica, ciberseguridad (MITIC) y control interno (MECIP).',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m31->id,
            'name'        => 'Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC para el resguardo de información.',
            'level'       => 'action',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m31->id,
            'name'        => 'Analizar, rediseñar y documentar las estructuras organizacionales y los procesos institucionales orientados a resultados.',
            'level'       => 'action',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m31->id,
            'name'        => 'Implementar el Plan de Trabajo de las Normas de Requisitos Mínimos MECIP y fortalecer la cultura de control interno.',
            'level'       => 'action',
            'order_item'  => 3,
        ]);

        // OE 3.2 (Goal 2)
        $m32 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe3->id,
            'name'        => 'Objetivo Específico 3.2 - Eficiencia en el Programa Anual de Contrataciones (PAC), profesionalización del Talento Humano e Infraestructura.',
            'level'       => 'goal',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m32->id,
            'name'        => 'Optimizar la ejecución del Programa Anual de Contrataciones (PAC) y simplificar los procesos de adquisición de bienes y servicios según la normativa vigente.',
            'level'       => 'action',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m32->id,
            'name'        => 'Implementar Concursos de Méritos para el ingreso, procesos de inducción y fortalecer el Sistema de Carrera del Talento humano del IPS.',
            'level'       => 'action',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m32->id,
            'name'        => 'Reducir la morosidad y evasión mediante vínculos interinstitucionales y fiscalización a empresas.',
            'level'       => 'action',
            'order_item'  => 3,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m32->id,
            'name'        => 'Gestionar los proyectos de infraestructura y asegurar el mantenimiento, custodia de los bienes institucionales.',
            'level'       => 'action',
            'order_item'  => 4,
        ]);

        // 3. Reconstruir la jerarquía interna de la gema NestedSet
        PeiProfile::fixTree();

        $this->command->info('✅ PEI IPS 2026–2028 (ID: ' . $this->targetUuid . ') reestructurado exitosamente con 3 OE, 7 OE Específicos y 21 Acciones Reales.');
    }
}
