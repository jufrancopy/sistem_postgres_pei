<?php

namespace App\Services;

use App\Models\Riiss\CarteraServicio;
use App\Models\Riiss\Evaluacion;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\FormularioPregunta;
use App\Models\Riiss\GapAnalysisItem;
use Illuminate\Support\Collection;

class GapAnalysisService
{
    public function __construct(
        private CarteraMatchingService  $carteraService,
        private FormularioDinamicoService $formularioService
    ) {}

    /**
     * Ejecuta el análisis de brechas completo para una evaluación.
     */
    public function ejecutar(Evaluacion $evaluacion): array
    {
        $est              = $evaluacion->establecimiento;
        $serviciosRequeridos = $this->carteraService->serviciosRequeridos($est);
        $respuestas       = $evaluacion->respuestas->keyBy('formulario_pregunta_id');

        // Limpiar gap anterior
        $evaluacion->gapAnalysis()->delete();

        $resultados = [];
        $contadores = ['cumple' => 0, 'no_cumple' => 0, 'no_verificable' => 0, 'no_aplica' => 0, 'pendiente' => 0];

        foreach ($serviciosRequeridos as $servicio) {
            $resultado    = $this->evaluarServicio($servicio, $est, $respuestas, $evaluacion->id);
            $resultados[] = $resultado;
            $contadores[$resultado['estado']]++;
        }

        // Calcular porcentaje
        $total          = count($resultados);
        $totalEvaluables = $total - ($contadores['no_aplica'] + $contadores['pendiente']);
        $porcentaje     = $totalEvaluables > 0
            ? round(($contadores['cumple'] / $totalEvaluables) * 100, 2)
            : 0;

        $clasificacion = match(true) {
            $porcentaje >= 90 => 'CUMPLE',
            $porcentaje >= 70 => 'CUMPLE_PARCIALMENTE',
            default           => 'NO_CUMPLE',
        };

        $evaluacion->update([
            'porcentaje_cumplimiento' => $porcentaje,
            'clasificacion_resultado' => $clasificacion,
        ]);

        return [
            'evaluacion_id'    => $evaluacion->id,
            'establecimiento'  => $est->toResumenArray(),
            'resumen'          => [
                'total_servicios_evaluados' => $total,
                'cumple'                    => $contadores['cumple'],
                'no_cumple'                 => $contadores['no_cumple'],
                'no_verificable'            => $contadores['no_verificable'],
                'no_aplica'                 => $contadores['no_aplica'],
                'pendiente'                 => $contadores['pendiente'],
                'porcentaje_cumplimiento'   => $porcentaje,
                'clasificacion'             => $clasificacion,
            ],
            'detalles'          => $resultados,
            'acciones_criticas' => collect($resultados)
                ->filter(fn($r) => $r['prioridad'] >= 2 && $r['estado'] !== 'cumple')
                ->values()->toArray(),
        ];
    }

    private function evaluarServicio(
        CarteraServicio $servicio,
        Establecimiento $est,
        Collection $respuestas,
        int $evaluacionId
    ): array {
        $preguntasRelacionadas = $this->buscarPreguntasRelacionadas($servicio, $est);

        if ($preguntasRelacionadas->isEmpty()) {
            GapAnalysisItem::create([
                'evaluacion_id'           => $evaluacionId,
                'cartera_servicio_id'     => $servicio->id,
                'servicio_nombre'         => $servicio->servicio,
                'grupo_servicio'          => $servicio->grupo_servicio,
                'tipo_prestacion'         => $servicio->tipo_prestacion,
                'especialidad'            => $servicio->especialidad_1,
                'requerido_para_nivel'    => $servicio->requerido,
                'estado'                  => 'no_verificable',
                'criterio_evaluacion'     => 'No existen preguntas en el formulario que validen este servicio.',
                'preguntas_relacionadas'  => [],
                'respuestas_relacionadas' => [],
                'accion_recomendada'      => "Verificar presencialmente la disponibilidad de: {$servicio->servicio}.",
                'prioridad'               => $servicio->requerido ? 1 : 0,
            ]);

            return $this->crearResultado(
                $servicio, 'no_verificable', [], [],
                'No existen preguntas en el formulario que validen este servicio.',
                0, null, $evaluacionId
            );
        }

        $preguntaIds          = $preguntasRelacionadas->pluck('id')->toArray();
        $respuestasRelacionadas = $respuestas->only($preguntaIds);

        if ($respuestasRelacionadas->isEmpty()) {
            GapAnalysisItem::create([
                'evaluacion_id'           => $evaluacionId,
                'cartera_servicio_id'     => $servicio->id,
                'servicio_nombre'         => $servicio->servicio,
                'grupo_servicio'          => $servicio->grupo_servicio,
                'tipo_prestacion'         => $servicio->tipo_prestacion,
                'especialidad'            => $servicio->especialidad_1,
                'requerido_para_nivel'    => $servicio->requerido,
                'estado'                  => 'pendiente',
                'criterio_evaluacion'     => 'Preguntas identificadas pero sin respuestas registradas.',
                'preguntas_relacionadas'  => $preguntaIds,
                'respuestas_relacionadas' => [],
                'accion_recomendada'      => "Completar las respuestas para: {$servicio->servicio}.",
                'prioridad'               => 1,
            ]);

            return $this->crearResultado(
                $servicio, 'pendiente', $preguntaIds, [],
                'Preguntas identificadas pero sin respuestas registradas.',
                1, null, $evaluacionId
            );
        }

        $estados            = [];
        $detallesRespuestas = [];
        $hayNoCumple        = false;
        $hayNoVerificable   = false;

        foreach ($preguntasRelacionadas as $pregunta) {
            $respuesta = $respuestas->get($pregunta->id);
            if (!$respuesta) continue;

            $estadoRespuesta = $pregunta->evaluarRespuesta($respuesta->respuesta);
            $estados[]       = $estadoRespuesta;
            $detallesRespuestas[$pregunta->id] = [
                'pregunta'  => $pregunta->pregunta,
                'respuesta' => $respuesta->respuesta,
                'estado'    => $estadoRespuesta,
            ];

            if ($estadoRespuesta === 'no_cumple')      $hayNoCumple      = true;
            if ($estadoRespuesta === 'no_verificable') $hayNoVerificable = true;
        }

        if ($hayNoCumple) {
            $estado   = 'no_cumple';
            $prioridad = 2;
        } elseif ($hayNoVerificable && !in_array('cumple', $estados)) {
            $estado   = 'no_verificable';
            $prioridad = 1;
        } elseif (in_array('cumple', $estados)) {
            $estado   = 'cumple';
            $prioridad = 0;
        } else {
            $estado   = 'no_verificable';
            $prioridad = 1;
        }

        $criterio = $this->generarCriterio($servicio, $detallesRespuestas);
        $accion   = $this->generarAccion($servicio, $estado, $detallesRespuestas);

        GapAnalysisItem::create([
            'evaluacion_id'           => $evaluacionId,
            'cartera_servicio_id'     => $servicio->id,
            'servicio_nombre'         => $servicio->servicio,
            'grupo_servicio'          => $servicio->grupo_servicio,
            'tipo_prestacion'         => $servicio->tipo_prestacion,
            'especialidad'            => $servicio->especialidad_1,
            'requerido_para_nivel'    => $servicio->requerido,
            'estado'                  => $estado,
            'criterio_evaluacion'     => $criterio,
            'preguntas_relacionadas'  => $preguntaIds,
            'respuestas_relacionadas' => $detallesRespuestas,
            'accion_recomendada'      => $accion,
            'prioridad'               => $prioridad,
        ]);

        return $this->crearResultado($servicio, $estado, $preguntaIds, $detallesRespuestas, $criterio, $prioridad, $accion, $evaluacionId);
    }

    private function buscarPreguntasRelacionadas(CarteraServicio $servicio, Establecimiento $est): Collection
    {
        $seccionesAplicables = $this->formularioService->seccionesAplicables($est)->pluck('id');
        $query = FormularioPregunta::where('activa', true)
            ->whereIn('formulario_seccion_id', $seccionesAplicables);

        // Estrategia 1: Match directo por grupo
        if ($servicio->grupo_servicio) {
            $porGrupo = (clone $query)->where('servicio_cartera_grupo', $servicio->grupo_servicio)->get();
            if ($porGrupo->isNotEmpty()) return $porGrupo;
        }

        // Estrategia 2: Match por especialidad
        if ($servicio->especialidad_1) {
            $porEsp = (clone $query)->where('especialidad_relacionada', $servicio->especialidad_1)->get();
            if ($porEsp->isNotEmpty()) return $porEsp;
        }

        // Estrategia 3: Match por tags JSON
        if ($servicio->grupo_servicio) {
            $porTags = (clone $query)->whereJsonContains('tags_cartera', $servicio->grupo_servicio)->get();
            if ($porTags->isNotEmpty()) return $porTags;
        }

        // Estrategia 4: Búsqueda textual
        $palabras = $this->extraerPalabrasClave($servicio->servicio);
        if (!empty($palabras)) {
            $textual = (clone $query)->where(function ($q) use ($palabras) {
                foreach ($palabras as $palabra) {
                    $q->orWhere('pregunta', 'ILIKE', "%{$palabra}%");
                }
            })->get();
            if ($textual->isNotEmpty()) return $textual;
        }

        return collect();
    }

    private function extraerPalabrasClave(string $servicio): array
    {
        $stopWords = ['de', 'del', 'la', 'el', 'los', 'las', 'en', 'con', 'y', 'a', 'para', 'por', 'que', 'un', 'una'];
        $palabras  = explode(' ', strtolower($servicio));
        return array_values(array_filter($palabras, fn($p) => strlen($p) > 3 && !in_array($p, $stopWords)));
    }

    private function crearResultado(
        CarteraServicio $servicio,
        string $estado,
        array $preguntas,
        array $respuestas,
        string $criterio,
        int $prioridad,
        ?string $accion = null,
        int $evaluacionId = 0
    ): array {
        return [
            'cartera_servicio_id' => $servicio->id,
            'servicio'            => $servicio->servicio,
            'grupo'               => $servicio->grupo_servicio,
            'tipo_prestacion'     => $servicio->tipo_prestacion,
            'especialidad'        => $servicio->especialidad_1,
            'requerido'           => $servicio->requerido,
            'estado'              => $estado,
            'icono'               => match($estado) {
                'cumple'         => '✅',
                'no_cumple'      => '❌',
                'no_verificable' => '⚠️',
                'no_aplica'      => '⬜',
                'pendiente'      => '⏳',
                default          => '❓',
            },
            'prioridad'           => $prioridad,
            'criterio'            => $criterio,
            'preguntas_evaluadas' => $preguntas,
            'respuestas'          => $respuestas,
            'accion_recomendada'  => $accion,
        ];
    }

    private function generarCriterio(CarteraServicio $servicio, array $respuestas): string
    {
        if (empty($respuestas)) {
            return "Servicio '{$servicio->servicio}' requerido para {$servicio->tipo_prestacion} - Sin preguntas evaluadas.";
        }
        $partes = ["Servicio: {$servicio->servicio}"];
        foreach ($respuestas as $r) {
            $icono    = match($r['estado']) { 'cumple' => '✅', 'no_cumple' => '❌', default => '⚠️' };
            $partes[] = "{$icono} \"{$r['pregunta']}\" → \"{$r['respuesta']}\"";
        }
        return implode("\n", $partes);
    }

    private function generarAccion(CarteraServicio $servicio, string $estado, array $respuestas): ?string
    {
        if ($estado === 'cumple') return null;

        $noCumple = array_filter($respuestas, fn($r) => $r['estado'] === 'no_cumple');
        if (!empty($noCumple)) {
            $items = array_map(fn($r) => "- \"{$r['pregunta']}\"", $noCumple);
            return "El establecimiento NO cuenta con los requisitos para: {$servicio->servicio}.\n"
                . "Preguntas sin cumplir:\n" . implode("\n", $items)
                . "\n\nAcción: Verificar infraestructura, equipamiento y RRHH necesarios.";
        }

        if ($estado === 'no_verificable') {
            return "No se pudo verificar la disponibilidad de: {$servicio->servicio}. Se requiere evaluación presencial o documentación adicional.";
        }

        return "Pendiente de evaluación para: {$servicio->servicio}.";
    }

    /**
     * Obtener el gap analysis ya persistido sin re-ejecutar.
     */
    public function obtenerGapPersistido(Evaluacion $evaluacion): array
    {
        $items     = $evaluacion->gapAnalysis()->orderBy('prioridad', 'desc')->get();
        $agrupados = $items->groupBy('grupo_servicio')->map(fn($grupo) => [
            'grupo'          => $grupo->first()->grupo_servicio ?? 'Sin grupo',
            'total'          => $grupo->count(),
            'cumple'         => $grupo->where('estado', 'cumple')->count(),
            'no_cumple'      => $grupo->where('estado', 'no_cumple')->count(),
            'no_verificable' => $grupo->where('estado', 'no_verificable')->count(),
            'items'          => $grupo->map(fn($i) => [
                'servicio' => $i->servicio_nombre,
                'estado'   => $i->estado,
                'icono'    => $i->icono,
                'prioridad'=> $i->prioridad,
                'accion'   => $i->accion_recomendada,
            ])->values()->toArray(),
        ]);

        return [
            'evaluacion_id' => $evaluacion->id,
            'porcentaje'    => $evaluacion->porcentaje_cumplimiento,
            'clasificacion' => $evaluacion->clasificacion_resultado,
            'resumen'       => [
                'total'          => $items->count(),
                'cumple'         => $items->where('estado', 'cumple')->count(),
                'no_cumple'      => $items->where('estado', 'no_cumple')->count(),
                'no_verificable' => $items->where('estado', 'no_verificable')->count(),
                'criticos'       => $items->where('prioridad', '>=', 2)->count(),
            ],
            'por_grupo'         => $agrupados->values()->toArray(),
            'acciones_criticas' => $items->where('prioridad', '>=', 2)
                ->where('estado', '!=', 'cumple')
                ->map(fn($i) => [
                    'servicio' => $i->servicio_nombre,
                    'grupo'    => $i->grupo_servicio,
                    'accion'   => $i->accion_recomendada,
                ])->values()->toArray(),
        ];
    }
}
