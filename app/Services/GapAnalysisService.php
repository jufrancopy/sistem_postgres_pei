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
        private CarteraMatchingService   $carteraService,
        private FormularioDinamicoService $formularioService
    ) {}

    /**
     * Ejecuta el análisis completo en dos dimensiones.
     */
    public function ejecutar(Evaluacion $evaluacion): array
    {
        $evaluacion->gapAnalysis()->delete();

        $dimCartera      = $this->ejecutarDimensionCartera($evaluacion);
        $dimHabilitacion = $this->ejecutarDimensionHabilitacion($evaluacion);

        $clasificacionFinal = $this->clasificacionFinal(
            $dimCartera['clasificacion'],
            $dimHabilitacion['clasificacion']
        );

        $evaluacion->update([
            'porcentaje_cumplimiento'  => $dimCartera['porcentaje'],
            'clasificacion_resultado'  => $dimCartera['clasificacion'],
            'pct_habilitacion'         => $dimHabilitacion['porcentaje'],
            'clasificacion_habilitacion' => $dimHabilitacion['clasificacion'],
        ]);

        return [
            'evaluacion_id'       => $evaluacion->id,
            'establecimiento'     => $evaluacion->establecimiento->toResumenArray(),
            'clasificacion_final' => $clasificacionFinal,
            'cartera'             => $dimCartera,
            'habilitacion'        => $dimHabilitacion,
        ];
    }

    // ─── DIMENSIÓN A: CARTERA DE SERVICIOS ───────────────────────

    private function ejecutarDimensionCartera(Evaluacion $evaluacion): array
    {
        $est             = $evaluacion->establecimiento;
        $servicios       = $this->carteraService->serviciosRequeridos($est);
        $respuestas = $evaluacion->respuestas()->get()->mapWithKeys(fn($r) => [(int)$r->formulario_pregunta_id => $r]);
        $contadores      = ['cumple' => 0, 'no_cumple' => 0, 'no_verificable' => 0, 'no_aplica' => 0, 'pendiente' => 0];
        $resultados      = [];

        foreach ($servicios as $servicio) {
            $resultado    = $this->evaluarServicio($servicio, $est, $respuestas, $evaluacion->id);
            $resultados[] = $resultado;
            $contadores[$resultado['estado']]++;
        }

        $total           = count($resultados);
        $totalEvaluables = $total - ($contadores['no_aplica'] + $contadores['pendiente']);
        $porcentaje      = $totalEvaluables > 0
            ? round(($contadores['cumple'] / $totalEvaluables) * 100, 2)
            : 0;

        $clasificacion = $this->clasificar($porcentaje);

        return [
            'porcentaje'        => $porcentaje,
            'clasificacion'     => $clasificacion,
            'resumen'           => array_merge($contadores, [
                'total'    => $total,
                'criticos' => collect($resultados)->where('prioridad', '>=', 2)->count(),
            ]),
            'detalles'          => $resultados,
            'acciones_criticas' => collect($resultados)
                ->filter(fn($r) => $r['prioridad'] >= 2 && $r['estado'] !== 'cumple')
                ->values()->toArray(),
        ];
    }

    // ─── DIMENSIÓN B: CONDICIONES HABILITANTES ───────────────────

    private function ejecutarDimensionHabilitacion(Evaluacion $evaluacion): array
    {
        $est        = $evaluacion->establecimiento;
        $respuestas = $evaluacion->respuestas()->get()->mapWithKeys(fn($r) => [(int)$r->formulario_pregunta_id => $r]);
        $contadores = ['cumple' => 0, 'no_cumple' => 0, 'no_verificable' => 0, 'no_aplica' => 0, 'pendiente' => 0];
        $resultados = [];

        $preguntas = FormularioPregunta::where('dimension', 'condiciones_habilitantes')
            ->where('activa', true)
            ->whereIn('formulario_seccion_id',
                $this->formularioService->seccionesAplicables($est)->pluck('id')
            )
            ->get();

        foreach ($preguntas as $pregunta) {
            $respuesta = $respuestas->get($pregunta->id);

            if (!$respuesta) {
                $estado    = 'pendiente';
                $prioridad = 1;
                $accion    = "Completar respuesta para: {$pregunta->pregunta}";
            } else {
                $estado    = $pregunta->evaluarRespuesta($respuesta->respuesta);
                $prioridad = $estado === 'no_cumple' ? 2 : ($estado === 'no_verificable' ? 1 : 0);
                $accion    = $estado !== 'cumple' ? "Verificar condición: {$pregunta->pregunta}" : null;
            }

            $contadores[$estado]++;

            GapAnalysisItem::create([
                'evaluacion_id'           => $evaluacion->id,
                'cartera_servicio_id'     => null,
                'servicio_nombre'         => $pregunta->pregunta,
                'grupo_servicio'          => $pregunta->seccion->seccion ?? 'General',
                'dimension'               => 'condiciones_habilitantes',
                'tipo_prestacion'         => 'habilitacion',
                'especialidad'            => null,
                'requerido_para_nivel'    => true,
                'estado'                  => $estado,
                'criterio_evaluacion'     => $respuesta?->respuesta ?? 'Sin respuesta',
                'preguntas_relacionadas'  => [$pregunta->id],
                'respuestas_relacionadas' => $respuesta ? [[
                    'pregunta'  => $pregunta->pregunta,
                    'respuesta' => $respuesta->respuesta,
                    'estado'    => $estado,
                ]] : [],
                'accion_recomendada'      => $accion,
                'prioridad'               => $prioridad,
            ]);

            $resultados[] = [
                'pregunta'  => $pregunta->pregunta,
                'seccion'   => $pregunta->seccion->seccion ?? 'General',
                'estado'    => $estado,
                'prioridad' => $prioridad,
                'icono'     => $this->icono($estado),
                'accion'    => $accion,
            ];
        }

        $total           = count($resultados);
        $totalEvaluables = $total - ($contadores['no_aplica'] + $contadores['pendiente']);
        $porcentaje      = $totalEvaluables > 0
            ? round(($contadores['cumple'] / $totalEvaluables) * 100, 2)
            : 0;

        return [
            'porcentaje'    => $porcentaje,
            'clasificacion' => $this->clasificar($porcentaje),
            'resumen'       => array_merge($contadores, ['total' => $total]),
            'detalles'      => $resultados,
        ];
    }

    // ─── HELPERS ─────────────────────────────────────────────────

    private function clasificar(float $pct): string
    {
        return match(true) {
            $pct >= 90 => 'CUMPLE',
            $pct >= 70 => 'CUMPLE_PARCIALMENTE',
            default    => 'NO_CUMPLE',
        };
    }

    private function clasificacionFinal(string $cartera, string $habilitacion): string
    {
        if ($cartera === 'CUMPLE' && $habilitacion === 'CUMPLE') return 'APTO_HABILITACION';
        if ($cartera === 'NO_CUMPLE') return 'NO_APTO';
        return 'OBSERVADO';
    }

    private function icono(string $estado): string
    {
        return match($estado) {
            'cumple'         => '✅',
            'no_cumple'      => '❌',
            'no_verificable' => '⚠️',
            'no_aplica'      => '⬜',
            'pendiente'      => '⏳',
            default          => '❓',
        };
    }

    // ─── EVALUACIÓN DE SERVICIO (dim cartera) ────────────────────

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
                'dimension'               => 'cartera_servicios',
                'tipo_prestacion'         => $servicio->tipo_prestacion,
                'especialidad'            => $servicio->especialidad_1,
                'requerido_para_nivel'    => $servicio->requerido,
                'estado'                  => 'no_verificable',
                'criterio_evaluacion'     => 'No existen preguntas en el formulario que validen este servicio.',
                'preguntas_relacionadas'  => [],
                'respuestas_relacionadas' => [],
                'accion_recomendada'      => "Verificar presencialmente: {$servicio->servicio}.",
                'prioridad'               => $servicio->requerido ? 1 : 0,
            ]);

            return $this->crearResultado($servicio, 'no_verificable', [], [], 'Sin preguntas de validación.', 0);
        }

        $preguntaIds            = $preguntasRelacionadas->pluck('id')->map(fn($id) => (int)$id)->toArray();
        $respuestasRelacionadas = $respuestas->only($preguntaIds);

        if ($respuestasRelacionadas->isEmpty()) {
            \Log::debug('GAP_PENDIENTE', [
                'servicio'     => $servicio->servicio,
                'preguntaIds'  => $preguntaIds,
                'respKeys'     => $respuestas->keys()->take(5)->toArray(),
            ]);
            GapAnalysisItem::create([
                'evaluacion_id'           => $evaluacionId,
                'cartera_servicio_id'     => $servicio->id,
                'servicio_nombre'         => $servicio->servicio,
                'grupo_servicio'          => $servicio->grupo_servicio,
                'dimension'               => 'cartera_servicios',
                'tipo_prestacion'         => $servicio->tipo_prestacion,
                'especialidad'            => $servicio->especialidad_1,
                'requerido_para_nivel'    => $servicio->requerido,
                'estado'                  => 'pendiente',
                'criterio_evaluacion'     => 'Preguntas identificadas pero sin respuestas.',
                'preguntas_relacionadas'  => $preguntaIds,
                'respuestas_relacionadas' => [],
                'accion_recomendada'      => "Completar respuestas para: {$servicio->servicio}.",
                'prioridad'               => 1,
            ]);

            return $this->crearResultado($servicio, 'pendiente', $preguntaIds, [], 'Sin respuestas.', 1);
        }

        $estados = [];
        $detalles = [];
        $hayNoCumple = false;
        $hayNoVerificable = false;

        foreach ($preguntasRelacionadas as $pregunta) {
            $respuesta = $respuestas->get($pregunta->id);
            if (!$respuesta) continue;

            $estadoResp = $pregunta->evaluarRespuesta($respuesta->respuesta);
            $estados[]  = $estadoResp;
            $detalles[$pregunta->id] = [
                'pregunta'  => $pregunta->pregunta,
                'respuesta' => $respuesta->respuesta,
                'estado'    => $estadoResp,
            ];

            if ($estadoResp === 'no_cumple')      $hayNoCumple      = true;
            if ($estadoResp === 'no_verificable') $hayNoVerificable = true;
        }

        $estado = match(true) {
            $hayNoCumple                                       => 'no_cumple',
            $hayNoVerificable && !in_array('cumple', $estados) => 'no_verificable',
            in_array('cumple', $estados)                       => 'cumple',
            default                                            => 'no_verificable',
        };

        $prioridad = match($estado) {
            'no_cumple'      => 2,
            'no_verificable' => 1,
            default          => 0,
        };

        $criterio = $this->generarCriterio($servicio, $detalles);
        $accion   = $this->generarAccion($servicio, $estado, $detalles);

        GapAnalysisItem::create([
            'evaluacion_id'           => $evaluacionId,
            'cartera_servicio_id'     => $servicio->id,
            'servicio_nombre'         => $servicio->servicio,
            'grupo_servicio'          => $servicio->grupo_servicio,
            'dimension'               => 'cartera_servicios',
            'tipo_prestacion'         => $servicio->tipo_prestacion,
            'especialidad'            => $servicio->especialidad_1,
            'requerido_para_nivel'    => $servicio->requerido,
            'estado'                  => $estado,
            'criterio_evaluacion'     => $criterio,
            'preguntas_relacionadas'  => $preguntaIds,
            'respuestas_relacionadas' => $detalles,
            'accion_recomendada'      => $accion,
            'prioridad'               => $prioridad,
        ]);

        return $this->crearResultado($servicio, $estado, $preguntaIds, $detalles, $criterio, $prioridad, $accion);
    }

    private function buscarPreguntasRelacionadas(CarteraServicio $servicio, Establecimiento $est): Collection
    {
        $seccionesAplicables = $this->formularioService->seccionesAplicables($est)->pluck('id');
        $query = FormularioPregunta::where('activa', true)
            ->where('dimension', 'cartera_servicios')
            ->whereIn('formulario_seccion_id', $seccionesAplicables);

        if ($servicio->grupo_servicio) {
            $porGrupo = (clone $query)->where('servicio_cartera_grupo', $servicio->grupo_servicio)->get();
            if ($porGrupo->isNotEmpty()) return $porGrupo;
        }

        if ($servicio->especialidad_1) {
            $porEsp = (clone $query)->where('especialidad_relacionada', $servicio->especialidad_1)->get();
            if ($porEsp->isNotEmpty()) return $porEsp;
        }

        if ($servicio->grupo_servicio) {
            $porTags = (clone $query)->whereJsonContains('tags_cartera', $servicio->grupo_servicio)->get();
            if ($porTags->isNotEmpty()) return $porTags;
        }

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
        ?string $accion = null
    ): array {
        return [
            'cartera_servicio_id' => $servicio->id,
            'servicio'            => $servicio->servicio,
            'grupo'               => $servicio->grupo_servicio,
            'tipo_prestacion'     => $servicio->tipo_prestacion,
            'especialidad'        => $servicio->especialidad_1,
            'requerido'           => $servicio->requerido,
            'estado'              => $estado,
            'icono'               => $this->icono($estado),
            'prioridad'           => $prioridad,
            'criterio'            => $criterio,
            'preguntas_evaluadas' => $preguntas,
            'respuestas'          => $respuestas,
            'accion_recomendada'  => $accion,
        ];
    }

    private function generarCriterio(CarteraServicio $servicio, array $respuestas): string
    {
        if (empty($respuestas)) return "Servicio '{$servicio->servicio}' sin preguntas evaluadas.";
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
                . implode("\n", $items)
                . "\nAcción: Verificar infraestructura, equipamiento y RRHH necesarios.";
        }

        return $estado === 'no_verificable'
            ? "No se pudo verificar: {$servicio->servicio}. Se requiere evaluación presencial."
            : "Pendiente de evaluación para: {$servicio->servicio}.";
    }

    // ─── OBTENER GAP PERSISTIDO ──────────────────────────────────

    public function obtenerGapPersistido(Evaluacion $evaluacion): array
    {
        $cartera      = $evaluacion->gapAnalysis()->where('dimension', 'cartera_servicios')->orderBy('prioridad', 'desc')->get();
        $habilitacion = $evaluacion->gapAnalysis()->where('dimension', 'condiciones_habilitantes')->orderBy('prioridad', 'desc')->get();

        return [
            'evaluacion_id'       => $evaluacion->id,
            'clasificacion_final' => $this->clasificacionFinal(
                $evaluacion->clasificacion_resultado ?? 'NO_CUMPLE',
                $evaluacion->clasificacion_habilitacion ?? 'NO_CUMPLE'
            ),
            'cartera' => [
                'porcentaje'    => $evaluacion->porcentaje_cumplimiento,
                'clasificacion' => $evaluacion->clasificacion_resultado,
                'resumen'       => $this->resumenItems($cartera),
                'por_grupo'     => $this->agrupar($cartera),
                'acciones_criticas' => $this->accionesCriticas($cartera),
            ],
            'habilitacion' => [
                'porcentaje'    => $evaluacion->pct_habilitacion,
                'clasificacion' => $evaluacion->clasificacion_habilitacion,
                'resumen'       => $this->resumenItems($habilitacion),
                'por_grupo'     => $this->agrupar($habilitacion),
                'acciones_criticas' => $this->accionesCriticas($habilitacion),
            ],
        ];
    }

    private function resumenItems(Collection $items): array
    {
        return [
            'total'          => $items->count(),
            'cumple'         => $items->where('estado', 'cumple')->count(),
            'no_cumple'      => $items->where('estado', 'no_cumple')->count(),
            'no_verificable' => $items->where('estado', 'no_verificable')->count(),
            'pendiente'      => $items->where('estado', 'pendiente')->count(),
            'criticos'       => $items->where('prioridad', '>=', 2)->count(),
        ];
    }

    private function agrupar(Collection $items): array
    {
        return $items->groupBy('grupo_servicio')->map(fn($grupo) => [
            'grupo'          => $grupo->first()->grupo_servicio ?? 'General',
            'total'          => $grupo->count(),
            'cumple'         => $grupo->where('estado', 'cumple')->count(),
            'no_cumple'      => $grupo->where('estado', 'no_cumple')->count(),
            'no_verificable' => $grupo->where('estado', 'no_verificable')->count(),
            'items'          => $grupo->map(fn($i) => [
                'servicio'  => $i->servicio_nombre,
                'estado'    => $i->estado,
                'icono'     => $i->icono,
                'prioridad' => $i->prioridad,
                'accion'    => $i->accion_recomendada,
            ])->values()->toArray(),
        ])->values()->toArray();
    }

    private function accionesCriticas(Collection $items): array
    {
        return $items->where('prioridad', '>=', 2)
            ->where('estado', '!=', 'cumple')
            ->map(fn($i) => [
                'servicio' => $i->servicio_nombre,
                'grupo'    => $i->grupo_servicio,
                'accion'   => $i->accion_recomendada,
            ])->values()->toArray();
    }
}
