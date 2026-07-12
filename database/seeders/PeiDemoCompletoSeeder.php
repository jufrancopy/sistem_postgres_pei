<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\PeiAccionPgn;
use App\Models\Planificacion\MeeMarcoLegal;
use App\Models\Planificacion\MeeOfertaServicio;

/**
 * PeiDemoCompletoSeeder
 *
 * Crea un PEI de demostración completo con:
 *  - Misión, Visión, Valores
 *  - 2 Ejes estratégicos con Resultado Intermedio, BSC, FODA, Marcos
 *  - 2 Objetivos por eje
 *  - 2 Acciones por objetivo con Indicadores, Responsables, PGN
 *  - Marco Estratégico Específico (MEE): marcos legales + oferta
 *
 * Uso: php artisan db:seed --class=PeiDemoCompletoSeeder
 */
class PeiDemoCompletoSeeder extends Seeder
{
    private Carbon $now;
    private string $masterId;
    private string $nivelLabel;

    // IDs reales del sistema
    const ORG_IPS   = 8;
    const USER_ID   = 201;

    public function run(): void
    {
        $this->now = Carbon::now();
        $this->nivelLabel = json_encode([
            'master' => 'PEI',
            'axi'    => 'Eje Estratégico',
            'goal'   => 'Objetivo',
            'action' => 'Acción',
        ]);

        $this->command->info('── Creando PEI Demo Completo...');
        $this->crearMaster();
        $this->crearEjes();
        PeiProfile::fixTree();
        $this->crearMee();
        $this->command->info('✅ PEI Demo Completo creado: ' . $this->masterId);
    }

    // ── MASTER ────────────────────────────────────────────────────────────────
    private function crearMaster(): void
    {
        $this->masterId = $this->perfil([
            'name'        => '[DEMO] Plan Estratégico Institucional 2025–2028',
            'level'       => 'master',
            'type'        => 'corporative',
            'year_start'  => '2025-01-01',
            'year_end'    => '2028-12-31',
            'dependency_id' => self::ORG_IPS,
            'nivel_label' => $this->nivelLabel,
            'mision'      => '<p>Garantizar, oportuna y eficientemente, las prestaciones del Seguro Social con calidad y calidez, contribuyendo al bienestar de los asegurados y sus familias.</p>',
            'vision'      => '<p>Ser la institución líder en seguridad social del Paraguay, con amplia cobertura, gestión eficiente y tecnología de vanguardia al servicio de la ciudadanía.</p>',
            'values'      => '<p><strong>Transparencia</strong> · <strong>Eficiencia</strong> · <strong>Equidad</strong> · <strong>Innovación</strong> · <strong>Compromiso Social</strong></p>',
        ]);
    }

    // ── EJES ──────────────────────────────────────────────────────────────────
    private function crearEjes(): void
    {
        // ── EJE I: Salud ──
        $eje1 = $this->perfil([
            'name'                  => '<p><strong>Eje I — Fortalecimiento de la Red de Salud con calidad y cobertura universal</strong></p>',
            'level'                 => 'axi',
            'type'                  => 'corporative',
            'parent_id'             => $this->masterId,
            'dependency_id'         => self::ORG_IPS,
            'nivel_label'           => $this->nivelLabel,
            'order_item'            => 1,
            'bsc_perspectiva'       => 'clientes',
            'resultado_intermedio'  => 'Asegurados y beneficiarios acceden a servicios de salud integrales y de calidad',
            'ri_presupuestario'     => 'Trabajadores dependientes que aportan al seguro social reciben prestaciones sanitarias y económicas a corto plazo',
            'ri_programa'           => 'Programa Central — Actividad: Servicios de Prestaciones Sanitarias',
            'ri_recursos_gs'        => 6341255316165,
            'ri_metas'              => json_encode([
                ['anio' => 2025, 'valor' => '85% cobertura'],
                ['anio' => 2026, 'valor' => '88% cobertura'],
                ['anio' => 2027, 'valor' => '90% cobertura'],
                ['anio' => 2028, 'valor' => '92% cobertura'],
            ]),
        ]);

        // Vincular estrategia FODA al eje 1
        DB::table('planificacion.pei_profiles_has_strategies')->insertOrIgnore([
            'profile_id'  => $eje1,
            'strategy_id' => 10, // FO — Difusión Programas Sociales
        ]);

        // Vincular marco referencial ODS 3
        $ods3 = \App\Models\Planificacion\MarcoReferencial::where('nombre','like','%ODS 3%')->value('id');
        if ($ods3) {
            DB::table('planificacion.pei_profile_marcos')->insertOrIgnore([
                'pei_profile_id' => $eje1,
                'marco_id'       => $ods3,
            ]);
        }

        // Objetivos del Eje I
        $obj11 = $this->perfil([
            'name'          => '<p>Ampliar la cobertura de servicios de salud preventiva en la red de establecimientos</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje1,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 1,
        ]);

        $this->crearAccion($obj11, 1, [
            'name'       => '<p>Implementar programa de telemedicina en 15 centros periféricos de la red IPS</p>',
            'indicador_id' => 1, // IPS-001 Razón mortalidad materna
            'pgn_nodo_id'  => 9,  // Clínicas Periféricas
            'monto_vs'     => 180000000000,
            'monto_ej'     => 95000000000,
            'semaforo'     => 'amarillo',
            'numerator'    => 8,
            'denominator'  => 15,
        ]);

        $this->crearAccion($obj11, 2, [
            'name'       => '<p>Capacitar 300 funcionarios en protocolos de atención primaria y salud preventiva</p>',
            'indicador_id' => 4, // IPS-004 Cobertura vacunación
            'pgn_nodo_id'  => 10, // Programas de Salud Preventiva
            'monto_vs'     => 120000000000,
            'monto_ej'     => 98000000000,
            'semaforo'     => 'verde',
            'numerator'    => 255,
            'denominator'  => 300,
        ]);

        $obj12 = $this->perfil([
            'name'          => '<p>Modernizar la infraestructura hospitalaria en establecimientos prioritarios de la red</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje1,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 2,
        ]);

        $this->crearAccion($obj12, 1, [
            'name'         => '<p>Renovar equipos biomédicos en hospitales de área interior de la red RIISS</p>',
            'indicador_id' => 7,  // IPS-007 Trazabilidad
            'pgn_nodo_id'  => 7,  // Equipamiento Hospitales Regionales
            'monto_vs'     => 200000000000,
            'monto_ej'     => 175000000000,
            'semaforo'     => 'verde',
            'numerator'    => 7,
            'denominator'  => 8,
        ]);

        $this->crearAccion($obj12, 2, [
            'name'         => '<p>Implementar sistema de Historia Clínica Electrónica en todos los establecimientos de la red</p>',
            'indicador_id' => 9,  // IPS-009 Herramienta digital
            'pgn_nodo_id'  => 11, // Digitalización Expedientes
            'monto_vs'     => 150000000000,
            'monto_ej'     => 138000000000,
            'semaforo'     => 'verde',
            'numerator'    => 90,
            'denominator'  => 100,
        ]);

        // ── EJE II: Gestión Institucional ──
        $eje2 = $this->perfil([
            'name'                  => '<p><strong>Eje II — Modernización de la Gestión Institucional y Sostenibilidad Financiera</strong></p>',
            'level'                 => 'axi',
            'type'                  => 'corporative',
            'parent_id'             => $this->masterId,
            'dependency_id'         => self::ORG_IPS,
            'nivel_label'           => $this->nivelLabel,
            'order_item'            => 2,
            'bsc_perspectiva'       => 'procesos',
            'resultado_intermedio'  => 'Administración institucional eficiente con talento humano profesionalizado y recursos optimizados',
            'ri_presupuestario'     => 'Administración institucional eficiente',
            'ri_programa'           => 'Programa Central — Actividad: Gestión Administrativa Institucional',
            'ri_recursos_gs'        => 467065260973,
            'ri_metas'              => json_encode([
                ['anio' => 2025, 'valor' => '70% procesos digitalizados'],
                ['anio' => 2026, 'valor' => '80% procesos digitalizados'],
                ['anio' => 2027, 'valor' => '90% procesos digitalizados'],
                ['anio' => 2028, 'valor' => '95% procesos digitalizados'],
            ]),
        ]);

        // Marco PND
        $pnd = \App\Models\Planificacion\MarcoReferencial::where('nombre','like','%OE 4.1%')->value('id');
        if ($pnd) {
            DB::table('planificacion.pei_profile_marcos')->insertOrIgnore([
                'pei_profile_id' => $eje2,
                'marco_id'       => $pnd,
            ]);
        }

        $obj21 = $this->perfil([
            'name'          => '<p>Alinear la planificación estratégica, gestión por procesos y control interno MECIP con la transformación digital</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje2,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 1,
        ]);

        $this->crearAccion($obj21, 1, [
            'name'         => '<p>Implementar el Plan de Trabajo MECIP y fortalecer la cultura de control interno institucional</p>',
            'indicador_id' => 16, // IPS-016 MECIP
            'pgn_nodo_id'  => 12, // Sistema Gestión RRHH
            'monto_vs'     => 50000000000,
            'monto_ej'     => 35000000000,
            'semaforo'     => 'amarillo',
            'numerator'    => 55,
            'denominator'  => 100,
        ]);

        $this->crearAccion($obj21, 2, [
            'name'         => '<p>Analizar, rediseñar y documentar los procesos institucionales orientados a resultados</p>',
            'indicador_id' => 17, // IPS-017 Procesos
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'rojo',
            'numerator'    => 25,
            'denominator'  => 100,
        ]);

        $obj22 = $this->perfil([
            'name'          => '<p>Mejorar la eficiencia de la gestión administrativa y el desarrollo del talento humano institucional</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje2,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 2,
        ]);

        $this->crearAccion($obj22, 1, [
            'name'         => '<p>Implementar concursos de méritos para el ingreso y fortalecer el sistema de carrera del talento humano</p>',
            'indicador_id' => 20, // IPS-020 Cargos por concurso
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'amarillo',
            'numerator'    => 40,
            'denominator'  => 100,
        ]);

        $this->crearAccion($obj22, 2, [
            'name'         => '<p>Optimizar la ejecución del Programa Anual de Contrataciones y simplificar procesos de adquisición</p>',
            'indicador_id' => 21, // IPS-021 PAC
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'verde',
            'numerator'    => 78,
            'denominator'  => 100,
        ]);

        // ── EJE III: Sostenibilidad Financiera (BSC: Financiera) ──
        $eje3 = $this->perfil([
            'name'                  => '<p><strong>Eje III — Sostenibilidad Financiera y Transparencia en el Uso de Recursos</strong></p>',
            'level'                 => 'axi',
            'type'                  => 'corporative',
            'parent_id'             => $this->masterId,
            'dependency_id'         => self::ORG_IPS,
            'nivel_label'           => $this->nivelLabel,
            'order_item'            => 3,
            'bsc_perspectiva'       => 'financiera',
            'resultado_intermedio'  => 'Jubilados y Pensionados reciben sus haberes jubilatorios con oportunidad y calidad',
            'ri_presupuestario'     => 'Jubilados y Pensionados reciben sus haberes jubilatorios',
            'ri_programa'           => 'Programa Central — Actividad: Gestión para Jubilados y Pensionados',
            'ri_recursos_gs'        => 6385106032434,
            'ri_metas'              => json_encode([
                ['anio' => 2025, 'valor' => 'Rentabilidad real positiva'],
                ['anio' => 2026, 'valor' => 'LB + 0.5 pp'],
                ['anio' => 2027, 'valor' => 'LB + 1.0 pp'],
                ['anio' => 2028, 'valor' => 'Mantener rentabilidad positiva'],
            ]),
        ]);

        $obj31 = $this->perfil([
            'name'          => '<p>Garantizar la sostenibilidad financiera de los fondos del seguro social con criterios de rentabilidad</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje3,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 1,
        ]);

        $this->crearAccion($obj31, 1, [
            'name'         => '<p>Diversificar e invertir las reservas técnicas bajo criterios de seguridad, liquidez y rentabilidad</p>',
            'indicador_id' => 13, // IPS-013 Rentabilidad reservas
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'verde',
            'numerator'    => 92,
            'denominator'  => 100,
        ]);

        $this->crearAccion($obj31, 2, [
            'name'         => '<p>Aumentar la recaudación del Aporte Obrero Patronal mediante vínculos interinstitucionales y fiscalización</p>',
            'indicador_id' => 15, // IPS-015 Variación recaudación AOP
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'amarillo',
            'numerator'    => 5,
            'denominator'  => 10,
        ]);

        $obj32 = $this->perfil([
            'name'          => '<p>Asegurar el acceso oportuno a las prestaciones económicas de trabajadores, jubilados y pensionados</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje3,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 2,
        ]);

        $this->crearAccion($obj32, 1, [
            'name'         => '<p>Concesión oportuna, automatizada y descentralizada de prestaciones económicas del Seguro Social</p>',
            'indicador_id' => 10, // IPS-010 Prestaciones dentro del plazo
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'verde',
            'numerator'    => 85,
            'denominator'  => 100,
        ]);

        // ── EJE IV: Capital Humano e Innovación (BSC: Aprendizaje) ──
        $eje4 = $this->perfil([
            'name'                  => '<p><strong>Eje IV — Desarrollo del Capital Humano, Innovación y Transformación Digital</strong></p>',
            'level'                 => 'axi',
            'type'                  => 'corporative',
            'parent_id'             => $this->masterId,
            'dependency_id'         => self::ORG_IPS,
            'nivel_label'           => $this->nivelLabel,
            'order_item'            => 4,
            'bsc_perspectiva'       => 'aprendizaje',
            'resultado_intermedio'  => 'Talento humano profesionalizado e infraestructura tecnológica moderna que sustenta la gestión institucional',
            'ri_presupuestario'     => 'Administración institucional eficiente',
            'ri_programa'           => 'Programa Central — Actividad: Desarrollo Institucional y Tecnológico',
            'ri_recursos_gs'        => 280000000000,
            'ri_metas'              => json_encode([
                ['anio' => 2025, 'valor' => '60% conectividad'],
                ['anio' => 2026, 'valor' => '75% conectividad'],
                ['anio' => 2027, 'valor' => '90% conectividad'],
                ['anio' => 2028, 'valor' => '95% conectividad'],
            ]),
        ]);

        $obj41 = $this->perfil([
            'name'          => '<p>Fortalecer la infraestructura tecnológica e implementar la política de ciberseguridad del MITIC</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje4,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 1,
        ]);

        $this->crearAccion($obj41, 1, [
            'name'         => '<p>Expandir la conectividad institucional a todos los establecimientos priorizados de la red IPS</p>',
            'indicador_id' => 18, // IPS-018 Conectividad establecimientos
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'amarillo',
            'numerator'    => 60,
            'denominator'  => 100,
        ]);

        $this->crearAccion($obj41, 2, [
            'name'         => '<p>Implementar controles de ciberseguridad y fortalecer la infraestructura tecnológica según política MITIC</p>',
            'indicador_id' => 19, // IPS-019 Ciberseguridad
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'amarillo',
            'numerator'    => 65,
            'denominator'  => 100,
        ]);

        $obj42 = $this->perfil([
            'name'          => '<p>Implementar concursos de méritos y fortalecer el sistema de carrera del talento humano del IPS</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje4,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => 2,
        ]);

        $this->crearAccion($obj42, 1, [
            'name'         => '<p>Capacitar al 100% del personal en competencias digitales y nuevas herramientas de gestión institucional</p>',
            'indicador_id' => 20, // IPS-020 Cargos por concurso
            'pgn_nodo_id'  => null,
            'monto_vs'     => null,
            'monto_ej'     => null,
            'semaforo'     => 'rojo',
            'numerator'    => 25,
            'denominator'  => 100,
        ]);
    }

    // ── MARCO ESTRATÉGICO ESPECÍFICO ──────────────────────────────────────────
    private function crearMee(): void
    {
        $masterUuid = $this->masterId;

        $marcos = [
            ['marco_legal' => 'Carta Orgánica del IPS', 'competencias' => "Define la naturaleza, fines y atribuciones institucionales:\n• Administrar el sistema de seguridad social\n• Otorgar prestaciones de salud y previsionales\n• Recaudar aportes y gestionar fondos", 'orden' => 1],
            ['marco_legal' => 'Manual MECIP', 'competencias' => "Implementa sistema de control interno:\n• Establecer normas y procesos de control\n• Gestionar riesgos institucionales\n• Evaluar cumplimiento de objetivos", 'orden' => 2],
            ['marco_legal' => 'Decreto Nº 8.841 Estatuto del Funcionario del IPS', 'competencias' => "Regula las relaciones laborales IPS-funcionarios:\n• Derechos y obligaciones laborales\n• Carrera administrativa y méritos\n• Régimen disciplinario", 'orden' => 3],
        ];

        foreach ($marcos as $m) {
            MeeMarcoLegal::firstOrCreate(
                ['pei_profile_id' => $masterUuid, 'marco_legal' => $m['marco_legal']],
                array_merge($m, ['pei_profile_id' => $masterUuid, 'created_at' => $this->now, 'updated_at' => $this->now])
            );
        }

        $ofertas = [
            ['accion' => 'Prestaciones de salud', 'descripcion' => 'Servicios integrales de atención: promoción, prevención, consultas, internaciones, cirugías, rehabilitación, terapias y provisión de medicamentos.', 'beneficiarios' => 'Asegurados activos y derechohabientes', 'orden' => 1],
            ['accion' => 'Prestaciones económicas', 'descripcion' => 'Jubilaciones, pensiones, subsidios de maternidad, enfermedad y accidentes laborales derivados de la seguridad social.', 'beneficiarios' => 'Asegurados activos, jubilados, pensionados y derechohabientes', 'orden' => 2],
            ['accion' => 'Administración y gestión institucional', 'descripcion' => 'Servicios administrativos, gestión de aportes patronales y soporte operativo a los asegurados y dependencias.', 'beneficiarios' => 'Empleadores, asegurados y dependencias internas del IPS', 'orden' => 3],
        ];

        foreach ($ofertas as $o) {
            MeeOfertaServicio::firstOrCreate(
                ['pei_profile_id' => $masterUuid, 'accion' => $o['accion']],
                array_merge($o, ['pei_profile_id' => $masterUuid, 'created_at' => $this->now, 'updated_at' => $this->now])
            );
        }
    }

    // ── HELPERS ───────────────────────────────────────────────────────────────
    private function perfil(array $data): string
    {
        $existe = DB::table('planificacion.pei_profiles')
            ->where('name', $data['name'])
            ->where('level', $data['level'])
            ->whereNull('deleted_at')
            ->first();

        if ($existe) return $existe->id;

        $id = (string) Str::uuid();
        DB::table('planificacion.pei_profiles')->insert(array_merge([
            'id'          => $id,
            'type'        => 'corporative',
            'year_start'  => '2025-01-01',
            'year_end'    => '2028-12-31',
            'report_type' => 'quantitative',
            'user_id'     => self::USER_ID,
            '_lft'        => 0,
            '_rgt'        => 0,
            'ri_metas'    => '[]',
            'created_at'  => $this->now,
            'updated_at'  => $this->now,
        ], $data));

        return $id;
    }

    private function crearAccion(string $parentId, int $orden, array $opts): void
    {
        $accionId = $this->perfil([
            'name'          => $opts['name'],
            'level'         => 'action',
            'type'          => 'corporative',
            'parent_id'     => $parentId,
            'dependency_id' => self::ORG_IPS,
            'nivel_label'   => $this->nivelLabel,
            'order_item'    => $orden,
            'indicador_id'  => $opts['indicador_id'] ?? null,
            'semaforo'      => $opts['semaforo'] ?? 'sin-datos',
            'numerator'     => $opts['numerator'] ?? null,
            'denominator'   => $opts['denominator'] ?? null,
            'progress'      => $opts['numerator'] ?? null,
            'report_type'   => 'quantitative',
        ]);

        // Asignar responsable (verificar que no exista antes de insertar)
        $yaExiste = DB::table('planificacion.peis_profiles_has_responsibles')
            ->where('profile_id', $accionId)
            ->where('responsible_id', self::ORG_IPS)
            ->exists();

        if (!$yaExiste) {
            DB::table('planificacion.peis_profiles_has_responsibles')->insert([
                'profile_id'     => $accionId,
                'responsible_id' => self::ORG_IPS,
                'rol'            => 'A',
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ]);
        }

        // Vincular PGN
        if (!empty($opts['pgn_nodo_id'])) {
            PeiAccionPgn::firstOrCreate(
                ['pei_profile_id' => $accionId, 'pgn_nodo_id' => $opts['pgn_nodo_id']],
                [
                    'resultado'          => 'Resultado vinculado al eje estratégico',
                    'monto_vinculado_gs' => $opts['monto_vs'],
                    'monto_ejecutado_gs' => $opts['monto_ej'],
                ]
            );
        }
    }
}
