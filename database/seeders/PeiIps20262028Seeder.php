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
        $this->command->info('── Construyendo PEI IPS 2026–2028 (ID: ' . $this->targetUuid . ') con 6 Objetivos Estratégicos y rótulo MEF...');

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

        $this->command->info('   Master creado/actualizado con nivel_label MEF: ' . $master->name);

        // Obtener IDs de Marcos Referenciales clave
        $marcoPndSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.2%')->value('id');
        $marcoPndPens   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.4%')->value('id');
        $marcoPndGob    = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 4.1%')->value('id');
        $marcoOdsSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 3%')->value('id');
        $marcoOdsTrabajo= DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 8%')->value('id');
        $marcoOdsInst   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 16%')->value('id');

        // ── OE 1 (Misional - Salud Preventiva y Asistencial) ──────────────────────────────────
        $oe1 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 1. Estructurar y fortalecer la Red Integrada e Integral de Servicios de Salud (RIISS) con enfoque preventivo',
            'level'               => 'axi',
            'order_item'          => 1,
            'bsc_perspectiva'     => 'clientes',
            'resultado_intermedio'=> 'Atención médica preventiva e integral por líneas de cuidado con enfoque por ciclo de vida.',
            'ri_recursos_gs'      => 400000000000,
            'ri_programa'         => 'Programa 1: Prestaciones Sanitarias y Salud Integral IPS',
        ]);
        if ($marcoPndSalud) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoPndSalud]);
        if ($marcoOdsSalud) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoOdsSalud]);

        $m11 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe1->id,
            'name'        => 'Objetivo Específico 1.1 - Garantizar el acceso oportuno y la promoción de la salud por líneas de cuidado prioritarias.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m11->id,
            'name'        => 'Implementación de programas de atención primaria y promoción de la salud por ciclo de vida.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Porcentaje de cobertura en programas preventivos de salud',
            'baseline'    => '40%',
            'target'      => '85%',
        ]);

        // ── OE 2 (Misional - Sostenibilidad del Sistema Sanitarias) ───────────────────────────
        $oe2 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 2. Garantizar la calidad, oportunidad y sostenibilidad de los servicios de salud y medicamentos',
            'level'               => 'axi',
            'order_item'          => 2,
            'bsc_perspectiva'     => 'procesos',
            'resultado_intermedio'=> 'Disponibilidad del 98% en stock de medicamentos esenciales y reducción del tiempo de espera asistencial.',
            'ri_recursos_gs'      => 350000000000,
            'ri_programa'         => 'Programa 1: Prestaciones Sanitarias e Insumos Médicos',
        ]);
        if ($marcoPndSalud) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe2->id, 'marco_id' => $marcoPndSalud]);

        $m21 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe2->id,
            'name'        => 'Objetivo Específico 2.1 - Asegurar la logística eficiente y el stock continuo de medicamentos del Cuadro Básico.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m21->id,
            'name'        => 'Automatización e inteligencia de inventarios en el Parque Logístico Central y farmacias de la Red.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Disponibilidad continua de medicamentos esenciales en farmacias',
            'baseline'    => '78%',
            'target'      => '98%',
        ]);

        // ── OE 3 (Misional - Previsión Social & Jubilaciones) ──────────────────────────────────
        $oe3 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 3. Garantizar la sostenibilidad del Fondo de Reserva de Jubilaciones y la oportunidad en prestaciones económicas',
            'level'               => 'axi',
            'order_item'          => 3,
            'bsc_perspectiva'     => 'financiera',
            'resultado_intermedio'=> 'Rendimiento real positivo en inversiones previsionales y liquidación de jubilaciones en menos de 30 días.',
            'ri_recursos_gs'      => 300000000000,
            'ri_programa'         => 'Programa 2: Prestaciones Económicas y Pensiones IPS',
        ]);
        if ($marcoPndPens)    DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoPndPens]);
        if ($marcoOdsTrabajo) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoOdsTrabajo]);

        $m31 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe3->id,
            'name'        => 'Objetivo Específico 3.1 - Optimizar la rentabilidad de las reservas financieras e inmobiliarias y digitalizar trámites jubilatorios.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m31->id,
            'name'        => 'Plataforma digital de concesión y liquidación automática de haberes previsionales.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Tiempo promedio de resolución de trámites de jubilación',
            'baseline'    => '90 días',
            'target'      => '< 30 días',
        ]);

        // ── OE 4 (Misional - Cobertura Contributiva) ──────────────────────────────────────────
        $oe4 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 4. Ampliar la cobertura contributiva formal y fortalecer el control de la mora patronal',
            'level'               => 'axi',
            'order_item'          => 4,
            'bsc_perspectiva'     => 'financiera',
            'resultado_intermedio'=> 'Incremento del 10% en cotizantes formales y recuperación eficiente de la cartera morosa.',
            'ri_recursos_gs'      => 120000000000,
            'ri_programa'         => 'Programa 2: Fiscalización y Cobranzas Previsionales',
        ]);

        $m41 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe4->id,
            'name'        => 'Objetivo Específico 4.1 - Intensificar la fiscalización de patronales en mora y promover la bancarización.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m41->id,
            'name'        => 'Plan de auditoría integrada contra la evasión patronal y cobranza digital.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Porcentaje de recuperación de morosidad contributiva',
            'baseline'    => '45%',
            'target'      => '80%',
        ]);

        // ── OE 5 (Fortalecimiento - Salud Digital & Transformación) ───────────────────────────
        $oe5 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 5. Innovar en tecnologías de la información, ciberseguridad y Salud Digital en las RIISS',
            'level'               => 'axi',
            'order_item'          => 5,
            'bsc_perspectiva'     => 'aprendizaje',
            'resultado_intermedio'=> 'Digitalización del 100% de trámites asistenciales y agendamiento omnicanal accesible.',
            'ri_recursos_gs'      => 150000000000,
            'ri_programa'         => 'Programa 3: Transformación Digital e Innovación Tecnológica',
        ]);

        $m51 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe5->id,
            'name'        => 'Objetivo Específico 5.1 - Desplegar el Expediente Electrónico y agendamiento multicanal en toda la Red.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m51->id,
            'name'        => 'Optimización del sistema de agendamiento omnicanal (App Mi IPS, Call Center, Web).',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Tiempo promedio de agendamiento de citas médicas',
            'baseline'    => '45 min',
            'target'      => '< 5 min',
        ]);

        // ── OE 6 (Fortalecimiento - Gobernanza MECIP & Talento Humano) ────────────────────────
        $oe6 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 6. Fortalecer la gobernanza transparente, el modelo MECIP y el desarrollo integral del talento humano',
            'level'               => 'axi',
            'order_item'          => 6,
            'bsc_perspectiva'     => 'aprendizaje',
            'resultado_intermedio'=> 'Certificación MECIP, índice de transparencia 100/100 y capacitación asistencial continua.',
            'ri_recursos_gs'      => 130000000000,
            'ri_programa'         => 'Programa 3: Gestión Institucional y Desarrollo del Talento Humano',
        ]);
        if ($marcoPndGob)  DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe6->id, 'marco_id' => $marcoPndGob]);
        if ($marcoOdsInst) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe6->id, 'marco_id' => $marcoOdsInst]);

        $m61 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe6->id,
            'name'        => 'Objetivo Específico 6.1 - Institucionalizar el control interno MECIP y capacitar al personal sanitario y administrativo.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m61->id,
            'name'        => 'Portal Abierto de Transparencia, Rendición de Cuentas y Datos Abiertos al Asegurado.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Calificación del Índice de Transparencia Institucional',
            'baseline'    => '82/100',
            'target'      => '100/100',
        ]);

        // 3. Reconstruir la jerarquía interna de la gema NestedSet
        PeiProfile::fixTree();

        $this->command->info('✅ PEI IPS 2026–2028 (ID: ' . $this->targetUuid . ') reconstruido exitosamente con 6 Objetivos Estratégicos y rótulo MEF.');
    }
}
