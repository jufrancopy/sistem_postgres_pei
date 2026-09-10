<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\Evaluacion;
use App\Models\Riiss\ValidacionEspecialidadRegistro;
use App\Models\Riiss\SesionValidador;
use App\Models\RiissEspecialidad;
use App\Models\User;
use App\Services\CarteraMatchingService;
use App\Services\FormularioDinamicoService;
use App\Services\GapAnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    public function dashboardDatos(Request $request): JsonResponse
    {
        $query = Evaluacion::with('establecimiento')
            ->whereIn('estado', ['borrador', 'en_progreso', 'completada'])
            ->orderByDesc('updated_at');

        if ($area = trim($request->get('area_gestion', ''))) {
            $query->whereHas('establecimiento', function($q) use ($area) {
                $q->where('area_gestion', $area);
            });
        }

        $evaluaciones = $query->get()
            ->map(function ($ev) {
                $respondidas = $ev->respuestas()->count();
                $totalPreguntas = 0;
                try {
                    $totalPreguntas = (new \App\Services\FormularioDinamicoService())
                        ->seccionesAplicables($ev->establecimiento)
                        ->sum(fn($s) => $s->preguntas->where('activa', true)->count());
                } catch (\Exception $e) {}

                $pct = 0;
                if ($ev->porcentaje_cumplimiento !== null && (float)$ev->porcentaje_cumplimiento > 0) {
                    $pct = min(100.0, round((float)$ev->porcentaje_cumplimiento, 1));
                } elseif ($totalPreguntas > 0) {
                    $pct = min(100.0, round(($respondidas / $totalPreguntas) * 100, 1));
                }

                return [
                    'id'                      => $ev->id,
                    'id_establecimiento'      => $ev->id_establecimiento,
                    'establecimiento'         => $ev->establecimiento->nombre_oficial ?? '—',
                    'tipologia'               => $ev->establecimiento->tipologia_clasificacion ?? '—',
                    'departamento'            => $ev->establecimiento->departamento ?? '—',
                    'area_gestion'            => $ev->establecimiento->area_gestion ?? '—',
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
                    'cerrado_con_firmas'      => $ev->isCerradaConFirmas(),
                    'responsable_nombre'      => $ev->responsable_nombre,
                    'responsable_cargo'       => $ev->responsable_cargo,
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
     * POST /riiss/evaluaciones/{id}/cerrar-con-firmas
     * Sellar y cerrar formalmente el relevamiento con firmas digitales del receptor y evaluador.
     */
    public function cerrarConFirmas(Request $request, Evaluacion $evaluacion): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $validated = $request->validate([
            'responsable_nombre'     => 'required|string|max:200',
            'responsable_cargo'      => 'required|string|max:150',
            'responsable_documento'  => 'nullable|string|max:50',
            'responsable_telefono'   => 'nullable|string|max:50',
            'responsable_firma'      => 'required|string',
            'evaluador_firma'        => 'nullable|string',
            'evaluador_nombre'       => 'nullable|string|max:200',
            'evaluador_cargo'        => 'nullable|string|max:150',
            'evaluadores_firmas'     => 'nullable|array',
            'cierre_observaciones'   => 'nullable|string|max:2000',
        ], [
            'responsable_nombre.required' => 'El nombre del responsable receptor es obligatorio.',
            'responsable_cargo.required'  => 'El cargo del responsable receptor es obligatorio.',
            'responsable_firma.required'  => 'La firma digital del responsable del establecimiento es obligatoria.',
        ]);

        $currentUser = Auth::user();
        $firmasEvaluadores = is_array($evaluacion->firmas_evaluadores) ? $evaluacion->firmas_evaluadores : [];

        // Si se envió un array de firmas de múltiples evaluadores
        if (!empty($validated['evaluadores_firmas']) && is_array($validated['evaluadores_firmas'])) {
            foreach ($validated['evaluadores_firmas'] as $ef) {
                if (empty($ef['firma'])) continue;
                $uId = $ef['user_id'] ?? null;
                $nombre = $ef['nombre'] ?? ($currentUser?->name ?? 'Evaluador IPS');
                $cargo = $ef['cargo'] ?? 'Evaluador / Analista RIISS — Dirección de Planificación';
                
                $firmaItem = [
                    'user_id'    => $uId ? (int)$uId : null,
                    'nombre'     => $nombre,
                    'cargo'      => $cargo,
                    'email'      => $ef['email'] ?? ($currentUser?->email),
                    'firma'      => $ef['firma'],
                    'firmado_at' => now()->format('Y-m-d H:i:s'),
                ];

                $updated = false;
                foreach ($firmasEvaluadores as $idx => $existente) {
                    if (($uId && isset($existente['user_id']) && $existente['user_id'] == $uId) || (isset($existente['nombre']) && strcasecmp(trim($existente['nombre']), trim($nombre)) === 0)) {
                        $firmasEvaluadores[$idx] = $firmaItem;
                        $updated = true;
                        break;
                    }
                }
                if (!$updated) {
                    $firmasEvaluadores[] = $firmaItem;
                }
            }
        } elseif (!empty($validated['evaluador_firma'])) {
            // Caso firma única tradicional
            $firmaEvaluadorItem = [
                'user_id'     => $currentUser?->id,
                'nombre'      => $validated['evaluador_nombre'] ?: ($currentUser?->name ?? 'Evaluador IPS'),
                'cargo'       => $validated['evaluador_cargo'] ?: 'Evaluador / Analista RIISS',
                'email'       => $currentUser?->email,
                'firma'       => $validated['evaluador_firma'],
                'firmado_at'  => now()->format('Y-m-d H:i:s'),
            ];

            $foundIndex = false;
            if ($currentUser) {
                foreach ($firmasEvaluadores as $idx => $f) {
                    if (isset($f['user_id']) && $f['user_id'] == $currentUser->id) {
                        $firmasEvaluadores[$idx] = $firmaEvaluadorItem;
                        $foundIndex = true;
                        break;
                    }
                }
            }
            if (!$foundIndex) {
                $firmasEvaluadores[] = $firmaEvaluadorItem;
            }
        }

        if (empty($firmasEvaluadores)) {
            return response()->json(['ok' => false, 'message' => 'Debe registrar al menos una firma de evaluador técnico.'], 422);
        }

        // Actualizar la evaluación
        $evaluacion->update([
            'responsable_nombre'     => $validated['responsable_nombre'],
            'responsable_cargo'      => $validated['responsable_cargo'],
            'responsable_documento'  => $validated['responsable_documento'] ?? null,
            'responsable_telefono'   => $validated['responsable_telefono'] ?? null,
            'responsable_firma'      => $validated['responsable_firma'],
            'responsable_firmado_at' => now(),
            'firmas_evaluadores'     => $firmasEvaluadores,
            'cierre_observaciones'   => $validated['cierre_observaciones'] ?? null,
            'estado'                 => 'completada',
            'cerrado_at'             => now(),
            'cerrado_por_id'         => $currentUser?->id,
        ]);

        // Sincronizar asignaciones asociadas a este establecimiento
        \App\Models\Riiss\Asignacion::where('id_establecimiento', $evaluacion->id_establecimiento)
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->update([
                'estado'        => 'completada',
                'evaluacion_id' => $evaluacion->id,
            ]);

        // Otorgar gamificación por cierre formal
        if ($currentUser) {
            $config = \App\Models\HomeConfiguration::first();
            $peiProfileId = $evaluacion->pei_profile_id ?: ($config?->pei_profile_id ?: '766eb883-fdd0-4723-8f75-cf689aa8f0fa');
            app(\App\Services\GamificationService::class)->awardPoints(
                $currentUser,
                'riiss_cierre_firmado',
                'Cierre con firmas del Relevamiento RIISS: ' . ($evaluacion->establecimiento?->nombre_oficial ?? 'Establecimiento'),
                150,
                $evaluacion,
                $peiProfileId
            );
        }

        return response()->json([
            'ok'      => true,
            'message' => '¡Relevamiento cerrado y sellado exitosamente con firmas digitales!',
            'data'    => [
                'evaluacion_id'      => $evaluacion->id,
                'estado'             => $evaluacion->estado,
                'cerrado_at'         => $evaluacion->cerrado_at?->format('d/m/Y H:i:s'),
                'responsable_nombre' => $evaluacion->responsable_nombre,
                'responsable_cargo'  => $evaluacion->responsable_cargo,
            ],
        ]);
    }

    /**
     * POST /riiss/evaluaciones/{evaluacion}/firmar-evaluador
     * Registra o actualiza la firma de un evaluador técnico en una evaluación ya iniciada o cerrada.
     */
    public function firmarEvaluador(Request $request, Evaluacion $evaluacion): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $validated = $request->validate([
            'evaluador_firma'   => 'required|string',
            'evaluador_nombre'  => 'required|string|max:200',
            'evaluador_cargo'   => 'nullable|string|max:150',
            'evaluador_user_id' => 'nullable|integer',
        ], [
            'evaluador_firma.required'  => 'La firma digital es obligatoria.',
            'evaluador_nombre.required' => 'El nombre del evaluador es obligatorio.',
        ]);

        $currentUser = Auth::user();
        $userId = $validated['evaluador_user_id'] ?? $currentUser?->id;
        $firmasEvaluadores = is_array($evaluacion->firmas_evaluadores) ? $evaluacion->firmas_evaluadores : [];

        $firmaItem = [
            'user_id'    => $userId ? (int)$userId : null,
            'nombre'     => $validated['evaluador_nombre'],
            'cargo'      => $validated['evaluador_cargo'] ?: 'Evaluador / Analista RIISS — Dirección de Planificación',
            'email'      => $currentUser?->email,
            'firma'      => $validated['evaluador_firma'],
            'firmado_at' => now()->format('Y-m-d H:i:s'),
        ];

        $found = false;
        foreach ($firmasEvaluadores as $idx => $f) {
            if (($userId && isset($f['user_id']) && $f['user_id'] == $userId) || (isset($f['nombre']) && strcasecmp(trim($f['nombre']), trim($validated['evaluador_nombre'])) === 0)) {
                $firmasEvaluadores[$idx] = $firmaItem;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $firmasEvaluadores[] = $firmaItem;
        }

        $evaluacion->update([
            'firmas_evaluadores' => $firmasEvaluadores,
        ]);

        return response()->json([
            'ok'      => true,
            'message' => '¡Firma de evaluador registrada exitosamente!',
            'data'    => [
                'evaluacion_id'      => $evaluacion->id,
                'firmas_evaluadores' => $firmasEvaluadores,
            ]
        ]);
    }

    /**
     * GET /riiss/evaluaciones/{id}
     * Detalle completo de una evaluación.
     */
    public function show(Evaluacion $evaluacion)
    {
        $this->authorizeEvaluacion($evaluacion);
        $evaluacion->load(['establecimiento.especialidades', 'respuestas.pregunta.seccion', 'gapAnalysis', 'cerradoPor']);

        $est = $evaluacion->establecimiento;

        // Validaciones existentes para las especialidades de este establecimiento
        $validacionesEspecialidades = ValidacionEspecialidadRegistro::where('establecimiento_id', $est->id_establecimiento)
            ->with('sesionValidador')
            ->get()
            ->keyBy('especialidad_id');

        // Especialidades añadidas en terreno que no estaban originalmente
        $especialidadesAgregadas = [];
        foreach ($validacionesEspecialidades as $valReg) {
            if ($valReg->es_agregada && !$est->especialidades->contains('id', $valReg->especialidad_id)) {
                $espModel = RiissEspecialidad::find($valReg->especialidad_id);
                if ($espModel) {
                    $especialidadesAgregadas[] = $espModel;
                }
            }
        }

        // Sesión de validación activa para el área/departamento del establecimiento
        $sesionActiva = SesionValidador::where('estado', 'activo')
            ->where(function($q) use ($est) {
                $q->where('area_gestion', $est->area_gestion)
                  ->orWhereNull('area_gestion');
            })
            ->latest()
            ->first();

        if (request()->expectsJson()) {
            return response()->json(['ok' => true, 'data' => $evaluacion]);
        }

        return view('admin.riiss.evaluaciones.show', compact('evaluacion', 'validacionesEspecialidades', 'especialidadesAgregadas', 'sesionActiva'));
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
     * GET /riiss/evaluaciones/{evaluacion}/acta-datos
     * Retorna datos completos para el Acta Institucional de Cierre en Terreno.
     */
    public function actaDatos(Evaluacion $evaluacion): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $est = $evaluacion->establecimiento;
        $respondidas = $evaluacion->respuestas()->count();
        $totalPreguntas = 0;
        try {
            $totalPreguntas = (new \App\Services\FormularioDinamicoService())
                ->seccionesAplicables($est)
                ->sum(fn($s) => $s->preguntas->where('activa', true)->count());
        } catch (\Exception $e) {}

        $pct = $totalPreguntas > 0 ? round(($respondidas / $totalPreguntas) * 100, 1) : ($evaluacion->porcentaje_cumplimiento ?? 0);

        $primeraFirmaEval = !empty($evaluacion->firmas_evaluadores) ? $evaluacion->firmas_evaluadores[0] : null;
        $evaluadorNombre = $primeraFirmaEval['nombre'] ?? ($evaluacion->evaluador_nombre ?: ($evaluacion->cerradoPor ? $evaluacion->cerradoPor->name : 'Equipo Técnico IPS'));
        $evaluadorCargo  = $primeraFirmaEval['cargo'] ?? 'Evaluador Técnico — Dirección de Planificación';
        $evaluadorFirma  = $primeraFirmaEval['firma'] ?? null;

        $gap = $evaluacion->gapAnalysis()
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $veredicto = $this->generarVeredicto($evaluacion, $est, $gap);
        $instCtx = $this->resolveInstitucionalContext($evaluacion);

        return response()->json([
            'ok' => true,
            'data' => [
                'id'                      => $evaluacion->id,
                'establecimiento'         => [
                    'id'                  => $est->id_establecimiento,
                    'nombre'              => $est->nombre_oficial,
                    'tipologia'           => $est->tipologia_clasificacion,
                    'complejidad'         => $est->complejidad,
                    'complejidad_color'   => $est->complejidad_color,
                    'nivel_atencion'      => $est->nivel_atencion,
                    'grado_complejidad'   => $est->grado_complejidad,
                    'departamento'        => $est->departamento,
                    'distrito'            => $est->distrito ?? $est->ciudad,
                    'microred'            => $est->microred,
                    'red'                 => $est->red,
                    'prestador'           => $est->prestador,
                ],
                'marco_institucional'     => [
                    'institucion'         => $instCtx['institucion'],
                    'dependencia'         => $instCtx['dependencia'],
                    'logo_institucional'  => $instCtx['logo_institucional'],
                    'pei_nombre'          => $instCtx['pei_nombre'],
                    'evaluadores_texto'   => $instCtx['evaluadores_texto'],
                    'evaluadores_lista'   => $instCtx['evaluadores_lista'],
                    'politica'            => 'POLÍTICA DE REDES INTEGRADAS E INTEGRALES DE SERVICIOS DE SALUD (RIISS)',
                    'modulo_numero'       => 1,
                    'modulo_nombre'       => 'RELEVAMIENTO DE CARTERA DE SERVICIOS Y CAPACIDAD RESOLUTIVA',
                    'total_modulos'       => 9,
                    'footer_text'         => $instCtx['footer_text'],
                    'contact_email'       => $instCtx['contact_email'],
                    'contact_phone'       => $instCtx['contact_phone'],
                    'address'             => $instCtx['address'],
                    'site_name'           => $instCtx['site_name'],
                ],
                'visita'                  => [
                    'fecha'               => $evaluacion->fecha_evaluacion?->format('d/m/Y') ?: date('d/m/Y'),
                    'fecha_hora_cierre'   => $evaluacion->cerrado_at?->format('d/m/Y H:i') ?: ($evaluacion->responsable_firmado_at?->format('d/m/Y H:i') ?: date('d/m/Y H:i')),
                    'estado'              => $evaluacion->estado,
                    'cerrado_con_firmas'  => $evaluacion->isCerradaConFirmas(),
                    'respondidas'         => $respondidas,
                    'total_preguntas'     => $totalPreguntas,
                    'progreso'            => $pct,
                    'cumplimiento'        => $evaluacion->porcentaje_cumplimiento ?? $pct,
                    'clasificacion'       => $evaluacion->clasificacion_resultado,
                    'veredicto'           => $veredicto,
                    'observaciones'       => $evaluacion->cierre_observaciones,
                ],
                'responsable'             => [
                    'nombre'              => $evaluacion->responsable_nombre,
                    'cargo'               => $evaluacion->responsable_cargo,
                    'documento'           => $evaluacion->responsable_documento,
                    'telefono'            => $evaluacion->responsable_telefono,
                    'firma'               => $evaluacion->responsable_firma,
                    'firmado_at'          => $evaluacion->responsable_firmado_at?->format('d/m/Y H:i'),
                ],
                'fotos'                   => is_array($evaluacion->fotos) ? $evaluacion->fotos : [],
                'evaluador'               => [
                    'nombre'              => $evaluadorNombre,
                    'cargo'               => $evaluadorCargo,
                    'telefono'            => $evaluacion->evaluador_telefono,
                    'firma'               => $evaluadorFirma,
                    'cerrado_por'         => $evaluacion->cerradoPor?->name,
                ],
            ]
        ]);
    }

    /**
     * GET /riiss/evaluaciones/{evaluacion}/acta-pdf
     * Descarga el PDF oficial del Acta Institucional de Cierre RIISS generado con DomPDF.
     */
    public function descargarActaPdf(Evaluacion $evaluacion)
    {
        $this->authorizeEvaluacion($evaluacion);

        $est = $evaluacion->establecimiento;
        $respondidas = $evaluacion->respuestas()->count();
        $totalPreguntas = 0;
        try {
            $totalPreguntas = (new \App\Services\FormularioDinamicoService())
                ->seccionesAplicables($est)
                ->sum(fn($s) => $s->preguntas->where('activa', true)->count());
        } catch (\Exception $e) {}

        $progreso = $totalPreguntas > 0 ? round(($respondidas / $totalPreguntas) * 100, 1) : ($evaluacion->porcentaje_cumplimiento ?? 0);

        $primeraFirmaEval = !empty($evaluacion->firmas_evaluadores) ? $evaluacion->firmas_evaluadores[0] : null;
        $evaluadorNombre = $primeraFirmaEval['nombre'] ?? ($evaluacion->evaluador_nombre ?: ($evaluacion->cerradoPor ? $evaluacion->cerradoPor->name : 'Equipo Técnico IPS'));
        $evaluadorCargo  = $primeraFirmaEval['cargo'] ?? 'Evaluador Técnico — Dirección de Planificación';
        $evaluadorFirma  = $primeraFirmaEval['firma'] ?? null;

        $gap = $evaluacion->gapAnalysis()
            ->select('estado', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $veredicto = $this->generarVeredicto($evaluacion, $est, $gap);
        $clasificacion = $evaluacion->clasificacion_resultado ?: 'PENDIENTE';
        $instCtx = $this->resolveInstitucionalContext($evaluacion);

        $logoInstitucional = $this->prepareLogoForPdf($instCtx['logo_institucional']);
        $institucion       = $instCtx['institucion'];
        $dependencia       = $instCtx['dependencia'];
        $footerText        = $instCtx['footer_text'];
        $contactEmail      = $instCtx['contact_email'];
        $contactPhone      = $instCtx['contact_phone'];
        $address           = $instCtx['address'];
        $peiNombre         = $instCtx['pei_nombre'];
        $evaluadoresTexto  = $instCtx['evaluadores_texto'];
        $evaluadoresLista  = $instCtx['evaluadores_lista'];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.riiss.evaluaciones.acta_pdf', compact(
            'evaluacion', 'est', 'respondidas', 'totalPreguntas', 'progreso',
            'evaluadorNombre', 'evaluadorCargo', 'evaluadorFirma', 'veredicto', 'clasificacion',
            'logoInstitucional', 'institucion', 'dependencia', 'footerText', 'contactEmail', 'contactPhone', 'address',
            'peiNombre', 'evaluadoresTexto', 'evaluadoresLista'
        ))->setPaper('a4', 'portrait');

        $slugEst = \Illuminate\Support\Str::slug($est->nombre_oficial ?: 'establecimiento', '_');
        return $pdf->stream("Acta_RIISS_Modulo1_{$slugEst}.pdf");
    }

    /**
     * GET /riiss/evaluaciones/{evaluacion}/acta-imprimir
     * Vista imprimible institucional limpia para impresión directa.
     */
    public function imprimirActa(Evaluacion $evaluacion)
    {
        $this->authorizeEvaluacion($evaluacion);

        $est = $evaluacion->establecimiento;
        $respondidas = $evaluacion->respuestas()->count();
        $totalPreguntas = 0;
        try {
            $totalPreguntas = (new \App\Services\FormularioDinamicoService())
                ->seccionesAplicables($est)
                ->sum(fn($s) => $s->preguntas->where('activa', true)->count());
        } catch (\Exception $e) {}

        $progreso = $totalPreguntas > 0 ? round(($respondidas / $totalPreguntas) * 100, 1) : ($evaluacion->porcentaje_cumplimiento ?? 0);

        $primeraFirmaEval = !empty($evaluacion->firmas_evaluadores) ? $evaluacion->firmas_evaluadores[0] : null;
        $evaluadorNombre = $primeraFirmaEval['nombre'] ?? ($evaluacion->evaluador_nombre ?: ($evaluacion->cerradoPor ? $evaluacion->cerradoPor->name : 'Equipo Técnico IPS'));
        $evaluadorCargo  = $primeraFirmaEval['cargo'] ?? 'Evaluador Técnico — Dirección de Planificación';
        $evaluadorFirma  = $primeraFirmaEval['firma'] ?? null;

        $gap = $evaluacion->gapAnalysis()
            ->select('estado', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $veredicto = $this->generarVeredicto($evaluacion, $est, $gap);
        $clasificacion = $evaluacion->clasificacion_resultado ?: 'PENDIENTE';
        $instCtx = $this->resolveInstitucionalContext($evaluacion);

        $logoInstitucional = $instCtx['logo_institucional'];
        $institucion       = $instCtx['institucion'];
        $dependencia       = $instCtx['dependencia'];
        $footerText        = $instCtx['footer_text'];
        $contactEmail      = $instCtx['contact_email'];
        $contactPhone      = $instCtx['contact_phone'];
        $address           = $instCtx['address'];
        $peiNombre         = $instCtx['pei_nombre'];
        $evaluadoresTexto  = $instCtx['evaluadores_texto'];
        $evaluadoresLista  = $instCtx['evaluadores_lista'];

        return view('admin.riiss.evaluaciones.acta_imprimir', compact(
            'evaluacion', 'est', 'respondidas', 'totalPreguntas', 'progreso',
            'evaluadorNombre', 'evaluadorCargo', 'evaluadorFirma', 'veredicto', 'clasificacion',
            'logoInstitucional', 'institucion', 'dependencia', 'footerText', 'contactEmail', 'contactPhone', 'address',
            'peiNombre', 'evaluadoresTexto', 'evaluadoresLista'
        ));
    }

    /**
     * Resuelve los datos institucionales (Logo, Institución, Dependencia, Footer, PEI y Evaluadores)
     * desde el PEI Profile (Variables del Plan) y HomeConfiguration.
     */
    private function resolveInstitucionalContext(Evaluacion $evaluacion): array
    {
        $profile = $evaluacion->peiProfile;
        if (!$profile) {
            $profile = \App\Admin\Planificacion\Pei\PeiProfile::find('ce99f883-fdd0-4723-8f75-cf689aa8f0fa')
                ?? \App\Admin\Planificacion\Pei\PeiProfile::where('level', 'master')->first()
                ?? \App\Admin\Planificacion\Pei\PeiProfile::first();
        }

        $master = $profile && $profile->parent_id ? ($profile->getRoot() ?? $profile) : $profile;
        $params = [];
        if ($master) {
            $params = is_string($master->parameters) ? (json_decode($master->parameters, true) ?? []) : ($master->parameters ?? []);
        }

        $logoInstitucional = $params['logo_institucional'] ?? $params['acta_logo_url'] ?? ($master?->logo_institucional ?? null);
        $institucion       = $params['acta_institucion'] ?? 'INSTITUTO DE PREVISIÓN SOCIAL (IPS)';
        $dependencia       = $params['acta_dependencia'] ?? 'DIRECCIÓN DE PLANIFICACIÓN';

        $footerText   = \App\Models\HomeConfiguration::getSetting('footer_text', '© ' . date('Y') . ' Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.');
        $contactEmail = \App\Models\HomeConfiguration::getSetting('contact_email');
        $contactPhone = \App\Models\HomeConfiguration::getSetting('contact_phone');
        $address      = \App\Models\HomeConfiguration::getSetting('address');
        $siteName     = \App\Models\HomeConfiguration::getSetting('site_name', 'SIPLAN');

        $peiNombre = $evaluacion->peiProfile?->name
            ?? ($profile?->name ?: 'Plan Estratégico Institucional (PEI 2024–2028)');

        // Extraer lista completa de evaluadores/relevadores
        $evaluadoresList = [];
        if (!empty($evaluacion->evaluadores) && is_array($evaluacion->evaluadores)) {
            foreach ($evaluacion->evaluadores as $ev) {
                if (is_array($ev) && !empty($ev['text'])) {
                    $evaluadoresList[] = $ev['text'];
                } elseif (is_string($ev) && trim($ev) !== '') {
                    $evaluadoresList[] = trim($ev);
                }
            }
        }
        if (empty($evaluadoresList) && !empty($evaluacion->evaluador_nombre)) {
            $evaluadoresList = array_map('trim', explode(',', $evaluacion->evaluador_nombre));
        }
        if (empty($evaluadoresList)) {
            $evaluadoresList = [$evaluacion->cerradoPor ? $evaluacion->cerradoPor->name : 'Equipo Técnico Relevador — Dirección de Planificación'];
        }
        $evaluadoresTexto = implode(', ', array_unique($evaluadoresList));

        return [
            'logo_institucional' => $logoInstitucional,
            'institucion'        => $institucion,
            'dependencia'        => $dependencia,
            'footer_text'        => $footerText,
            'contact_email'      => $contactEmail,
            'contact_phone'      => $contactPhone,
            'address'            => $address,
            'site_name'          => $siteName,
            'pei_nombre'         => $peiNombre,
            'evaluadores_lista'  => $evaluadoresList,
            'evaluadores_texto'  => $evaluadoresTexto,
        ];
    }

    /**
     * Prepara el logo para DomPDF convirtiendo recursos locales a data URI base64.
     */
    private function prepareLogoForPdf(?string $logoUrl): ?string
    {
        if (empty($logoUrl)) return null;

        if (str_starts_with($logoUrl, 'data:image')) {
            return $logoUrl;
        }

        $localFile = null;
        $storagePrefix = asset('storage/');
        if (str_starts_with($logoUrl, $storagePrefix)) {
            $relative = str_replace($storagePrefix, '', $logoUrl);
            $storageCandidate = storage_path('app/public/' . ltrim($relative, '/'));
            if (file_exists($storageCandidate)) {
                $localFile = $storageCandidate;
            }
        } elseif (str_starts_with($logoUrl, '/storage/')) {
            $publicCandidate = public_path(ltrim($logoUrl, '/'));
            if (file_exists($publicCandidate)) {
                $localFile = $publicCandidate;
            }
        } elseif (file_exists(public_path(ltrim($logoUrl, '/')))) {
            $localFile = public_path(ltrim($logoUrl, '/'));
        }

        if ($localFile && file_exists($localFile)) {
            $mime = mime_content_type($localFile) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($localFile));
        }

        return $logoUrl;
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

    /**
     * POST /riiss/evaluaciones/{evaluacion}/fotos
     * Subir foto de evidencia fotográfica con descripción.
     */
    public function subirFoto(Request $request, Evaluacion $evaluacion): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $request->validate([
            'foto'        => 'required|image|mimes:jpeg,png,jpg,webp|max:12288',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $file = $request->file('foto');
        $extension = $file->getClientOriginalExtension();
        $filename = 'relevamiento_' . $evaluacion->id . '_' . time() . '_' . Str::random(6) . '.' . $extension;

        // Guardar en public storage
        $path = $file->storeAs('riiss/evaluaciones/' . $evaluacion->id, $filename, 'public');
        $url = asset('storage/' . $path);

        $nuevaFoto = [
            'id'             => 'foto_' . uniqid(),
            'url'            => $url,
            'path'           => $path,
            'nombre_archivo' => $file->getClientOriginalName(),
            'descripcion'    => trim($request->get('descripcion', '')),
            'fecha'          => now()->format('d/m/Y H:i'),
            'subido_por'     => auth()->user()?->name ?? 'Evaluador',
        ];

        $fotos = is_array($evaluacion->fotos) ? $evaluacion->fotos : [];
        $fotos[] = $nuevaFoto;

        $evaluacion->fotos = $fotos;
        $evaluacion->save();

        return response()->json([
            'ok'      => true,
            'foto'    => $nuevaFoto,
            'fotos'   => $fotos,
            'total'   => count($fotos),
            'message' => 'Foto guardada con éxito',
        ]);
    }

    /**
     * DELETE /riiss/evaluaciones/{evaluacion}/fotos/{fotoId}
     * Eliminar una foto de la galería.
     */
    public function eliminarFoto(Evaluacion $evaluacion, string $fotoId): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $fotos = is_array($evaluacion->fotos) ? $evaluacion->fotos : [];
        $fotosFiltradas = [];
        $encontrada = false;

        foreach ($fotos as $f) {
            if (($f['id'] ?? '') === $fotoId) {
                $encontrada = true;
                if (!empty($f['path'])) {
                    Storage::disk('public')->delete($f['path']);
                }
            } else {
                $fotosFiltradas[] = $f;
            }
        }

        if ($encontrada) {
            $evaluacion->fotos = array_values($fotosFiltradas);
            $evaluacion->save();
        }

        return response()->json([
            'ok'      => true,
            'fotos'   => array_values($fotosFiltradas),
            'total'   => count($fotosFiltradas),
            'message' => 'Foto eliminada correctamente',
        ]);
    }

    /**
     * PATCH /riiss/evaluaciones/{evaluacion}/fotos/{fotoId}/descripcion
     * Actualizar la descripción técnica de una foto.
     */
    public function actualizarDescripcionFoto(Request $request, Evaluacion $evaluacion, string $fotoId): JsonResponse
    {
        $this->authorizeEvaluacion($evaluacion);

        $request->validate([
            'descripcion' => 'nullable|string|max:255',
        ]);

        $descripcion = trim($request->get('descripcion', ''));
        $fotos = is_array($evaluacion->fotos) ? $evaluacion->fotos : [];
        $actualizada = false;

        foreach ($fotos as &$f) {
            if (($f['id'] ?? '') === $fotoId) {
                $f['descripcion'] = $descripcion;
                $actualizada = true;
                break;
            }
        }
        unset($f);

        if ($actualizada) {
            $evaluacion->fotos = $fotos;
            $evaluacion->save();
        }

        return response()->json([
            'ok'      => true,
            'fotos'   => $fotos,
            'message' => 'Descripción actualizada',
        ]);
    }
}
