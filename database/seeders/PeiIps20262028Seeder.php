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
        $this->command->info('── Iniciando construcción del PEI IPS 2026–2028 Reestructurado (ID: ' . $this->targetUuid . ')...');

        // Garantizar existencia de marcos referenciales
        $this->call(MarcoReferencialSeeder::class);

        // 1. Crear o actualizar el perfil Master
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
                    'goal'   => 'Meta Anual / Resultado',
                    'action' => 'Acción Estratégica',
                ]),
                'group_id'     => 50,
            ]
        );

        $this->command->info('   Master creado/actualizado: ' . $master->name);

        // 2. Limpiar descendientes anteriores para permitir recarga limpia
        $existingChildren = PeiProfile::where('parent_id', $master->id)->pluck('id');
        if ($existingChildren->isNotEmpty()) {
            PeiProfile::whereIn('parent_id', $existingChildren)->delete(); // Eliminar acciones/goals
            PeiProfile::whereIn('id', $existingChildren)->delete();        // Eliminar ejes
        }

        // Obtener IDs de Marcos Referenciales clave
        $marcoPndSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.2%')->value('id');
        $marcoPndPens   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 1.4%')->value('id');
        $marcoPndGob    = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%PND OE 4.1%')->value('id');
        $marcoOdsSalud  = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 3%')->value('id');
        $marcoOdsTrabajo= DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 8%')->value('id');
        $marcoOdsInst   = DB::table('planificacion.marcos_referenciales')->where('nombre', 'like', '%ODS 16%')->value('id');

        // ── OE 1 (Misional 1 - Salud) ──────────────────────────────────────────────────────────
        $oe1 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 1. Servicios de Salud Misionales: Cobertura Universal, Oportunidad y Calidez Asistencial',
            'level'               => 'axi',
            'order_item'          => 1,
            'bsc_perspectiva'     => 'clientes',
            'resultado_intermedio'=> 'Atención médica integral en la Red de Salud con tiempo de espera reducido y 98% de disponibilidad continua en medicamentos esenciales.',
            'ri_recursos_gs'      => 450000000000,
            'ri_programa'         => 'Programa 1: Prestaciones Sanitarias y Salud Integral IPS',
        ]);

        if ($marcoPndSalud)   DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoPndSalud]);
        if ($marcoOdsSalud)   DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe1->id, 'marco_id' => $marcoOdsSalud]);

        // Meta 1.1
        $m11 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe1->id,
            'name'        => 'Meta 1.1 - Reducción del tiempo de espera en citas médicas y cirugías programadas a menos de 15 días.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m11->id,
            'name'        => 'Implementación del Expediente Clínico Electrónico e Historia Clínica Unificada en la Red Asistencial Nacional.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Porcentaje de establecimientos de salud con expediente clínico electrónico activo',
            'baseline'    => '45%',
            'target'      => '100%',
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m11->id,
            'name'        => 'Optimización del sistema de agendamiento omnicanal (App Mi IPS, Call Center y Kioscos Digitales).',
            'level'       => 'action',
            'order_item'  => 2,
            'indicator'   => 'Tiempo promedio de confirmación de cita médica por usuario',
            'baseline'    => '45 minutos',
            'target'      => '< 5 minutos',
        ]);

        // Meta 1.2
        $m12 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe1->id,
            'name'        => 'Meta 1.2 - Garantía del 98% de disponibilidad continua en el stock de medicamentos e insumos médicos esenciales.',
            'level'       => 'goal',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m12->id,
            'name'        => 'Automatización de inventarios e Inteligencia Logística en el Parque Central y Farmacias del IPS.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Nivel de abastecimiento de medicamentos del Cuadro Básico Institucional',
            'baseline'    => '78%',
            'target'      => '98%',
        ]);

        // ── OE 2 (Misional 2 - Previsión Social) ───────────────────────────────────────────────
        $oe2 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 2. Previsión Social Misional: Protección Social y Sostenibilidad Financiera del Fondo de Jubilaciones',
            'level'               => 'axi',
            'order_item'          => 2,
            'bsc_perspectiva'     => 'financiera',
            'resultado_intermedio'=> 'Sostenibilidad financiera del sistema previsional con crecimiento del 10% en cotizantes formales y liquidez de reserva asegurada.',
            'ri_recursos_gs'      => 320000000000,
            'ri_programa'         => 'Programa 2: Prestaciones Económicas y Fondo de Pensiones IPS',
        ]);

        if ($marcoPndPens)    DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe2->id, 'marco_id' => $marcoPndPens]);
        if ($marcoOdsTrabajo) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe2->id, 'marco_id' => $marcoOdsTrabajo]);

        // Meta 2.1
        $m21 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe2->id,
            'name'        => 'Meta 2.1 - Sostenibilidad actuarial y financiera del Fondo de Reserva de Jubilaciones y Pensiones.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m21->id,
            'name'        => 'Estrategia de inversión financiera rentabilizada en instrumentos seguros con rendimiento real superior a la inflación.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Rentabilidad real anual de las inversiones del Fondo de Reserva',
            'baseline'    => '4.2%',
            'target'      => '6.5%',
        ]);

        // Meta 2.2
        $m22 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe2->id,
            'name'        => 'Meta 2.2 - Ampliación de la cobertura contributiva y reducción de la mora patronal.',
            'level'       => 'goal',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m22->id,
            'name'        => 'Fiscalización integrada contra la evasión patronal y digitalización del trámite de jubilación en < 30 días.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Tiempo promedio de resolución y concesión de beneficios jubilatorios',
            'baseline'    => '90 días',
            'target'      => '< 30 días',
        ]);

        // ── OE 3 (Fortalecimiento Institucional) ──────────────────────────────────────────────
        $oe3 = PeiProfile::create([
            'id'                  => (string) Str::uuid(),
            'parent_id'           => $master->id,
            'name'                => 'OE 3. Fortalecimiento Institucional: Gobernanza Transparente, Transformación Digital y Talento Humano',
            'level'               => 'axi',
            'order_item'          => 3,
            'bsc_perspectiva'     => 'aprendizaje',
            'resultado_intermedio'=> 'Transformación digital del 100% de trámites internos, certificación MECIP e índice de satisfacción del personal > 85%.',
            'ri_recursos_gs'      => 180000000000,
            'ri_programa'         => 'Programa 3: Gestión Administrativa y Transformación Institucional',
        ]);

        if ($marcoPndGob)  DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoPndGob]);
        if ($marcoOdsInst) DB::table('planificacion.pei_profile_marcos')->insert(['pei_profile_id' => $oe3->id, 'marco_id' => $marcoOdsInst]);

        // Meta 3.1
        $m31 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe3->id,
            'name'        => 'Meta 3.1 - Digitalización del 100% de trámites administrativos e implementación del modelo MECIP.',
            'level'       => 'goal',
            'order_item'  => 1,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m31->id,
            'name'        => 'Implementación del Portal Institucional de Transparencia, Rendición de Cuentas y Datos Abiertos.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Calificación del Índice de Transparencia Institucional (Portal de la Función Pública)',
            'baseline'    => '82/100',
            'target'      => '100/100',
        ]);

        // Meta 3.2
        $m32 = PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $oe3->id,
            'name'        => 'Meta 3.2 - Desarrollo del talento humano institucional y capacitación asistencial continua.',
            'level'       => 'goal',
            'order_item'  => 2,
        ]);
        PeiProfile::create([
            'id'          => (string) Str::uuid(),
            'parent_id'   => $m32->id,
            'name'        => 'Plan de Carrera, Evaluación del Desempeño y Capacitación Continua para Servidores del IPS.',
            'level'       => 'action',
            'order_item'  => 1,
            'indicator'   => 'Porcentaje del personal capacitado y evaluado en estándares de calidad asistencial y ética',
            'baseline'    => '35%',
            'target'      => '90%',
        ]);

        // 3. Reconstruir la jerarquía interna de la gema NestedSet
        PeiProfile::fixTree();

        $this->command->info('✅ PEI IPS 2026–2028 (ID: ' . $this->targetUuid . ') reconstruido exitosamente con 3 Objetivos Estratégicos.');
    }
}
