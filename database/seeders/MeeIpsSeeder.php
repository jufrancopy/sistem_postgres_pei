<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Planificacion\MeeMarcoLegal;
use App\Models\Planificacion\MeeOfertaServicio;

class MeeIpsSeeder extends Seeder
{
    private function getPerfilId(): string
    {
        $perfil = \DB::table('planificacion.pei_profiles')
            ->where('level', 'master')
            ->where('name', 'like', '%Plan Estratégico Institucional IPS 2024%')
            ->whereNull('deleted_at')
            ->whereNull('parent_id')
            ->first();

        if (!$perfil) {
            throw new \Exception('No se encontró el perfil PEI IPS 2024. Ejecutá PeiIps2024Seeder primero.');
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
                'marco_legal'  => 'Código de Ética Versión 5 / 2024',
                'competencias' => "Regula conducta y valores institucionales:\n• Establecer principios éticos\n• Regular comportamiento de funcionarios\n• Prevenir conflictos de interés",
                'orden'        => 2,
            ],
            [
                'marco_legal'  => 'Código de Buen Gobierno',
                'competencias' => "Define lineamientos de gobernanza institucional:\n• Promover transparencia\n• Fortalecer rendición de cuentas\n• Regular toma de decisiones",
                'orden'        => 3,
            ],
            [
                'marco_legal'  => 'Contrato Colectivo de Condiciones de Trabajo',
                'competencias' => "Regula relaciones laborales IPS-funcionarios:\n• Establecer derechos y obligaciones laborales\n• Definir beneficios, horarios y condiciones\n• Regular negociación colectiva",
                'orden'        => 4,
            ],
            [
                'marco_legal'  => 'Contrato Colectivo de Condiciones de Trabajo 2023',
                'competencias' => "Regula relaciones laborales IPS-funcionarios:\n• Establecer derechos y obligaciones laborales\n• Definir beneficios, horarios y condiciones\n• Regular negociación colectiva",
                'orden'        => 5,
            ],
            [
                'marco_legal'  => 'Decreto Nº 8.841 Estatuto del Funcionario del IPS',
                'competencias' => "Actualización de condiciones laborales:\n• Ajustar beneficios y condiciones vigentes\n• Incorporar mejoras laborales\n• Regular nuevas disposiciones",
                'orden'        => 6,
            ],
            [
                'marco_legal'  => 'Manual MECIP',
                'competencias' => "Implementa sistema de control interno:\n• Establecer normas y procesos de control\n• Gestionar riesgos institucionales\n• Evaluar cumplimiento de objetivos",
                'orden'        => 7,
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
                'descripcion'   => 'Servicios integrales de atención: promoción y prevención, consultas, internaciones, cirugías, rehabilitación y terapias. Asimismo, contempla la provisión de medicamentos, insumos médicos y dispositivos médicos necesarios para la atención.',
                'beneficiarios' => null,
                'orden'         => 1,
            ],
            [
                'accion'        => 'Prestaciones económicas',
                'descripcion'   => 'Beneficios monetarios derivados de la seguridad social: jubilaciones, pensiones y subsidios (maternidad, enfermedad y otras contingencias), garantizando riesgos que comprenden la cobertura de la seguridad social.',
                'beneficiarios' => 'Asegurados activos, jubilados, pensionados y derechohabientes',
                'orden'         => 2,
            ],
            [
                'accion'        => 'Administración y gestión institucional',
                'descripcion'   => 'Asegurados, jubilados, pensionados y beneficiarios',
                'beneficiarios' => 'Empleadores, asegurados, beneficiarios y dependencias internas del IPS',
                'orden'         => 3,
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

        $this->command->info('✅ MEE IPS sembrado: ' . count($marcos) . ' marcos legales + ' . count($ofertas) . ' ofertas de servicio.');
    }
}
