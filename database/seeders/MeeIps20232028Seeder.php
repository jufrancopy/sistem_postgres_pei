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
    private function getPerfilIds(): array
    {
        return \DB::table('planificacion.pei_profiles')
            ->where('level', 'master')
            ->whereIn('id', ['ce99f883-fdd0-4723-8f75-cf689aa8f0fa', '766eb883-fdd0-4723-8f75-cf689aa8f0fa'])
            ->whereNull('deleted_at')
            ->pluck('id')
            ->toArray();
    }

    public function run(): void
    {
        $ahora     = now();
        $perfilIds = $this->getPerfilIds();

        foreach ($perfilIds as $perfilId) {
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
                    'marco_legal'  => 'Ley N° 5655/2016 — Complementaria del Seguro Social',
                    'competencias' => "Modifica y amplía disposiciones relativas a las prestaciones económicas y la cobertura previsional.",
                    'orden'        => 4,
                ],
            ];

            foreach ($marcos as $m) {
                MeeMarcoLegal::updateOrCreate(
                    ['pei_profile_id' => $perfilId, 'marco_legal' => $m['marco_legal']],
                    array_merge($m, ['pei_profile_id' => $perfilId])
                );
            }

            // ── Sección B: Oferta de Servicios ────────────────────────────────────
            $ofertas = [
                ['accion' => 'Atención Médica Ambulatoria y Citas Médicas Especializadas', 'descripcion' => 'Consultas en especialidades clínicas y quirúrgicas en toda la Red Asistencial.'],
                ['accion' => 'Servicio de Urgencias y Emergencias Sanitarias 24/7', 'descripcion' => 'Atención de emergencias médicas y quirúrgicas en hospitales centrales y regionales.'],
                ['accion' => 'Provisión de Medicamentos e Insumos Médicos Esenciales', 'descripcion' => 'Suministro de fármacos del Cuadro Básico Institucional en farmacias asistenciales.'],
                ['accion' => 'Concesión de Jubilaciones por Vejez e Invalidez', 'descripcion' => 'Trámite y otorgamiento de haberes jubilatorios a cotizantes calificados.'],
                ['accion' => 'Pago de Subsidios y Pensiones a Derecho-habientes', 'descripcion' => 'Subsidios por enfermedad, maternidad, accidentes laborales y pensiones a sobrevivientes.'],
            ];

            foreach ($ofertas as $o) {
                MeeOfertaServicio::updateOrCreate(
                    ['pei_profile_id' => $perfilId, 'accion' => $o['accion']],
                    array_merge($o, ['pei_profile_id' => $perfilId])
                );
            }
        }

        $this->command->info('✅ MEE sembrado para todos los perfiles PEI.');
    }
}
