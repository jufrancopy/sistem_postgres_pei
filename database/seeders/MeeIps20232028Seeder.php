<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Planificacion\MeeMarcoLegal;
use App\Models\Planificacion\MeeOfertaServicio;

/**
 * MeeIps20232028Seeder — Marco Estratégico Específico para PEI IPS 2023-2028
 */
class MeeIps20232028Seeder extends Seeder
{
    private function getPerfilId(): string
    {
        $perfil = \DB::table('planificacion.pei_profiles')
            ->where('level', 'master')
            ->where('name', 'like', '%Plan Estratégico Institucional%2023%')
            ->whereNull('deleted_at')
            ->whereNull('parent_id')
            ->first();

        if (!$perfil) {
            throw new \Exception('No se encontró el perfil PEI IPS 2023-2028. Ejecutá PeiIps20232028Seeder primero.');
        }
        return $perfil->id;
    }

    public function run(): void
    {
        $ahora    = now();
        $perfilId = $this->getPerfilId();
        $this->command->info('Sembrando MEE para: ' . $perfilId);

        // ── Sección A: Marco Legal ─────────────────────────────────────────────
        $marcos = [
            [
                'marco_legal'  => 'Carta Orgánica del IPS',
                'competencias' => "Define la naturaleza, fines y atribuciones institucionales:\n• Administrar el sistema de seguridad social\n• Otorgar prestaciones de salud y previsionales\n• Recaudar aportes y gestionar fondos",
                'orden'        => 1,
            ],
            [
                'marco_legal'  => 'Plan Nacional de Desarrollo Paraguay 2030',
                'competencias' => "Establece los lineamientos estratégicos nacionales:\n• Reducción de la pobreza y la desigualdad\n• Crecimiento económico inclusivo\n• Inserción de Paraguay en el mundo",
                'orden'        => 2,
            ],
            [
                'marco_legal'  => 'Objetivos de Desarrollo Sostenible (ODS) 2030',
                'competencias' => "Marco internacional de desarrollo sostenible:\n• ODS 3: Salud y bienestar\n• ODS 8: Trabajo decente y crecimiento económico\n• ODS 10: Reducción de las desigualdades\n• ODS 16: Paz, justicia e instituciones sólidas",
                'orden'        => 3,
            ],
            [
                'marco_legal'  => 'Manual MECIP — Modelo Estándar de Control Interno del Paraguay',
                'competencias' => "Implementa sistema de control interno:\n• Establecer normas y procesos de control\n• Gestionar riesgos institucionales\n• Evaluar cumplimiento de objetivos\n• Componente Corporativo de Control Estratégico",
                'orden'        => 4,
            ],
            [
                'marco_legal'  => 'Código de Ética Institucional IPS',
                'competencias' => "Regula conducta y valores institucionales:\n• Establecer principios éticos\n• Regular comportamiento de funcionarios\n• Prevenir conflictos de interés",
                'orden'        => 5,
            ],
            [
                'marco_legal'  => 'Código de Buen Gobierno IPS',
                'competencias' => "Define lineamientos de gobernanza institucional:\n• Promover transparencia\n• Fortalecer rendición de cuentas\n• Regular toma de decisiones",
                'orden'        => 6,
            ],
            [
                'marco_legal'  => 'Ley N° 1626/2000 — De la Función Pública',
                'competencias' => "Regula el régimen de la función pública:\n• Ingreso por concurso de méritos\n• Carrera administrativa\n• Derechos y obligaciones de los funcionarios",
                'orden'        => 7,
            ],
            [
                'marco_legal'  => 'Política Institucional del Adulto Mayor IPS 2021/2030',
                'competencias' => "Define lineamientos para la atención al adulto mayor:\n• Atención domiciliaria\n• Programas de bienestar\n• Observatorio del adulto mayor",
                'orden'        => 8,
            ],
        ];

        foreach ($marcos as $m) {
            MeeMarcoLegal::firstOrCreate(
                [
                    'pei_profile_id' => $perfilId,
                    'marco_legal'    => $m['marco_legal'],
                ],
                array_merge($m, [
                    'pei_profile_id' => $perfilId,
                    'created_at'     => $ahora,
                    'updated_at'     => $ahora,
                ])
            );
        }

        // ── Sección B: Oferta de Servicios ────────────────────────────────────
        $ofertas = [
            [
                'accion'        => 'Prestaciones de salud',
                'descripcion'   => 'Servicios integrales de atención médica: promoción y prevención, consultas ambulatorias, internaciones, cirugías, rehabilitación, salud mental, salud bucal, atención materno-infantil y programas de enfermedades crónicas. Incluye provisión de medicamentos, insumos médicos y dispositivos médicos.',
                'beneficiarios' => 'Asegurados activos, jubilados, pensionados y sus beneficiarios (cónyuge e hijos)',
                'orden'         => 1,
            ],
            [
                'accion'        => 'Prestaciones económicas',
                'descripcion'   => 'Beneficios monetarios derivados de la seguridad social: jubilaciones por vejez, pensiones por invalidez y derecho habiente, subsidios por maternidad, enfermedad, accidente de trabajo y otras contingencias previstas en la normativa vigente.',
                'beneficiarios' => 'Asegurados activos, jubilados, pensionados y derechohabientes',
                'orden'         => 2,
            ],
            [
                'accion'        => 'Gestión de inversiones y sostenibilidad del Fondo Común',
                'descripcion'   => 'Administración de las reservas técnicas del Fondo Común de Jubilaciones y Pensiones mediante inversiones financieras e inmobiliarias bajo criterios de seguridad, liquidez y rentabilidad, garantizando la sostenibilidad del sistema previsional.',
                'beneficiarios' => 'Jubilados, pensionados y futuros beneficiarios del sistema previsional IPS',
                'orden'         => 3,
            ],
            [
                'accion'        => 'Administración y gestión institucional',
                'descripcion'   => 'Servicios de soporte institucional: gestión del talento humano, contrataciones y adquisiciones, tecnología de la información, infraestructura y mantenimiento edilicio, control interno MECIP y planificación estratégica.',
                'beneficiarios' => 'Empleadores, asegurados, beneficiarios y dependencias internas del IPS',
                'orden'         => 4,
            ],
        ];

        foreach ($ofertas as $o) {
            MeeOfertaServicio::firstOrCreate(
                [
                    'pei_profile_id' => $perfilId,
                    'accion'         => $o['accion'],
                ],
                array_merge($o, [
                    'pei_profile_id' => $perfilId,
                    'created_at'     => $ahora,
                    'updated_at'     => $ahora,
                ])
            );
        }

        $this->command->info('✅ MEE IPS 2023-2028 sembrado: ' . count($marcos) . ' marcos legales + ' . count($ofertas) . ' ofertas de servicio.');
    }
}
