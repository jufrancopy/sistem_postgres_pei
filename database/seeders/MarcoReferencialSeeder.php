<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcoReferencialSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        $marcos = [
            // ── PND 2050 — Pilares ────────────────────────────────────────────
            ['tipo' => 'pnd', 'nombre' => 'PND 2050 - Pilar 1: Personas y Sociedad'],
            ['tipo' => 'pnd', 'nombre' => 'PND 2050 - Pilar 2: Economía e Innovación'],
            ['tipo' => 'pnd', 'nombre' => 'PND 2050 - Pilar 3: Infraestructura y Territorio'],
            ['tipo' => 'pnd', 'nombre' => 'PND 2050 - Pilar 4: Institucionalidad y Gobernanza'],
            ['tipo' => 'pnd', 'nombre' => 'PND 2050 - Pilar 5: Sostenibilidad Ambiental'],

            // ── PND 2050 — Objetivos Estratégicos (OE) ───────────────────────
            ['tipo' => 'pnd', 'nombre' => 'PND OE 1.1 - Fomentar el desarrollo de un mercado laboral inclusivo'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 1.2 - Garantizar el acceso universal a servicios de salud'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 1.3 - Asegurar educación de calidad e inclusiva'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 1.4 - Fortalecer la protección social y previsional'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 2.1 - Diversificar y modernizar la estructura productiva'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 2.2 - Promover la innovación, ciencia y tecnología'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 3.1 - Desarrollar infraestructura de conectividad'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 4.1 - Fortalecer las instituciones públicas y el Estado de Derecho'],
            ['tipo' => 'pnd', 'nombre' => 'PND OE 5.1 - Gestionar sosteniblemente los recursos naturales'],

            // ── PND 2050 — Objetivos Específicos (OES) ───────────────────────
            ['tipo' => 'pnd', 'nombre' => 'PND OES 1.1.1 - Reducir la informalidad laboral'],
            ['tipo' => 'pnd', 'nombre' => 'PND OES 1.1.2 - Mejorar la empleabilidad de jóvenes y mujeres'],
            ['tipo' => 'pnd', 'nombre' => 'PND OES 1.2.1 - Ampliar cobertura del sistema de salud pública'],
            ['tipo' => 'pnd', 'nombre' => 'PND OES 1.2.2 - Fortalecer la red de prestaciones sanitarias del IPS'],
            ['tipo' => 'pnd', 'nombre' => 'PND OES 1.4.1 - Ampliar cobertura del sistema de pensiones contributivas'],
            ['tipo' => 'pnd', 'nombre' => 'PND OES 1.4.2 - Garantizar sostenibilidad financiera del IPS'],
            ['tipo' => 'pnd', 'nombre' => 'PND OES 1.4.3 - Modernizar la gestión de prestaciones económicas'],

            // ── ODS 2030 ──────────────────────────────────────────────────────
            ['tipo' => 'ods', 'nombre' => 'ODS 1 - Fin de la pobreza'],
            ['tipo' => 'ods', 'nombre' => 'ODS 2 - Hambre cero'],
            ['tipo' => 'ods', 'nombre' => 'ODS 3 - Salud y bienestar'],
            ['tipo' => 'ods', 'nombre' => 'ODS 4 - Educación de calidad'],
            ['tipo' => 'ods', 'nombre' => 'ODS 5 - Igualdad de género'],
            ['tipo' => 'ods', 'nombre' => 'ODS 6 - Agua limpia y saneamiento'],
            ['tipo' => 'ods', 'nombre' => 'ODS 7 - Energía asequible y no contaminante'],
            ['tipo' => 'ods', 'nombre' => 'ODS 8 - Trabajo decente y crecimiento económico'],
            ['tipo' => 'ods', 'nombre' => 'ODS 9 - Industria, innovación e infraestructura'],
            ['tipo' => 'ods', 'nombre' => 'ODS 10 - Reducción de las desigualdades'],
            ['tipo' => 'ods', 'nombre' => 'ODS 11 - Ciudades y comunidades sostenibles'],
            ['tipo' => 'ods', 'nombre' => 'ODS 12 - Producción y consumo responsables'],
            ['tipo' => 'ods', 'nombre' => 'ODS 13 - Acción por el clima'],
            ['tipo' => 'ods', 'nombre' => 'ODS 16 - Paz, justicia e instituciones sólidas'],
            ['tipo' => 'ods', 'nombre' => 'ODS 17 - Alianzas para lograr los objetivos'],

            // ── BSC — Perspectivas IPS ────────────────────────────────────────
            ['tipo' => 'bsc', 'nombre' => 'BSC - Perspectiva Financiera'],
            ['tipo' => 'bsc', 'nombre' => 'BSC - Perspectiva del Cliente / Asegurado'],
            ['tipo' => 'bsc', 'nombre' => 'BSC - Perspectiva de Procesos Internos'],
            ['tipo' => 'bsc', 'nombre' => 'BSC - Perspectiva de Aprendizaje y Crecimiento'],

            // ── MECIP 2015 ────────────────────────────────────────────────────
            ['tipo' => 'mecip', 'nombre' => 'MECIP - Ambiente de Control'],
            ['tipo' => 'mecip', 'nombre' => 'MECIP - Administración de Riesgos'],
            ['tipo' => 'mecip', 'nombre' => 'MECIP - Actividades de Control'],
            ['tipo' => 'mecip', 'nombre' => 'MECIP - Información y Comunicación'],
            ['tipo' => 'mecip', 'nombre' => 'MECIP - Seguimiento y Monitoreo'],
        ];

        foreach ($marcos as $marco) {
            DB::table('planificacion.marcos_referenciales')->updateOrInsert(
                ['nombre' => $marco['nombre'], 'tipo' => $marco['tipo']],
                ['activo' => true, 'created_at' => $ahora, 'updated_at' => $ahora]
            );
        }

        $this->command->info('✅ ' . count($marcos) . ' marcos referenciales sembrados.');
    }
}
