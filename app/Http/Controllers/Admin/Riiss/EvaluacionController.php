<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\Evaluacion;
use App\Models\User;
use App\Services\CarteraMatchingService;
use App\Services\FormularioDinamicoService;
use App\Services\GapAnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluacionController extends Controller
{
    public function __construct(
        private FormularioDinamicoService $formularioService,
        private CarteraMatchingService    $carteraService,
        private GapAnalysisService        $gapService
    ) {}

    /**
     * GET /riiss/evaluaciones/usuarios
     * Búsqueda de usuarios para Select2.
     */
    public function buscarUsuarios(Request $request): JsonResponse
    {
        $q = $request->get('q', '');
        $roleFilter = $request->get('role');

        $users = User::select('id', 'name', 'email')
            ->when($roleFilter, fn($query) => $query->whereHas('roles', fn($query) => $query->where('name', $roleFilter)))
            ->when($q, fn($query) => $query->where('name', 'ILIKE', "%{$q}%")
                ->orWhere('email', 'ILIKE', "%{$q}%"))
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(fn($u) => [
                'id'   => $u->id,
                'text' => $u->name,
                'email'=> $u->email,
            ]);

        return response()->json(['results' => $users]);
    }

    /**
     * GET /riiss/evaluaciones/establecimientos
     * Búsqueda de establecimientos para Select2.
     */
    public function buscarEstablecimientos(Request $request): JsonResponse
    {
        $q = $request->get('q', '');

        $establecimientos = Establecimiento::select('id_establecimiento', 'nombre_oficial', 'tipologia_clasificacion')
            ->when($q, fn($query) =>
                $query->where('nombre_oficial', 'ILIKE', "%{$q}%")
                      ->orWhere('id_establecimiento', 'ILIKE', "%{$q}%")
            )
            ->orderBy('nombre_oficial')
            ->limit(30)
            ->get()
            ->map(fn($e) => [
                'id'   => $e->id_establecimiento,
                'text' => $e->nombre_oficial . ' (' . ($e->tipologia_clasificacion ?? 'N/A') . ')',
            ]);

        return response()->json(['results' => $establecimientos]);
    }

    /**
     * GET /riiss/dashboard
     */
    public function dashboard()
    {
        return view('admin.riiss.dashboard');
    }

    /**
     * GET /riiss/dashboard/datos
     */
    public function dashboardDatos(): JsonResponse
    {
        $evaluaciones = Evaluacion::with('establecimiento')
            ->whereIn('estado', ['borrador', 'en_progreso', 'completada'])
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($ev) {
                $respondidas = $ev->respuestas()->count();
                $totalPreguntas = 0;
                try {
                    $totalPreguntas = (new \App\Services\FormularioDinamicoService())
                        ->seccionesAplicables($ev->establecimiento)
                        ->sum(fn($s) => $s->preguntas->where('activa', true)->count());
                } catch (\Exception $e) {}

                $pct = $totalPreguntas > 0 ? round(($respondidas / $totalPreguntas) * 100, 1) : 0;

                return [
                    'id'                      => $ev->id,
                    'id_establecimiento'      => $ev->id_establecimiento,
                    'establecimiento'         => $ev->establecimiento->nombre_oficial ?? '—',
                    'tipologia'               => $ev->establecimiento->tipologia_clasificacion ?? '—',
                    'complejidad'             => $ev->establecimiento->complejidad ?? '—',
                    'complejidad_color'       => $ev->establecimiento->complejidad_color ?? '#6b7280',
                    'evaluador'               => $ev->evaluador_nombre ?? '—',
                    'fecha'                   => $ev->fecha_evaluacion?->format('d/m/Y'),
                    'estado'                  => $ev->estado,
                    'respondidas'             => $respondidas,
                    'total_preguntas'         => $totalPreguntas,
                    'progreso'                => $pct,
                    'clasificacion'           => $ev->clasificacion_resultado,
                    'porcentaje_cumplimiento' => $ev->porcentaje_cumplimiento,
                    'updated_at'              => $ev->updated_at?->diffForHumans(),
                ];
            });

        return response()->json([
            'ok'          => true,
            'resumen'     => [
                'total'       => $evaluaciones->count(),
                'en_progreso' => $evaluaciones->where('estado', 'en_progreso')->count(),
                'completadas' => $evaluaciones->where('estado', 'completada')->count(),
                'borradores'  => $evaluaciones->where('estado', 'borrador')->count(),
                'promedio_pct'=> $evaluaciones->count() ? round($evaluaciones->avg('progreso'), 1) : 0,
            ],
            'evaluaciones'=> $evaluaciones->values(),
            'timestamp'   => now()->format('H:i:s'),
        ]);
    }

    /**
     * GET /riiss/evaluaciones
     * Vista principal de evaluaciones.
     */
    public function index()
    {
        $query = Evaluacion::with('establecimiento')->orderByDesc('created_at');

        $evaluaciones = $query->paginate(20);

        return view('admin.riiss.evaluaciones.index', compact('evaluaciones'));
    }

    /**
     * GET /riiss/evaluaciones/formulario/{id_establecimiento}
     * Genera el formulario dinámico para un establecimiento.
     */
    public function formulario(string $idEstablecimiento): JsonResponse
    {
        $est = Establecimiento::where('id_establecimiento', $idEstablecimiento)->firstOrFail();

        return response()->json([
            'ok'   => true,
            'data' => $this->formularioService->generarFormulario($est),
        ]);
    }

    /**
     * GET /riiss/evaluaciones/requisitos/{id_establecimiento}
     */
    public function requisitos(string $idEstablecimiento): JsonResponse
    {
        $est = Establecimiento::where('id_establecimiento', $idEstablecimiento)->firstOrFail();

        return response()->json([
            'ok'   => true,
            'data' => $this->carteraService->resumenRequisitos($est),
        ]);
    }

    /**
     * GET /riiss/evaluaciones/nueva/{id_establecimiento}
     * Vista del formulario de evaluación.
     */
    public function nueva(string $idEstablecimiento)
    {
        $user = auth()->user();
        $isAnalista = $user && $user->hasAnyRole(['Analista - RIISS', 'Analista RIISS']) && !$user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS']);
        if ($isAnalista) {
            $tieneAsignacion = \App\Models\Riiss\Asignacion::where('id_establecimiento', $idEstablecimiento)
                ->where('evaluador_id', $user->id)
                ->whereIn('estado', ['pendiente', 'en_progreso', 'completada'])
                ->exists();
            if (!$tieneAsignacion) {
                return redirect()->route('riiss.mis-asignaciones')
                    ->with('warning', 'Solo puedes cargar formularios de establecimientos que te hayan sido asignados.');
            }
        }

        $est = Establecimiento::where('id_establecimiento', $idEstablecimiento)->firstOrFail();
        return view('admin.riiss.evaluaciones.nueva', compact('est'));
    }

    /**
     * POST /riiss/evaluaciones
     * Crear una nueva evaluación (borrador) o recuperar la activa.
     */
    public function crear(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_establecimiento'              => 'required|string|exists:establecimientos,id_establecimiento',
            'fecha_evaluacion'                => 'required|date',
            'pei_profile_id'                  => 'nullable|string',
            'evaluadores'                     => 'nullable|array',
            'evaluadores.*.id'                => 'required|integer',
            'evaluadores.*.text'              => 'required|string',
            'evaluadores.*.email'             => 'nullable|string',
            'evaluador_telefono'              => 'nullable|string|max:50',
            'observaciones_generales'         => 'nullable|string',
            'aspectos_positivos'              => 'nullable|string',
            'latitud'                         => 'nullable|numeric',
            'longitud'                        => 'nullable|numeric',
            'metadata'                        => 'nullable|array',
        ]);

        $user = auth()->user();
        $isAnalista = $user && $user->hasAnyRole(['Analista - RIISS', 'Analista RIISS']) && !$user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS']);
        if ($isAnalista) {
            $tieneAsignacion = \App\Models\Riiss\Asignacion::where('id_establecimiento', $validated['id_establecimiento'])
                ->where('evaluador_id', $user->id)
                ->whereIn('estado', ['pendiente', 'en_progreso', 'completada'])
                ->exists();
            if (!$tieneAsignacion) {
                return response()->json(['ok' => false, 'message' => 'Solo puedes iniciar o cargar evaluaciones de establecimientos asignados a tu usuario.'], 403);
            }
        }

        // 1. Buscar si ya existe una evaluación activa (borrador o en progreso) para este establecimiento
        $evaluacion = Evaluacion::where('id_establecimiento', $validated['id_establecimiento'])
            ->whereIn('estado', ['borrador', 'en_progreso'])
            ->latest()
            ->first();

        if ($evaluacion) {
            return response()->json([
                'ok'      => true,
                'data'    => $evaluacion,
                'message' => 'Continuando con la evaluación existente',
            ]);
        }

        // 2. Si no hay una activa, crearla
        if (!empty($validated['evaluadores'])) {
            $validated['evaluador_nombre'] = collect($validated['evaluadores'])
                ->pluck('text')->implode(', ');
        }

        $config = \App\Models\HomeConfiguration::first();
        $validated['pei_profile_id'] = $request->pei_profile_id ?: ($config?->pei_profile_id ?: '766eb883-fdd0-4723-8f75-cf689aa8f0fa');

        $evaluacion = Evaluacion::create($validated);

        return response()->json([
            'ok'      => true,
            'data'    => $evaluacion,
            'message' => 'Evaluación creada exitosamente',
        ], 201);
    }

    /**
     * PATCH /riiss/evaluaciones/{id}/datos-visita
     * Actualiza evaluadores, fecha y ubicación en background.
     */
    public function actualizarDatosVisita(Request $request, Evaluacion $evaluacion): JsonResponse
    {
        $validated = $request->validate([
            'fecha_evaluacion'        => 'nullable|date',
            'evaluadores'             => 'nullable|array',
            'evaluador_telefono'      => 'nullable|string|max:50',
            'metadata'                => 'nullable|array',
            'observaciones_generales' => 'nullable|string',
            'aspectos_positivos'      => 'nullable|string',
        ]);

        if (!empty($validated['evaluadores'])) {
            $validated['evaluador_nombre'] = collect($validated['evaluadores'])
                ->pluck('text')->implode(', ');
        }

        $evaluacion->update($validated);

        return response()->json(['ok' => true]);
    }

    /**
     * PUT /riiss/evaluaciones/{id}/respuestas
     * Guardar respuestas (parcial o completo).
     */
    public function guardarRespuestas(Request $request, Evaluacion $evaluacion): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $validated = $request->validate([
            'respuestas'                              => 'required|array|min:1',
            'respuestas.*.formulario_pregunta_id'     => 'required|integer|exists:formulario_preguntas,id',
            'respuestas.*.respuesta'                  => 'required|string|max:2000',
            'respuestas.*.estado_cumplimiento'        => 'nullable|in:cumple,no_cumple,no_aplica,no_verificable,pendiente',
            'respuestas.*.observacion'                => 'nullable|string|max:1000',
            'respuestas.*.evidencia_adjuntos'         => 'nullable|array',
        ]);

        foreach ($validated['respuestas'] as $item) {
            $evaluacion->respuestas()->updateOrCreate(
                ['formulario_pregunta_id' => $item['formulario_pregunta_id']],
                [
                    'respuesta'           => $item['respuesta'],
                    'estado_cumplimiento' => $item['estado_cumplimiento'] ?? 'pendiente',
                    'observacion'         => $item['observacion'] ?? null,
                    'evidencia_adjuntos'  => $item['evidencia_adjuntos'] ?? null,
                ]
            );
        }

        if ($request->has('metadata')) {
            $evaluacion->update([
                'metadata' => array_merge($evaluacion->metadata ?? [], $request->get('metadata')),
            ]);
        }

        $totalPreguntas = $this->formularioService
            ->seccionesAplicables($evaluacion->establecimiento)
            ->sum(fn($s) => $s->preguntas->where('activa', true)->count());

        $respondidas = $evaluacion->respuestas()->count();

        if ($respondidas >= $totalPreguntas) {
            $evaluacion->update(['estado' => 'completada']);
            if (Auth::user()) {
                $config = \App\Models\HomeConfiguration::first();
                $peiProfileId = $evaluacion->pei_profile_id ?: ($config?->pei_profile_id ?: '766eb883-fdd0-4723-8f75-cf689aa8f0fa');
                app(\App\Services\GamificationService::class)->awardPoints(
                    Auth::user(),
                    'riiss_evaluacion',
                    'Evaluación RIISS completada: ' . ($evaluacion->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                    100,
                    $evaluacion,
                    $peiProfileId
                );
            }
        } elseif ($respondidas > 0) {
            $evaluacion->update(['estado' => 'en_progreso']);
        }

        return response()->json([
            'ok'   => true,
            'data' => [
                'evaluacion_id' => $evaluacion->id,
                'respondidas'   => $respondidas,
                'total'         => $totalPreguntas,
                'progreso'      => $totalPreguntas > 0 ? round(($respondidas / $totalPreguntas) * 100, 1) : 0,
                'estado'        => $evaluacion->estado,
            ],
            'message' => 'Respuestas guardadas',
        ]);
    }

    /**
     * POST /riiss/evaluaciones/{id}/ejecutar-gap
     */
    public function ejecutarGap(Evaluacion $evaluacion): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $resultado = $this->gapService->ejecutar($evaluacion);

        return response()->json(['ok' => true, 'data' => $resultado]);
    }

    /**
     * GET /riiss/evaluaciones/{id}/gap
     */
    public function obtenerGap(Evaluacion $evaluacion): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        return response()->json([
            'ok'   => true,
            'data' => $this->gapService->obtenerGapPersistido($evaluacion),
        ]);
    }

    /**
     * GET /riiss/evaluaciones/{id}
     * Detalle completo de una evaluación.
     */
    public function show(Evaluacion $evaluacion)
    {
        $this->authorizeEvaluacion($evaluacion);
        $evaluacion->load(['establecimiento', 'respuestas.pregunta.seccion', 'gapAnalysis']);

        if (request()->expectsJson()) {
            return response()->json(['ok' => true, 'data' => $evaluacion]);
        }

        return view('admin.riiss.evaluaciones.show', compact('evaluacion'));
    }

    /**
     * GET /riiss/evaluaciones/{id}/resumen-clasificacion
     */
    public function resumenClasificacion(Evaluacion $evaluacion): JsonResponse
    {
        $est       = $evaluacion->establecimiento;
        $requisitos = $this->carteraService->resumenRequisitos($est);
        $gap       = $evaluacion->gapAnalysis()
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return response()->json([
            'ok'   => true,
            'data' => [
                'establecimiento' => $est->toResumenArray(),
                'declara_ser'     => [
                    'complejidad' => $est->complejidad,
                    'tipologia'   => $est->tipologia_clasificacion,
                    'nivel'       => $est->nivel_atencion,
                    'grado'       => $est->grado_complejidad,
                ],
                'resultado_evaluacion' => [
                    'clasificacion'          => $evaluacion->clasificacion_resultado,
                    'porcentaje'             => $evaluacion->porcentaje_cumplimiento,
                    'servicios_cumplen'      => $gap['cumple'] ?? 0,
                    'servicios_no_cumplen'   => $gap['no_cumple'] ?? 0,
                    'servicios_no_verificables' => $gap['no_verificable'] ?? 0,
                ],
                'veredicto'                => $this->generarVeredicto($evaluacion, $est, $gap),
                'servicios_faltantes_criticos' => $evaluacion->gapAnalysis()
                    ->where('requerido_para_nivel', true)
                    ->where('estado', 'no_cumple')
                    ->orderByDesc('prioridad')
                    ->take(10)
                    ->get()
                    ->map(fn($g) => [
                        'servicio' => $g->servicio_nombre,
                        'grupo'    => $g->grupo_servicio,
                        'accion'   => $g->accion_recomendada,
                    ]),
            ],
        ]);
    }

    /**
     * DELETE /riiss/evaluaciones/{id}
     * Elimina (soft delete) una evaluación.
     */
    public function destroy(Evaluacion $evaluacion): JsonResponse
    {
        $user = auth()->user();
        if ($user && $user->hasAnyRole(['Analista - RIISS', 'Analista RIISS']) && !$user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS'])) {
            return response()->json(['ok' => false, 'message' => 'No tienes permisos para eliminar evaluaciones.'], 403);
        }

        $this->authorizeEvaluacion($evaluacion);
        $evaluacion->delete();

        return response()->json(['ok' => true, 'message' => 'Evaluación eliminada correctamente.']);
    }

    /**
     * GET /riiss/evaluaciones/{id}/matriz-partial
     * Retorna el HTML de la Matriz de Servicios para usar en modal (sin auth requerida para welcome).
     */
    public function matrizPartial(Evaluacion $evaluacion)
    {
        // Público — sin autenticación requerida
        $mapGradoColumna = [
            1 => 'aplica_puesto_sanitario',
            2 => 'aplica_clinica_periferica',
            3 => 'aplica_hospital_baja',
            4 => 'aplica_hospital_mediana',
            5 => 'aplica_hospital_alta',
            6 => 'aplica_hospital_alta',
        ];

        $tiposComplejidad   = \App\Models\Riiss\ComplejidadTipo::activos()->get();
        $complejidadTipoEst = $evaluacion->establecimiento->complejidadTipo;
        $gradoEst           = $complejidadTipoEst?->grado ?? 0;
        $colActual          = $mapGradoColumna[$gradoEst] ?? null;
        $nombreNivel        = $complejidadTipoEst?->nombre ?? 'Sin clasificación';
        $tipoLabel          = $complejidadTipoEst?->tipo_establecimiento ?? '';

        $columnas  = $tiposComplejidad->map(fn($tipo) => [
            'key'        => $mapGradoColumna[$tipo->grado] ?? null,
            'label'      => $tipo->nombre,
            'tipo_label' => $tipo->tipo_establecimiento,
            'nivel'      => $tipo->nivel_atencion,
            'grado'      => $tipo->grado,
            'color'      => $tipo->color ?? '#1a237e',
        ])->filter(fn($c) => $c['key'] !== null)->values();

        $servicios = \App\Models\Riiss\CarteraServicio::orderBy('tipo_prestacion')
            ->orderBy('grupo_servicio')->orderBy('servicio')->get();

        $gapItems = $evaluacion->gapAnalysis()
            ->where('dimension', 'cartera_servicios')
            ->get()->keyBy('servicio_nombre');

        return view('admin.riiss.evaluaciones.partials.matriz_partial', compact(
            'evaluacion', 'columnas', 'servicios', 'gapItems',
            'colActual', 'nombreNivel', 'tipoLabel', 'mapGradoColumna'
        ));
    }

    private function authorizeEvaluacion(Evaluacion $evaluacion): void
    {
        if (!auth()->user()->hasAnyRole(['Administrador', 'Super Admin', 'Analista - RIISS', 'Analista RIISS', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS'])) {
            abort(403, 'No estás autorizado para acceder a esta evaluación.');
        }
    }

    private function generarVeredicto(Evaluacion $evaluacion, Establecimiento $est, array $gap): string
    {
        $clasificacion = $evaluacion->clasificacion_resultado;
        $noCumple      = $gap['no_cumple'] ?? 0;
        $criticos      = $evaluacion->gapAnalysis()
            ->where('prioridad', '>=', 2)->where('estado', 'no_cumple')->count();

        if ($clasificacion === 'CUMPLE') {
            return "El establecimiento {$est->nombre_oficial} CUMPLE con los requisitos de la cartera de servicios para su nivel declarado ({$est->complejidad}).";
        }

        if ($clasificacion === 'CUMPLE_PARCIALMENTE') {
            return "El establecimiento {$est->nombre_oficial} CUMPLE PARCIALMENTE. Tiene {$noCumple} servicios requeridos que no se verificaron. Se recomienda completar las observaciones y plan de mejora en un plazo de 90 días.";
        }

        $msg = "El establecimiento {$est->nombre_oficial} NO CUMPLE con los requisitos para su nivel declarado ({$est->complejidad}). ";
        $msg .= "Se identificaron {$noCumple} servicios faltantes ({$criticos} críticos). ";

        if ($est->grado_complejidad > 1) {
            $msg .= "Se sugiere evaluar la recategorización a un nivel inferior o completar las deficiencias antes de mantener la categoría actual.";
        } else {
            $msg .= "Al ser el nivel más bajo, se requiere un plan de acción inmediato para cumplir con los servicios mínimos.";
        }

        return $msg;
    }
}
