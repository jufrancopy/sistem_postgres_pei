<?php

namespace App\Services;

use App\Models\Planificacion\RelevamientoProceso;

class RelevamientoAiAnalysisService
{
    public function generarDiagnostico(RelevamientoProceso $proceso): string
    {
        $pasos = $proceso->pasos;
        $totalPasos = $pasos->count();
        $cuellos = $pasos->where('es_cuello_botella', true);
        $conteoCuellos = $cuellos->count();
        
        $leadTime = $proceso->lead_time_total;
        $tiempoAtencion = $proceso->tiempo_atencion_total;
        $tiempoEspera = $proceso->tiempo_espera_total;
        $eficiencia = $proceso->eficiencia;

        $reporte = "### 🤖 Informe de Diagnóstico Inteligente de Flujo (IA Planificación & Calidad)\n\n";
        
        $reporte .= "#### 1. Evaluación Cuantitativa del Circuito\n";
        $reporte .= "- **Lead Time Total del Paciente:** {$leadTime} minutos (" . round($leadTime / 60, 1) . " horas).\n";
        $reporte .= "- **Tiempo de Atención Efectiva (Valor Agregado):** {$tiempoAtencion} min (" . round(($tiempoAtencion / ($leadTime ?: 1)) * 100, 1) . "%).\n";
        $reporte .= "- **Tiempo de Espera en Cola / Latencia (Tiempo Muerto):** {$tiempoEspera} min (" . round(($tiempoEspera / ($leadTime ?: 1)) * 100, 1) . "%).\n";
        $reporte .= "- **Índice de Eficiencia Operativa:** **{$eficiencia}%**.\n";
        $reporte .= "- **Puntos de Fricción / Cuellos de Botella Detectados:** {$conteoCuellos} de {$totalPasos} estaciones.\n\n";

        $reporte .= "#### 2. Hallazgos Clave y Análisis de Causa Raíz\n";
        if ($conteoCuellos > 0) {
            foreach ($cuellos as $paso) {
                $causaLabel = match($paso->causa_raiz) {
                    'sobredemanda' => 'Sobredemanda de Pacientes',
                    'falta_personal' => 'Insuficiencia de Personal en Ventanilla',
                    'falla_sistema' => 'Caída / Lentitud de Sistema Informático',
                    'burocracia_papel' => 'Exceso de Trámites Manuales en Papel',
                    'espacio_fisico' => 'Restricción de Infraestructura / Espacio Físico',
                    default => 'Fricción Operativa No Clasificada',
                };
                $reporte .= " - ⚠️ **Estación #{$paso->orden} ({$paso->nombre})**: Registra un tiempo de espera de **{$paso->tiempo_espera_min} min** (" . ($paso->organigrama ? $paso->organigrama->nombre : 'Área no asignada') . "). **Causa principal:** {$causaLabel}.\n";
                if ($paso->observacion_campo) {
                    $reporte .= "   *Observación:* {$paso->observacion_campo}\n";
                }
            }
        } else {
            $reporte .= " - ✅ No se han registrado cuellos de botella estructurales críticos en las estaciones evaluadas.\n";
        }

        $reporte .= "\n#### 3. Recomendaciones Estratégicas (Matriz de Mejora Continua)\n";
        if ($eficiencia < 50) {
            $reporte .= " - 🚨 **Acción Prioritaria Urgente**: El tiempo muerto/espera supera el 50% del circuito. Se recomienda digitalizar la pre-admisión o implementar agendamiento remoto previo.\n";
        }
        
        $reporte .= " - ⚡ **Quick Win (Solución Rápida)**: Reasignar temporalmente personal de ventanilla en horarios pico de mayor congestión detectados en la estación de triage/admisión.\n";
        $reporte .= " - 🎯 **Alineación con el PEI**: Vincular las propuestas de mejora de las estaciones críticas con las metas institucionales de la Acción PEI (" . ($proceso->peiProfile ? $proceso->peiProfile->name : 'Acción PEI no asociada') . ").\n";
        $reporte .= " - 📋 **Insumo para la Dirección de Organización y Calidad**: Se recomienda a la DOC estandarizar el protocolo operativo en las estaciones con mayor variabilidad de tiempo de atención.";

        return $reporte;
    }
}
