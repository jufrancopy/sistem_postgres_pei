<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * DemoSeeder — Datos de ejemplo completos para el módulo PEI
 *
 * Crea desde cero (idempotente):
 *  - 1 grupo de trabajo demo
 *  - 3 usuarios analistas demo
 *  - 1 perfil FODA con 6 análisis IEA calculados
 *  - 1 PEI completo: master → 2 ejes → 2 objetivos → 3 acciones c/u
 *  - RACI en cada acción (usando organigramas reales IDs 8-14)
 *  - Semáforo, tipo_indicador y presupuesto en cada acción
 *
 * Uso: php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    // ── Organigramas reales que usamos ────────────────────────
    const ORG_IPS        = 8;   // INSTITUTO DE PREVISIÓN SOCIAL
    const ORG_CONSEJO    = 9;   // Consejo de Administración
    const ORG_SALUD      = 10;  // Gerencia de Salud
    const ORG_LOGISTICA  = 11;  // Gerencia de Abastecimiento y Logística
    const ORG_TECNOLOGIA = 12;  // Gerencia de Desarrollo y Tecnología
    const ORG_ECONOMICAS = 13;  // Gerencia de Prestaciones Económicas
    const ORG_FINANCIERA = 14;  // Gerencia Administrativa y Financiera

    // ── Modelo FODA real ──────────────────────────────────────
    const FODA_MODEL_ID  = 1;   // Analisis FODA

    private Carbon $now;

    public function run(): void
    {
        $this->now = Carbon::now();

        $this->command->info('── Creando grupo demo...');
        $group = $this->crearGrupo();

        $this->command->info('── Creando usuarios demo...');
        $usuarios = $this->crearUsuarios();

        $this->command->info('── Creando perfil FODA con IEA...');
        $perfilFoda = $this->crearPerfilFoda($group->id);

        $this->command->info('── Creando PEI completo...');
        $this->crearPei($group->id, $usuarios, $perfilFoda->id);

        $this->command->info('── Reconstruyendo árbol nested sets...');
        \App\Admin\Planificacion\Pei\PeiProfile::fixTree();

        $this->command->info('✓ DemoSeeder completado.');
    }

    // ══════════════════════════════════════════════════════════
    // GRUPO
    // ══════════════════════════════════════════════════════════
    private function crearGrupo()
    {
        $group = DB::table('groups')->where('name', '[DEMO] Equipo de Planificación Estratégica IPS')->first();

        if (!$group) {
            $id = DB::table('groups')->insertGetId([
                'name'       => '[DEMO] Equipo de Planificación Estratégica IPS',
                'parent_id'  => null,
                '_lft'       => 0,
                '_rgt'       => 0,
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ]);
            $group = DB::table('groups')->find($id);
        }

        return $group;
    }

    // ══════════════════════════════════════════════════════════
    // USUARIOS
    // ══════════════════════════════════════════════════════════
    private function crearUsuarios(): array
    {
        $defs = [
            ['name' => '[DEMO] Ana Martínez',   'email' => 'demo.ana@ips.gov.py'],
            ['name' => '[DEMO] Carlos Benítez', 'email' => 'demo.carlos@ips.gov.py'],
            ['name' => '[DEMO] Laura Giménez',  'email' => 'demo.laura@ips.gov.py'],
        ];

        $ids = [];
        foreach ($defs as $def) {
            $user = DB::table('users')->where('email', $def['email'])->first();
            if (!$user) {
                $ids[] = DB::table('users')->insertGetId([
                    'name'       => $def['name'],
                    'email'      => $def['email'],
                    'password'   => Hash::make('Demo1234!'),
                    'created_at' => $this->now,
                    'updated_at' => $this->now,
                ]);
            } else {
                $ids[] = $user->id;
            }
        }

        return $ids; // [analista1, analista2, analista3]
    }

    // ══════════════════════════════════════════════════════════
    // PERFIL FODA + ANÁLISIS IEA
    // ══════════════════════════════════════════════════════════
    private function crearPerfilFoda(int $groupId)
    {
        $perfil = DB::table('planificacion.foda_perfiles')
            ->where('name', '[DEMO] Análisis FODA — IPS 2025')
            ->first();

        if (!$perfil) {
            $perfilId = (string) Str::uuid();
            DB::table('planificacion.foda_perfiles')->insert([
                'id'            => $perfilId,
                'name'          => '[DEMO] Análisis FODA — IPS 2025',
                'context'       => 'institucional',
                'type'          => 'grupal',
                'model_id'      => self::FODA_MODEL_ID,
                'group_id'      => $groupId,
                'dependency_id' => self::ORG_IPS,
                'created_at'    => $this->now,
                'updated_at'    => $this->now,
            ]);
            $perfil = DB::table('planificacion.foda_perfiles')->where('id', $perfilId)->first();
        }

        // Análisis IEA — 6 aspectos con distintas clasificaciones
        $aspectos = [
            // [aspecto_id, tipo,        ocurrencia, impacto, desempeno, inversion, → IEA,  clasificacion]
            [7,  'Fortaleza',  0.9, 0.9, 0.92, 1.00, 0.9200, 'fortaleza'], // IEA 0.92 → fortaleza
            [23, 'Fortaleza',  0.8, 0.8, 0.85, 0.95, 0.8947, 'fortaleza'], // IEA 0.89 → fortaleza
            [58, 'Debilidad',  0.7, 0.8, 0.30, 1.00, 0.3000, 'debilidad'], // IEA 0.30 → debilidad
            [78, 'Debilidad',  0.8, 0.9, 0.25, 0.80, 0.3125, 'debilidad'], // IEA 0.31 → debilidad
            [35, 'Oportunidad',0.6, 0.7, 0.60, 0.90, 0.6667, 'neutro'],    // IEA 0.67 → neutro
            [27, 'Amenaza',    0.7, 0.8, 0.50, 0.90, 0.5556, 'neutro'],    // IEA 0.56 → neutro
        ];

        foreach ($aspectos as [$aspectoId, $tipo, $ocurrencia, $impacto, $desempeno, $inversion, $iea, $clasificacion]) {
            $existe = DB::table('planificacion.foda_analisis')
                ->where('perfil_id', $perfil->id)
                ->where('aspecto_id', $aspectoId)
                ->exists();

            if (!$existe) {
                DB::table('planificacion.foda_analisis')->insert([
                    'user_id'                => 4,
                    'perfil_id'              => $perfil->id,
                    'aspecto_id'             => $aspectoId,
                    'tipo'                   => $tipo,
                    'ocurrencia'             => $ocurrencia,
                    'impacto'                => $impacto,
                    'promedio_desempeno_6m'  => $desempeno,
                    'inversion_historica_6m' => $inversion,
                    'iea_valor'              => $iea,
                    'iea_clasificacion'      => $clasificacion,
                    'created_at'             => $this->now,
                    'updated_at'             => $this->now,
                ]);
            }
        }

        return $perfil;
    }

    // ══════════════════════════════════════════════════════════
    // PEI COMPLETO
    // ══════════════════════════════════════════════════════════
    private function crearPei(int $groupId, array $usuarios, string $perfilFodaId): void
    {
        // ── Master ───────────────────────────────────────────
        $masterId = $this->perfil([
            'name'       => '[DEMO] Plan Estratégico Institucional IPS 2025–2028',
            'level'      => 'master',
            'type'       => 'corporative',
            'year_start' => '2025-01-01',
            'year_end'   => '2028-12-31',
            'mision'     => '<p>Garantizar, oportuna y eficientemente, las prestaciones del Seguro Social con calidad y calidez, contribuyendo al bienestar de los asegurados y sus familias.</p>',
            'vision'     => '<p>Ser la institución líder en seguridad social del Paraguay, con amplia cobertura, gestión eficiente y tecnología de vanguardia al servicio de la ciudadanía.</p>',
            'values'     => '<p><strong>Transparencia</strong> · <strong>Eficiencia</strong> · <strong>Equidad</strong> · <strong>Innovación</strong> · <strong>Compromiso Social</strong></p>',
            'group_id'   => $groupId,
            'dependency_id' => self::ORG_IPS,
            'user_id'    => $usuarios[0],
        ]);

        // Vincular analistas al master
        foreach ($usuarios as $uid) {
            $this->syncAnalista($masterId, $uid);
        }

        // ══ EJE I — Salud ════════════════════════════════════
        $eje1 = $this->perfil([
            'name'          => '<p><strong>Eje I — Fortalecimiento de la Red de Salud</strong></p>',
            'level'         => 'axi',
            'type'          => 'corporative',
            'parent_id'     => $masterId,
            'group_id'      => $groupId,
            'dependency_id' => self::ORG_SALUD,
            'user_id'       => $usuarios[0],
        ]);

        // Objetivo 1.1
        $obj11 = $this->perfil([
            'name'          => '<p>Ampliar la cobertura de servicios de salud preventiva en un 30% hacia 2028</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje1,
            'group_id'      => $groupId,
            'dependency_id' => self::ORG_SALUD,
            'user_id'       => $usuarios[0],
        ]);

        $this->crearAcciones($obj11, $groupId, $usuarios, [
            [
                'name'                  => '<p>Implementar programa de telemedicina en 15 centros periféricos</p>',
                'indicator'             => 'Nro. de centros con telemedicina activa',
                'tipo_indicador'        => 'lead',
                'baseline'              => '2',
                'target'                => '15',
                'progress'              => '13',   // 87% → verde
                'presupuesto_asignado'  => 850000,
                'presupuesto_ejecutado' => 720000, // 85% → OK
                'semaforo'              => 'verde',
                'dependency_id'         => self::ORG_SALUD,
                'raci'                  => [
                    self::ORG_SALUD      => 'A',
                    self::ORG_TECNOLOGIA => 'R',
                    self::ORG_LOGISTICA  => 'C',
                    self::ORG_FINANCIERA => 'I',
                ],
            ],
            [
                'name'                  => '<p>Capacitar 300 funcionarios en protocolos de atención primaria</p>',
                'indicator'             => 'Nro. de funcionarios capacitados',
                'tipo_indicador'        => 'lead',
                'baseline'              => '50',
                'target'                => '300',
                'progress'              => '165',  // 55% → amarillo
                'presupuesto_asignado'  => 120000,
                'presupuesto_ejecutado' => 98000,  // 82% → OK
                'semaforo'              => 'amarillo',
                'dependency_id'         => self::ORG_SALUD,
                'raci'                  => [
                    self::ORG_SALUD      => 'A',
                    self::ORG_LOGISTICA  => 'R',
                    self::ORG_FINANCIERA => 'I',
                ],
            ],
            [
                'name'                  => '<p>Reducir tasa de mortalidad intrahospitalaria en un 15%</p>',
                'indicator'             => 'Tasa de mortalidad intrahospitalaria (%)',
                'tipo_indicador'        => 'lag',
                'baseline'              => '4.2',
                'target'                => '3.57',
                'progress'              => '0.3',  // 8% → rojo
                'presupuesto_asignado'  => 500000,
                'presupuesto_ejecutado' => 430000, // 86% → ALERTA subejecución
                'semaforo'              => 'rojo',
                'dependency_id'         => self::ORG_SALUD,
                'raci'                  => [
                    self::ORG_SALUD      => 'A',
                    self::ORG_CONSEJO    => 'C',
                    self::ORG_LOGISTICA  => 'R',
                ],
            ],
        ]);

        // Objetivo 1.2
        $obj12 = $this->perfil([
            'name'          => '<p>Modernizar la infraestructura hospitalaria en 8 establecimientos prioritarios</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje1,
            'group_id'      => $groupId,
            'dependency_id' => self::ORG_SALUD,
            'user_id'       => $usuarios[1],
        ]);

        $this->crearAcciones($obj12, $groupId, $usuarios, [
            [
                'name'                  => '<p>Renovar equipos biomédicos en hospitales de área interior</p>',
                'indicator'             => 'Nro. de hospitales con equipos renovados',
                'tipo_indicador'        => 'lag',
                'baseline'              => '0',
                'target'                => '8',
                'progress'              => '7',    // 87% → verde
                'presupuesto_asignado'  => 3200000,
                'presupuesto_ejecutado' => 2800000, // 87% → OK
                'semaforo'              => 'verde',
                'dependency_id'         => self::ORG_LOGISTICA,
                'raci'                  => [
                    self::ORG_LOGISTICA  => 'A',
                    self::ORG_SALUD      => 'R',
                    self::ORG_FINANCIERA => 'C',
                    self::ORG_CONSEJO    => 'I',
                ],
            ],
            [
                'name'                  => '<p>Construir 3 nuevas clínicas periféricas en zonas de alta demanda</p>',
                'indicator'             => 'Nro. de clínicas construidas y habilitadas',
                'tipo_indicador'        => 'lag',
                'baseline'              => '0',
                'target'                => '3',
                'progress'              => '1',    // 33% → rojo + ALERTA
                'presupuesto_asignado'  => 5000000,
                'presupuesto_ejecutado' => 4300000, // 86% → ALERTA subejecución
                'semaforo'              => 'rojo',
                'dependency_id'         => self::ORG_LOGISTICA,
                'raci'                  => [
                    self::ORG_LOGISTICA  => 'A',
                    self::ORG_FINANCIERA => 'R',
                    self::ORG_CONSEJO    => 'C',
                ],
            ],
            [
                'name'                  => '<p>Implementar sistema de gestión de camas hospitalarias en tiempo real</p>',
                'indicator'             => 'Nro. de hospitales con sistema activo',
                'tipo_indicador'        => 'lead',
                'baseline'              => '0',
                'target'                => '8',
                'progress'              => '5',    // 62% → amarillo
                'presupuesto_asignado'  => 280000,
                'presupuesto_ejecutado' => 190000, // 68% → OK
                'semaforo'              => 'amarillo',
                'dependency_id'         => self::ORG_TECNOLOGIA,
                'raci'                  => [
                    self::ORG_TECNOLOGIA => 'A',
                    self::ORG_SALUD      => 'R',
                    self::ORG_LOGISTICA  => 'C',
                    self::ORG_FINANCIERA => 'I',
                ],
            ],
        ]);

        // ══ EJE II — Gestión y Sostenibilidad ════════════════
        $eje2 = $this->perfil([
            'name'          => '<p><strong>Eje II — Sostenibilidad Financiera y Gestión Eficiente</strong></p>',
            'level'         => 'axi',
            'type'          => 'corporative',
            'parent_id'     => $masterId,
            'group_id'      => $groupId,
            'dependency_id' => self::ORG_FINANCIERA,
            'user_id'       => $usuarios[1],
        ]);

        // Objetivo 2.1
        $obj21 = $this->perfil([
            'name'          => '<p>Reducir la evasión al Seguro Social en un 20% mediante fiscalización activa</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje2,
            'group_id'      => $groupId,
            'dependency_id' => self::ORG_FINANCIERA,
            'user_id'       => $usuarios[1],
        ]);

        $this->crearAcciones($obj21, $groupId, $usuarios, [
            [
                'name'                  => '<p>Ejecutar 500 fiscalizaciones a empleadores del sector formal</p>',
                'indicator'             => 'Nro. de fiscalizaciones realizadas',
                'tipo_indicador'        => 'lead',
                'baseline'              => '120',
                'target'                => '500',
                'progress'              => '460',  // 92% → verde
                'presupuesto_asignado'  => 95000,
                'presupuesto_ejecutado' => 78000,  // 82% → OK
                'semaforo'              => 'verde',
                'dependency_id'         => self::ORG_FINANCIERA,
                'raci'                  => [
                    self::ORG_FINANCIERA => 'A',
                    self::ORG_ECONOMICAS => 'R',
                    self::ORG_CONSEJO    => 'I',
                ],
            ],
            [
                'name'                  => '<p>Digitalizar 100% de los procesos de declaración y pago de aportes</p>',
                'indicator'             => '% de procesos digitalizados',
                'tipo_indicador'        => 'lead',
                'baseline'              => '30',
                'target'                => '100',
                'progress'              => '65',   // 65% → amarillo
                'presupuesto_asignado'  => 420000,
                'presupuesto_ejecutado' => 310000, // 74% → OK
                'semaforo'              => 'amarillo',
                'dependency_id'         => self::ORG_TECNOLOGIA,
                'raci'                  => [
                    self::ORG_TECNOLOGIA => 'A',
                    self::ORG_FINANCIERA => 'R',
                    self::ORG_ECONOMICAS => 'C',
                    self::ORG_CONSEJO    => 'I',
                ],
            ],
            [
                'name'                  => '<p>Reducir mora patronal en un 25% mediante planes de regularización</p>',
                'indicator'             => '% de reducción de mora patronal',
                'tipo_indicador'        => 'lag',
                'baseline'              => '18',
                'target'                => '13.5',
                'progress'              => '1.5',  // 10% → rojo + ALERTA
                'presupuesto_asignado'  => 180000,
                'presupuesto_ejecutado' => 155000, // 86% → ALERTA subejecución
                'semaforo'              => 'rojo',
                'dependency_id'         => self::ORG_FINANCIERA,
                'raci'                  => [
                    self::ORG_FINANCIERA => 'A',
                    self::ORG_ECONOMICAS => 'R',
                    self::ORG_CONSEJO    => 'C',
                ],
            ],
        ]);

        // Objetivo 2.2
        $obj22 = $this->perfil([
            'name'          => '<p>Optimizar la gestión de expedientes de jubilación reduciendo tiempos en un 40%</p>',
            'level'         => 'goal',
            'type'          => 'corporative',
            'parent_id'     => $eje2,
            'group_id'      => $groupId,
            'dependency_id' => self::ORG_ECONOMICAS,
            'user_id'       => $usuarios[2],
        ]);

        $this->crearAcciones($obj22, $groupId, $usuarios, [
            [
                'name'                  => '<p>Automatizar el flujo de aprobación de expedientes de jubilación ordinaria</p>',
                'indicator'             => 'Tiempo promedio de resolución (días)',
                'tipo_indicador'        => 'lag',
                'baseline'              => '90',
                'target'                => '54',
                'progress'              => '32',   // 88% → verde
                'presupuesto_asignado'  => 320000,
                'presupuesto_ejecutado' => 275000, // 86% → OK
                'semaforo'              => 'verde',
                'dependency_id'         => self::ORG_ECONOMICAS,
                'raci'                  => [
                    self::ORG_ECONOMICAS => 'A',
                    self::ORG_TECNOLOGIA => 'R',
                    self::ORG_FINANCIERA => 'C',
                    self::ORG_CONSEJO    => 'I',
                ],
            ],
            [
                'name'                  => '<p>Implementar ventanilla única digital para trámites previsionales</p>',
                'indicator'             => 'Nro. de trámites disponibles en plataforma digital',
                'tipo_indicador'        => 'lead',
                'baseline'              => '3',
                'target'                => '15',
                'progress'              => '9',    // 60% → amarillo
                'presupuesto_asignado'  => 210000,
                'presupuesto_ejecutado' => 145000, // 69% → OK
                'semaforo'              => 'amarillo',
                'dependency_id'         => self::ORG_TECNOLOGIA,
                'raci'                  => [
                    self::ORG_TECNOLOGIA => 'A',
                    self::ORG_ECONOMICAS => 'R',
                    self::ORG_CONSEJO    => 'I',
                ],
            ],
            [
                'name'                  => '<p>Capacitar al 100% del personal de prestaciones en el nuevo sistema</p>',
                'indicator'             => '% del personal capacitado',
                'tipo_indicador'        => 'lead',
                'baseline'              => '0',
                'target'                => '100',
                'progress'              => '15',   // 15% → rojo + ALERTA
                'presupuesto_asignado'  => 75000,
                'presupuesto_ejecutado' => 62000,  // 83% → ALERTA subejecución
                'semaforo'              => 'rojo',
                'dependency_id'         => self::ORG_ECONOMICAS,
                'raci'                  => [
                    self::ORG_ECONOMICAS => 'A',
                    self::ORG_LOGISTICA  => 'R',
                    self::ORG_TECNOLOGIA => 'C',
                ],
            ],
        ]);
    }

    // ══════════════════════════════════════════════════════════
    // HELPERS
    // ══════════════════════════════════════════════════════════

    private function perfil(array $data): string
    {
        // Idempotente por name + level
        $existe = DB::table('planificacion.pei_profiles')
            ->where('name', $data['name'])
            ->where('level', $data['level'])
            ->first();

        if ($existe) return $existe->id;

        $id = (string) Str::uuid();

        DB::table('planificacion.pei_profiles')->insert(array_merge([
            'id'          => $id,
            'type'        => 'corporative',
            'year_start'  => '2025-01-01',
            'year_end'    => '2028-12-31',
            'report_type' => 'quantitative',
            '_lft'        => 0,
            '_rgt'        => 0,
            'created_at'  => $this->now,
            'updated_at'  => $this->now,
        ], $data));

        return $id;
    }

    private function crearAcciones(string $parentId, int $groupId, array $usuarios, array $acciones): void
    {
        foreach ($acciones as $i => $accion) {
            $raci = $accion['raci'];
            unset($accion['raci']);

            $actionId = $this->perfil(array_merge($accion, [
                'level'       => 'action',
                'type'        => 'corporative',
                'parent_id'   => $parentId,
                'group_id'    => $groupId,
                'user_id'     => $usuarios[$i % count($usuarios)],
                'order_item'  => $i + 1,
            ]));

            // RACI
            foreach ($raci as $orgId => $rol) {
                $existe = DB::table('planificacion.peis_profiles_has_responsibles')
                    ->where('profile_id', $actionId)
                    ->where('responsible_id', $orgId)
                    ->exists();

                if (!$existe) {
                    DB::table('planificacion.peis_profiles_has_responsibles')->insert([
                        'profile_id'     => $actionId,
                        'responsible_id' => $orgId,
                        'rol'            => $rol,
                        'created_at'     => $this->now,
                        'updated_at'     => $this->now,
                    ]);
                }
            }
        }
    }

    private function syncAnalista(string $profileId, int $userId): void
    {
        $existe = DB::table('planificacion.peis_profiles_has_analysts')
            ->where('pei_profile_id', $profileId)
            ->where('analyst_id', $userId)
            ->exists();

        if (!$existe) {
            DB::table('planificacion.peis_profiles_has_analysts')->insert([
                'pei_profile_id' => $profileId,
                'analyst_id'     => $userId,
                'created_at'     => $this->now,
                'updated_at'     => $this->now,
            ]);
        }
    }
}
