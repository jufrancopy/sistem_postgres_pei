<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Planificacion\RelevamientoProceso;
use App\Models\Planificacion\RelevamientoPaso;
use App\Models\User;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\PeiProfile;

class RelevamientoProcesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buscar u obtener Organigrama de IPS (Gerencia de Salud / Policlínica)
        $org = Organigrama::where('dependency', 'ILIKE', '%salud%')
            ->orWhere('dependency', 'ILIKE', '%planificacion%')
            ->first();

        if (!$org) {
            $org = Organigrama::create([
                'dependency' => 'GERENCIA DE SALUD - HOSPITAL CENTRAL IPS',
                'code' => 'IPS-GS-HC',
            ]);
        }

        // 2. Buscar perfil PEI o tomar el primero disponible
        $pei = PeiProfile::first();

        // 3. Buscar usuarios responsables de la DOC / Planificación
        $users = User::take(2)->pluck('id')->toArray();
        if (empty($users)) {
            $user = User::first();
            if ($user) $users = [$user->id];
        }

        // 4. Crear Relevamiento de Proceso de Ejemplo Realista IPS Paraguay
        $proceso = RelevamientoProceso::updateOrCreate(
            ['nombre' => 'Relevamiento del Circuito de Admisión, RAC (Triage) y Consulta en Policlínica — Hospital Central IPS'],
            [
                'contexto_motivo' => 'Relevamiento técnico en el marco de la Resolución C.A. N° 045-021/2026 para la optimización de los tiempos de espera del asegurado y diagnóstico de cuellos de botella en admisión y consulta médica.',
                'pei_profile_id' => $pei ? $pei->id : null,
                'organigrama_id' => $org->id,
                'tipo_relevamiento' => 'circuito_paciente',
                'estado' => 'en_relevamiento',
                'fecha_relevamiento' => now()->format('Y-m-d'),
                'objetivo' => 'Identificar la latencia total del paciente asegurado desde la llegada a ventanilla hasta la atención médica y dispensación en farmacia.',
                'participantes_externos' => [
                    [
                        'id' => 'ext_ips_001',
                        'nombre' => 'Lic. Carmen Benítez',
                        'cargo' => 'Jefa de Admisión y Agendamiento — Hospital Central IPS',
                        'dependencia' => 'Ventanilla de Admisión Policlínica',
                        'firma_digital' => null,
                        'firmado_at' => null,
                        'observaciones_firma' => null,
                    ],
                    [
                        'id' => 'ext_ips_002',
                        'nombre' => 'Dra. Patricia Giménez',
                        'cargo' => 'Coordinadora de RAC (Triage) & Urgencias',
                        'dependencia' => 'Dirección de Medicina Preventiva IPS',
                        'firma_digital' => null,
                        'firmado_at' => null,
                        'observaciones_firma' => null,
                    ]
                ],
                'created_by' => !empty($users) ? $users[0] : null,
            ]
        );

        // Asociar responsables
        if (!empty($users)) {
            $proceso->responsables()->sync($users);
        }

        // 5. Crear Estaciones del Circuito Relevado
        $pasos = [
            [
                'orden' => 1,
                'nombre' => 'Ventanilla #1: Verificación de Derechos del Asegurado y Agendamiento',
                'descripcion' => 'Revisión en sistema SIH del aporte del asegurado IPS y entrega de número de turno presencial.',
                'area_dependencia_custom' => 'Ventanilla de Admisión Central - Bloque A',
                'rol_responsable' => 'Admisionista / Funcionario de Ventanilla',
                'tiempo_atencion_min' => 4,
                'tiempo_espera_min' => 35,
                'tiempo_traslado_min' => 2,
                'herramienta_sistema' => 'Sistema SIH IPS (Módulo Admisión)',
                'es_cuello_botella' => true,
                'criticidad' => 'critica',
                'causa_raiz' => 'sobredemanda',
                'observacion_campo' => 'Alta congestión de asegurados en horario matutino de 06:00 a 08:30 hs. Solo 3 de 6 ventanillas habilitadas.',
                'propuesta_mejora' => 'Habilitar pre-agendamiento digital mediante la App Mi IPS y tótem autoservicio para validación automática de aportes.',
            ],
            [
                'orden' => 2,
                'nombre' => 'RAC / Triage: Evaluación de Signos Vitales y Clasificación de Riesgo',
                'descripcion' => 'Evaluación de síntomas por personal de enfermería y asignación de prioridad (Rojo, Amarillo, Verde).',
                'area_dependencia_custom' => 'Sala RAC (Triage) Policlínica',
                'rol_responsable' => 'Licenciada/o en Enfermería de Triage',
                'tiempo_atencion_min' => 5,
                'tiempo_espera_min' => 12,
                'tiempo_traslado_min' => 3,
                'herramienta_sistema' => 'Ficha RAC Digital SIH',
                'es_cuello_botella' => false,
                'criticidad' => 'media',
                'causa_raiz' => 'falta_personal',
                'observacion_campo' => 'El flujo de toma de presión y temperatura funciona ágilmente con 2 puestos activos.',
                'propuesta_mejora' => 'Integrar monitores de signos vitales bluetooth sincronizados directamente con la ficha electrónica.',
            ],
            [
                'orden' => 3,
                'nombre' => 'Sala de Espera y Llamado por Pantalla Turnero',
                'descripcion' => 'Espera del paciente asegurado en la sala principal de especialidades médicas.',
                'area_dependencia_custom' => 'Sala de Espera Policlínica Adultos',
                'rol_responsable' => 'Auxiliar de Enfermería / Turnero',
                'tiempo_atencion_min' => 2,
                'tiempo_espera_min' => 48,
                'tiempo_traslado_min' => 2,
                'herramienta_sistema' => 'Pantalla LED Turnero Digifort',
                'es_cuello_botella' => true,
                'criticidad' => 'alta',
                'causa_raiz' => 'sobredemanda',
                'observacion_campo' => 'Demora acumulada por retraso en el inicio de consultas médicas de algunos especialistas.',
                'propuesta_mejora' => 'Notificación SMS/WhatsApp al paciente indicando posición en fila para evitar hacinamiento en sala.',
            ],
            [
                'orden' => 4,
                'nombre' => 'Consultorio Médico: Consulta, Diagnóstico y Prescripción Electrónica',
                'descripcion' => 'Atención médica especializada, registro en ficha clínica electrónica y emisión de receta SIH.',
                'area_dependencia_custom' => 'Consultorio N° 14 - Clínica Médica',
                'rol_responsable' => 'Médico Especialista en Clínica Médica',
                'tiempo_atencion_min' => 15,
                'tiempo_espera_min' => 5,
                'tiempo_traslado_min' => 3,
                'herramienta_sistema' => 'Ficha Electrónica SIH Médicos',
                'es_cuello_botella' => false,
                'criticidad' => 'baja',
                'causa_raiz' => null,
                'observacion_campo' => 'Consulta exhaustiva y expedición rápida de recetas medicamentosas en el sistema.',
                'propuesta_mejora' => 'Estandarizar plantillas de indicación médica para agilizar la carga del historial del paciente.',
            ],
            [
                'orden' => 5,
                'nombre' => 'Ventanilla de Farmacia: Retiro de Medicamentos Recetados',
                'descripcion' => 'Dispensación de medicamentos del Vademécum IPS y firma de ticket de entrega.',
                'area_dependencia_custom' => 'Farmacia Externa Policlínica',
                'rol_responsable' => 'Químico Farmacéutico / Auxiliar de Farmacia',
                'tiempo_atencion_min' => 4,
                'tiempo_espera_min' => 22,
                'tiempo_traslado_min' => 0,
                'herramienta_sistema' => 'SIH Farmacia & Inventarios',
                'es_cuello_botella' => false,
                'criticidad' => 'media',
                'causa_raiz' => 'espacio_fisico',
                'observacion_campo' => 'Ventanilla de entrega rápida saturada en picos de egreso de consultorios.',
                'propuesta_mejora' => 'Implementar código QR en la receta electrónica para lectura e identificación inmediata en farmacia.',
            ],
        ];

        // Limpiar pasos previos del seeder y recrear
        RelevamientoPaso::where('relevamiento_proceso_id', $proceso->id)->delete();

        foreach ($pasos as $pasoData) {
            $pasoData['relevamiento_proceso_id'] = $proceso->id;
            $pasoData['organigrama_id'] = $org->id;
            RelevamientoPaso::create($pasoData);
        }

        // 6. Generar Diagnóstico IA automático
        $aiService = app(\App\Services\RelevamientoAiAnalysisService::class);
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        if (isset($this->command)) {
            $this->command->info('✅ Seeder IPS Paraguay creado con éxito: ' . $proceso->nombre);
        }
    }
}
