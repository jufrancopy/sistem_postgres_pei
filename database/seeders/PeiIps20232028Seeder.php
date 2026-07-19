<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * PeiIps20232028Seeder — Plan Estratégico Institucional IPS 2023-2028
 * Jerarquía: master → axi (Eje Estratégico) → goal (Objetivo Estratégico) → action (Acción)
 * Fuente: PLANILLA MONI PEI FINAL 1.5 2026 — hoja TOTAL FINAL-2026
 */
class PeiIps20232028Seeder extends Seeder
{
    private Carbon $now;
    private string $nivelLabel;
    private string $masterId;

    const ORG_IPS  = 26;
    const USER_ID  = 201;
    const GROUP_ID = 50;
    const FODA_ID  = 5;

    public function run(): void
    {
        $this->now = Carbon::now();
        $this->nivelLabel = json_encode([
            'master' => 'PEI',
            'axi'    => 'Eje Estratégico',
            'goal'   => 'Objetivo Estratégico',
            'action' => 'Acción',
        ]);

        $this->masterId = $this->crearOObtenerMaster();
        $this->command->info('── Construyendo PEI IPS 2023-2028...');
        $this->command->info('   Master ID: ' . $this->masterId);

        $this->actualizarMaster();
        $this->eje1();
        $this->eje2();
        $this->eje3();

        \App\Admin\Planificacion\Pei\PeiProfile::fixTree();
        $this->command->info('✅ PEI IPS 2023-2028 completo.');
    }

    private function crearOObtenerMaster(): string
    {
        $existe = DB::table('planificacion.pei_profiles')
            ->where('level', 'master')
            ->where('name', 'like', '%Plan Estratégico Institucional%2023%')
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
            'name'           => 'Plan Estratégico Institucional - 2023 - 2028',
            'level'          => 'master',
            'type'           => 'corporative',
            'year_start'     => '2023-01-01',
            'year_end'       => '2028-12-31',
            'report_type'    => 'quantitative',
            'user_id'        => self::USER_ID,
            'dependency_id'  => self::ORG_IPS,
            'group_id'       => self::GROUP_ID,
            'foda_perfil_id' => self::FODA_ID,
            'nivel_label'    => $this->nivelLabel,
            'ri_metas'       => '[]',
            '_lft'           => 0,
            '_rgt'           => 0,
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

    // ══════════════════════════════════════════════════════════════════════════
    // EJE 1
    // ══════════════════════════════════════════════════════════════════════════
    private function eje1(): void
    {
        $eje = $this->axi(1, 'Eje Estratégico 1: FORTALECIMIENTO DE LA GESTIÓN DE LA RED INTEGRADA E INTEGRAL DE SALUD');

        // OE 1
        $oe1 = $this->goal($eje, 1, 'Objetivo Estratégico 1- Estructurar la Red Integrada e Integral de Servicios Salud, con enfoque preventivo, incluyendo la promoción de la salud.');
        $this->accion($oe1, 1,  'Implementar Redes Temáticas Integradas e Integrales (como Red de Salud Mental, Bucal, Nutrición, Sangre y otros en todo el país)');
        $this->accion($oe1, 2,  'Ampliar los núcleos de atención de la Red Integral de Atención a la Diabetes (RIAD), en establecimientos del país');
        $this->accion($oe1, 3,  'Realizar campañas de diagnóstico y seguimiento a pacientes Hipertensos en todo la Red del IPS');
        $this->accion($oe1, 4,  'Aumentar la captación de pacientes hipertensos diagnosticados en el IPS');
        $this->accion($oe1, 5,  'Brindar atención para la promoción y prevención de las enfermedades bucodentales materno infantil con énfasis en gestantes y bebes de 0 a 36 meses.');

        // OE 2
        $oe2 = $this->goal($eje, 2, 'Objetivo Estratégico 2- Garantizar la sostenibilidad del sistema de salud para la cobertura de servicios con calidad de forma oportuna, eficiente y humanizada.');
        $this->accion($oe2, 1,  'Descentralizar la gestión de junta medica para las prestaciones económicas, mediante las TICs EJ: Telemedicina en localidades del interior.');
        $this->accion($oe2, 2,  'Optimizar el proceso de concesión de prestaciones económicas por subsidios reposos, maternidad y accidentes de trabajo.');
        $this->accion($oe2, 3,  'Elaborar la Política Institucional de Prevención de Enfermedades y Promoción de la Salud.');
        $this->accion($oe2, 4,  'Optimizar los procesos de actualización (inclusión, exclusión y modificaciones técnicas) del Cuadro Básico de Medicamentos e Insumos Médicos.');
        $this->accion($oe2, 5,  'Analizar el gasto integral por pacientes con enfermedades catastróficas (oncológicas, renales, cardíacas, entre otras).');

        // OE 3
        $oe3 = $this->goal($eje, 3, 'Objetivo Estratégico 3- Promover la articulación intersectorial e interinstitucional para la optimización de los recursos, la docencia e investigación.');
        $this->accion($oe3, 1,  'Impulsar la realización de trabajos de investigación en establecimientos de salud del IPS.');

        // OE 4
        $oe4 = $this->goal($eje, 4, 'Objetivo Estratégico 4- Implementar efectivamente la planificación en salud en el área asistencial, la logística y las áreas de apoyo al acto médico.');
        $this->accion($oe4, 1,  'Actualizar los protocolos de atención en salud en todos los niveles de atención de la RIISS.');
        $this->accion($oe4, 2,  'Asegurar la trazabilidad de los insumos y medicamentos dentro del sistema informático.');
        $this->accion($oe4, 3,  'Actualizar y socializar los planes operativos según criterios epidemiológicos.');

        // OE 5
        $oe5 = $this->goal($eje, 5, 'Objetivo Estratégico 5- Aplicar el concepto de Salud Digital en las RIISS.');
        $this->accion($oe5, 1,  'Ampliar la cobertura de la red del servicio de telemedicina.');
        $this->accion($oe5, 2,  'Ampliar los establecimientos que cuenten con el servicio de remisión digital de los resultados de laboratorio e imágenes.');
        $this->accion($oe5, 3,  'Aumentar la implementación del expediente electrónico en los establecimientos de la RIISS.');

        // OE 6
        $oe6 = $this->goal($eje, 6, 'Objetivo Estratégico 6- Promover la implementación de líneas de cuidado por ciclo de vida.');
        $this->accion($oe6, 1,  'Disminuir la muerte materna en los establecimientos donde se realizan partos de la RIISS.');
        $this->accion($oe6, 2,  'Asegurar la cobertura de dosis aplicadas según el Programa Ampliado de Vacunación (PAV) en los establecimientos de la RIISS.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // EJE 2
    // ══════════════════════════════════════════════════════════════════════════
    private function eje2(): void
    {
        $eje = $this->axi(2, 'Eje Estratégico 2: Sostenibilidad del Fondo Común de Jubilaciones y Pensiones.');

        // OE 1
        $oe1 = $this->goal($eje, 1, 'Objetivo Estratégico 1- Brindar prestaciones económicas oportunamente a la población asegurada.');
        $this->accion($oe1, 1,  'Garantizar que los jubilados y pensionados tengan acceso a información veraz, clara y oportuna sobre sus beneficios.');
        $this->accion($oe1, 2,  'Automatizar los proceso de pensión a derecho habiente en caso de fallecimiento del titular.');
        $this->accion($oe1, 3,  'Reducir los tiempos de concesión de Beneficios al Jubilado (retiro por vejez, derecho habiente, invalidez).');
        $this->accion($oe1, 4,  'Optimizar la concesión de beneficios, a través de la revisión y modificación de los reglamentos vigentes.');
        $this->accion($oe1, 5,  'Construir alianzas estratégicas con empresas para facilitar la recopilación de información de los asegurados.');

        // OE 2
        $oe2 = $this->goal($eje, 2, 'Objetivo Estratégico 2- Maximizar los ingresos financieros e inmobiliarios del fondo.');
        $this->accion($oe2, 1,  'Elaborar criterios de evaluación para el análisis de inversión o colocación de los recursos del Fondo Común de Jubilaciones y Pensiones.');
        $this->accion($oe2, 2,  'Incorporar el producto financiero "Fondos Mutuos" al portafolio de inversiones del IPS.');
        $this->accion($oe2, 3,  'Actualizar el Reglamento Inmobiliario del Instituto de Previsión Social, aprobado por Resolución C.A. N° 081-030/2023.');
        $this->accion($oe2, 4,  'Gestionar las inversiones financieras e inmobiliarias para el Crecimiento de los recursos de las reservas técnicas del Fondo Común de Jubilaciones y Pensiones.');
        $this->accion($oe2, 5,  'Incorporar al régimen de inversiones del Instituto de Previsión Social los activos financieros elegibles.');
        $this->accion($oe2, 6,  'Controlar y monitorear los límites máximos de inversión en función a las restricciones del Reglamento de Inversiones.');
        $this->accion($oe2, 7,  'Análisis de la disponibilidad de los activos y/o emisores que dispongan de suficiente liquidez para la inversión.');
        $this->accion($oe2, 8,  'Identificar conforme al mapeo del margen de disponibilidad, las oportunidades de inversión.');
        $this->accion($oe2, 9,  'Elevar a consideración de la máxima autoridad las propuestas de inversión una vez identificadas las oportunidades.');
        $this->accion($oe2, 10, 'Aumentar la cantidad de créditos del segmento de funcionarios, jubilados y pensionados del IPS.');
        $this->accion($oe2, 11, 'Implementar el Sistema de Gestión Inmobiliaria en el Dpto. de Adm. de Inmuebles.');
        $this->accion($oe2, 12, 'Mejorar los procesos de fiscalización de contratos en ejecución.');
        $this->accion($oe2, 13, 'Mejorar la dinámica de los Registros Contables de las mejoras edilicias en los inmuebles del IPS.');
        $this->accion($oe2, 14, 'Mantener actualizado la visualización de datos de los concursos para arrendamiento de los bienes inmuebles del IPS.');

        // OE 3
        $oe3 = $this->goal($eje, 3, 'Objetivo Estratégico 3- Impulsar reformas legales para el financiamiento de las prestaciones económicas otorgadas equilibradamente entre el esfuerzo contributivo de los aportantes y los beneficios recibidos.');
        $this->accion($oe3, 1,  'Elaborar proyectos de reformas legales para el debido financiamiento de prestaciones económicas.');

        // OE 4
        $oe4 = $this->goal($eje, 4, 'Objetivo Estratégico 4- Promover el trabajo integrado, para la implementación de la Política Institucional del Adulto Mayor 2021/2030, en todas las dependencias del IPS.');
        $this->accion($oe4, 1,  'Aumentar el % de Cobertura de la Atención Domiciliaria para Adultos Mayores en la RIISS.');
        $this->accion($oe4, 2,  'Elaborar el Plan "Ciudadano de Oro" 2023/2030, de Implementación para la Política Institucional del Adulto Mayor.');
        $this->accion($oe4, 3,  'Monitorear la Implementación del Plan Ciudadano de Oro 2023/2030.');
        $this->accion($oe4, 4,  'Definir como Punto Focal del Observatorio de adulto Mayor del IPS, a la Dirección de Medicina Preventiva y Programas de Salud.');
        $this->accion($oe4, 5,  'Habilitar "La casa del ciudadano de oro" Clínica de atención ambulatoria al Adulto Mayor.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // EJE 3
    // ══════════════════════════════════════════════════════════════════════════
    private function eje3(): void
    {
        $eje = $this->axi(3, 'Eje Estratégico 3: Innovación tecnológica y optimización en la gestión estratégica y administrativa.');

        // OE 1
        $oe1 = $this->goal($eje, 1, 'Objetivo Estratégico 1- Fortalecer el direccionamiento estratégico institucional basados en la planificación, la gestión por procesos y el ambiente de control interno.');
        $this->accion($oe1, 1,  'Capacitar al funcionariado del Instituto de Previsión Social en Planificación Estratégica, Gestión por Procesos y Control Interno.');
        $this->accion($oe1, 2,  'Socializar y difundir el nuevo Plan Estratégico 2023-2028.');
        $this->accion($oe1, 3,  'Monitorear el cumplimiento de las metas del Plan Estratégico Institucional (PEI) 2023-2028.');
        $this->accion($oe1, 4,  'Analizar documentos internos y estructuras organizacional existentes en el IPS.');
        $this->accion($oe1, 5,  'Fortalecer los Procesos de Agendamientos de los Establecimientos de Salud del IPS.');
        $this->accion($oe1, 6,  'Elaborar el Plan de Gestión de la Calidad Institucional para los Establecimientos de Salud del IPS.');
        $this->accion($oe1, 7,  'Implementar el Plan de Gestión de la Calidad Institucional en forma progresiva en los Establecimientos de Salud del IPS.');
        $this->accion($oe1, 8,  'Aplicar el Programa de Calidad y Calidez en la Atención en las áreas de atención al público.');
        $this->accion($oe1, 9,  'Impulsar la cultura de la seguridad del paciente en los hospitales cabeceras de la RIISS.');
        $this->accion($oe1, 10, 'Implementar el Plan de Trabajo de las Normas de Requisitos Mínimos MECIP del Sistema de Control Interno.');
        $this->accion($oe1, 11, 'Apoyo técnico para la puesta en marcha del Convenio con el MTESS, a través del Programa de Promoción de la Seguridad y Salud en el Trabajo.');
        $this->accion($oe1, 12, 'Realizar el análisis de la RIISS del IPS para la Categorización por Niveles de Atención.');
        $this->accion($oe1, 13, 'Articular acciones con instituciones afines al cumplimiento de nuestra Misión institucional.');
        $this->accion($oe1, 14, 'Realizar el análisis de la operatividad del sistema SAOP.');

        // OE 2
        $oe2 = $this->goal($eje, 2, 'Objetivo Estratégico 2- Lograr un sistema de abastecimiento de bienes y servicios oportuno y eficiente.');
        $this->accion($oe2, 1,  'Dotar de medicamentos, insumos y dispositivos médicos requeridos por las áreas de salud del IPS.');
        $this->accion($oe2, 2,  'Simplificar los procesos de adquisición de bienes y servicios según reglamentación vigente.');

        // OE 3
        $oe3 = $this->goal($eje, 3, 'Objetivo Estratégico 3- Mejorar las políticas del talento humano vigentes.');
        $this->accion($oe3, 1,  'Gestionar y actualizar la movilidad interna o traslados temporales, en función a las necesidades institucionales.');
        $this->accion($oe3, 2,  'Implementar el Proceso de Inducción al personal que se incorpora a la Institución.');
        $this->accion($oe3, 3,  'Implementar Concursos de Méritos para el ingreso de todos los profesionales bajo el régimen de la Ley N° 1626/2000.');
        $this->accion($oe3, 4,  'Establecer compensaciones conforme al componente de educación formal acreditada en el IPS.');
        $this->accion($oe3, 5,  'Fortalecer la gestión del talento humano para la descentralización de los procesos con el uso de la tecnología.');
        $this->accion($oe3, 6,  'Actualizar el Manual de Organización y Funciones de la Dirección Gestión y Desarrollo del Talento Humano.');
        $this->accion($oe3, 7,  'Actualización de datos personales de los funcionarios, personal contratado, personal en cargos de confianza y trasladados temporalmente de otros OEE al IPS.');
        $this->accion($oe3, 8,  'Establecer compensaciones conforme al componente de experiencia laboral (antigüedad) en el IPS.');
        $this->accion($oe3, 9,  'Fortalecer las competencias laborales y personales del talento humano.');

        // OE 4
        $oe4 = $this->goal($eje, 4, 'Objetivo Estratégico 4- Innovar las tecnologías de información, comunicación y ciber-seguridad.');
        $this->accion($oe4, 1,  'Fortalecer la infraestructura de hardware y software implementando la política de ciberseguridad del MITIC.');
        $this->accion($oe4, 2,  'Realizar eventos de capacitación para el uso correcto e integral de las herramientas tecnológicas del IPS.');
        $this->accion($oe4, 3,  'Realizar capacitaciones de Seguridad de la Información a los usuarios de las distintas dependencias del IPS.');
        $this->accion($oe4, 4,  'Implementar firma digital en los procesos internos institucionales.');
        $this->accion($oe4, 5,  'Puesta en Marcha del Tablero Control Gerencial del IPS.');
        $this->accion($oe4, 6,  'Utilizar espacio en la nube para proveer y compartir informaciones a organismos del Estado.');
        $this->accion($oe4, 7,  'Gestionar un sistema integrado de información para todo el IPS.');

        // OE 5
        $oe5 = $this->goal($eje, 5, 'Objetivo Estratégico 5- Promover Calidad en todos los proyectos de infraestructura física, mantenimiento edilicio y de los equipamientos de la Institución actuales y futuros.');
        $this->accion($oe5, 1,  'Actualizar y aprobar las Licencias Ambientales.');
        $this->accion($oe5, 2,  'Optimizar el convenio de provisión de combustible y lubricantes existente.');
        $this->accion($oe5, 3,  'Disminuir la flota de rodados en desuso.');
        $this->accion($oe5, 4,  'Gestionar llamado a Licitación para los servicios de seguridad.');
        $this->accion($oe5, 5,  'Gestionar llamado a Licitación para los servicios de limpieza.');
        $this->accion($oe5, 6,  'Gestionar llamado a Licitación para los servicios de producciones varias, muebles y útiles.');
        $this->accion($oe5, 7,  'Gestionar llamado a Licitación para los servicios de fumigación general.');
        $this->accion($oe5, 8,  'Ejecutar el contrato de adjudicación de la obra Diseño y Construcción del Centro de Especialidades Médicas (CEM).');
        $this->accion($oe5, 9,  'Ejecutar el contrato de adjudicación de la obra Diseño y Construcción para la Clínica Periférica de Luque.');
        $this->accion($oe5, 10, 'Ejecutar el contrato de adjudicación de la obra Ampliación y Refacción del Hospital Regional de Encarnación.');
        $this->accion($oe5, 11, 'Ejecutar el contrato de adjudicación de la obra Diseño y Construcción del Complejo Hospitalario de Lambaré.');
        $this->accion($oe5, 12, 'Mantenimiento general anual, preventivo y correctivo de: Obras civiles, áreas verdes, instalaciones eléctricas, sanitarias, mecánicas y electromecánicas.');
        $this->accion($oe5, 13, 'Adquirir nuevos vehículos para fortalecer la flota de vehículos del IPS.');
        $this->accion($oe5, 14, 'Adquirir equipos biomédicos para responder a la demanda de los servicios de la RIISS.');
    }

    // ── HELPERS ───────────────────────────────────────────────────────────────

    private function perfil(array $data): string
    {
        $id = (string) Str::uuid();
        DB::table('planificacion.pei_profiles')->insert(array_merge([
            'id'            => $id,
            'type'          => 'corporative',
            'year_start'    => '2023-01-01',
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

    private function axi(int $orden, string $nombre): string
    {
        return $this->perfil([
            'name'       => '<p><strong>' . $nombre . '</strong></p>',
            'level'      => 'axi',
            'parent_id'  => $this->masterId,
            'order_item' => $orden,
        ]);
    }

    private function goal(string $axiId, int $orden, string $nombre): string
    {
        return $this->perfil([
            'name'       => '<p>' . $nombre . '</p>',
            'level'      => 'goal',
            'parent_id'  => $axiId,
            'order_item' => $orden,
        ]);
    }

    private function accion(string $goalId, int $orden, string $nombre): string
    {
        return $this->perfil([
            'name'       => '<p>' . $nombre . '</p>',
            'level'      => 'action',
            'parent_id'  => $goalId,
            'order_item' => $orden,
        ]);
    }
}
