<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Planificacion\RelevamientoProceso;
use App\Models\Planificacion\RelevamientoPaso;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Organigrama;
use App\Models\User;
use App\Services\RelevamientoAiAnalysisService;

class RelevamientoProcesoSeeder extends Seeder
{
    public function run(): void
    {
        $pei = PeiProfile::where('level', 'action')->first() ?: PeiProfile::first();
        $org = Organigrama::first();
        $users = User::limit(3)->pluck('id')->toArray();

        $proceso = RelevamientoProceso::create([
            'nombre' => 'Circuito de Recepción, Triage, Agendamiento e Internación de Pacientes',
            'contexto_motivo' => 'Instrucción del Consejo de Administración N° 45/2026 y Plan de Emergencia Hospitalaria para la Reducción de Tiempos de Espera.',
            'pei_profile_id' => $pei ? $pei->id : null,
            'organigrama_id' => $org ? $org->id : null,
            'tipo_relevamiento' => 'circuito_paciente',
            'estado' => 'en_relevamiento',
            'fecha_relevamiento' => now(),
            'objetivo' => 'Mapear el flujo del paciente desde su llegada hasta la internación, detectando cuellos de botella en ventanilla y triage.',
            'created_by' => $users ? $users[0] : null,
        ]);

        if ($users) {
            $proceso->responsables()->sync($users);
        }

        // Estaciones del circuito
        RelevamientoPaso::create([
            'relevamiento_proceso_id' => $proceso->id,
            'orden' => 1,
            'nombre' => 'Recepción e Identificación en Ventanilla',
            'descripcion' => 'Toma de datos personales y apertura de ticket de atención.',
            'organigrama_id' => $org ? $org->id : null,
            'rol_responsable' => 'Admisionista Ventanilla 1',
            'tiempo_atencion_min' => 4,
            'tiempo_espera_min' => 15,
            'tiempo_traslado_min' => 2,
            'herramienta_sistema' => 'SIH — Sistema Informático Hospitalario',
            'es_cuello_botella' => false,
            'criticidad' => 'media',
            'observacion_campo' => 'Formación de fila inicial a primera hora de la mañana.',
            'propuesta_mejora' => 'Habilitar totem de auto-consultas para expedición de ticket.',
        ]);

        RelevamientoPaso::create([
            'relevamiento_proceso_id' => $proceso->id,
            'orden' => 2,
            'nombre' => 'Verificación de Aportes y Derecho a Seguro',
            'descripcion' => 'Constatación de estado de cuenta y derechos vigentes.',
            'organigrama_id' => $org ? $org->id : null,
            'rol_responsable' => 'Verificador de Aportes',
            'tiempo_atencion_min' => 5,
            'tiempo_espera_min' => 10,
            'tiempo_traslado_min' => 3,
            'herramienta_sistema' => 'Sistema de Aportes Web',
            'es_cuello_botella' => false,
            'criticidad' => 'baja',
            'observacion_campo' => 'Verificación en línea rápida.',
            'propuesta_mejora' => 'Integración automática de validación de aportes en el SIH.',
        ]);

        RelevamientoPaso::create([
            'relevamiento_proceso_id' => $proceso->id,
            'orden' => 3,
            'nombre' => 'Triage y Clasificación de Riesgo Médico',
            'descripcion' => 'Evaluación de signos vitales y categorización de urgencia.',
            'organigrama_id' => $org ? $org->id : null,
            'rol_responsable' => 'Enfermero/a de Triage',
            'tiempo_atencion_min' => 7,
            'tiempo_espera_min' => 50,
            'tiempo_traslado_min' => 5,
            'herramienta_sistema' => 'Planilla Triage Manual + SIH',
            'es_cuello_botella' => true,
            'criticidad' => 'critica',
            'causa_raiz' => 'sobredemanda',
            'observacion_campo' => 'Saturación en sala de espera. Solo 1 box de triage habilitado para 120 pacientes.',
            'propuesta_mejora' => 'Aumentar a 3 boxes de triage simultáneos en horario pico (07:00 a 13:00).',
        ]);

        RelevamientoPaso::create([
            'relevamiento_proceso_id' => $proceso->id,
            'orden' => 4,
            'nombre' => 'Agendamiento de Consulta e Internación',
            'descripcion' => 'Asignación de médico tratante o cama de internación.',
            'organigrama_id' => $org ? $org->id : null,
            'rol_responsable' => 'Coordinador de Admisión',
            'tiempo_atencion_min' => 6,
            'tiempo_espera_min' => 20,
            'tiempo_traslado_min' => 4,
            'herramienta_sistema' => 'Módulo de Camas SIH',
            'es_cuello_botella' => false,
            'criticidad' => 'media',
            'causa_raiz' => 'falla_sistema',
            'observacion_campo' => 'Demora ocasional por confirmación telefónica de cama disponible en sala.',
            'propuesta_mejora' => 'Tablero digital de camas en tiempo real para admisión.',
        ]);

        RelevamientoPaso::create([
            'relevamiento_proceso_id' => $proceso->id,
            'orden' => 5,
            'nombre' => 'Traslado y Recepción en Cama de Internación',
            'descripcion' => 'Acompañamiento del paciente al bloque de internación.',
            'organigrama_id' => $org ? $org->id : null,
            'rol_responsable' => 'Camillero / Enfermería de Piso',
            'tiempo_atencion_min' => 12,
            'tiempo_espera_min' => 15,
            'tiempo_traslado_min' => 8,
            'herramienta_sistema' => 'Ficha Física de Internación',
            'es_cuello_botella' => false,
            'criticidad' => 'baja',
            'observacion_campo' => 'Traslado correcto.',
            'propuesta_mejora' => 'Digitalización del parte de pase de cama.',
        ]);

        // Generar diagnóstico de Inteligencia Artificial
        $aiService = app(RelevamientoAiAnalysisService::class);
        $proceso->refresh();
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();
    }
}
