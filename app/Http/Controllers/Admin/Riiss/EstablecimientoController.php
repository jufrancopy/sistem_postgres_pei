<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\Homologacion;
use App\Services\CarteraMatchingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstablecimientoController extends Controller
{
    public function __construct(private CarteraMatchingService $carteraService) {}

    /**
     * GET /riiss/establecimientos/buscar
     * Búsqueda JSON pura — siempre devuelve JSON, usada por modales y AJAX.
     */
    public function buscar(Request $request): JsonResponse
    {
        return $this->indexJson($request);
    }

    /**
     * GET /riiss/establecimientos
     * Vista principal con listado.
     */
    public function index(Request $request)
    {
        if ($request->expectsJson() || $request->boolean('json')) {            return $this->indexJson($request);
        }

        $departamentos = Establecimiento::activos()->asistenciales()
            ->select('departamento')->distinct()->orderBy('departamento')->pluck('departamento');

        $tipos = Establecimiento::activos()->asistenciales()
            ->select('tipo_est')->distinct()->orderBy('tipo_est')->pluck('tipo_est');

        $complejidades = Establecimiento::activos()->asistenciales()
            ->select('complejidad')->distinct()->orderBy('complejidad')->pluck('complejidad');

        $base = Establecimiento::activos()->asistenciales();
        $stats = [
            'total' => (clone $base)->count(),
            'hospitalarios' => (clone $base)->hospitalarios()->count(),
            'con_evaluacion' => (clone $base)->whereHas('evaluaciones')->count(),
            'sin_evaluacion' => (clone $base)->whereDoesntHave('evaluaciones')->count(),
        ];

        return view('admin.riiss.establecimientos.index', compact('departamentos', 'tipos', 'complejidades', 'stats'));
    }

    private function indexJson(Request $request): JsonResponse
    {
        $query = Establecimiento::activos()->asistenciales()->with('ultimaEvaluacion');
        if ($request->filled('departamento')) $query->porDepartamento($request->departamento);
        if ($request->filled('microred'))     $query->porMicrored($request->microred);
        if ($request->filled('tipo'))         $query->porTipo($request->tipo);
        if ($request->filled('complejidad'))  $query->porComplejidad($request->complejidad);
        if ($request->filled('nivel'))        $query->porNivel((int) $request->nivel);
        if ($request->boolean('hospitalarios'))  $query->hospitalarios();
        if ($request->boolean('con_internacion')) $query->conInternacion();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(fn($q) => $q
                ->where('nombre_oficial', 'ILIKE', "%{$b}%")
                ->orWhere('id_establecimiento', 'ILIKE', "%{$b}%")
            );
        }

        $sortBy  = $request->get('sort_by', 'nombre_oficial');
        $sortDir = $request->get('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->get('per_page', 50);
        $result  = $query->paginate($perPage);
        $result->getCollection()->transform(fn($e) => $e->toResumenArray());

        return response()->json(['ok' => true, 'data' => $result]);
    }

    /**
     * PATCH /riiss/establecimientos/{id}
     * Actualizar datos de un establecimiento.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $est = Establecimiento::where('id_establecimiento', $id)->firstOrFail();

        $validated = $request->validate([
            'nombre_oficial'          => 'sometimes|string|max:300',
            'complejidad'             => 'sometimes|string|max:100',
            'tipologia_clasificacion' => 'sometimes|string|max:100',
            'departamento'            => 'sometimes|string|max:100',
            'microred'                => 'nullable|string|max:100',
            'prestador'               => 'nullable|string|max:100',
            'tiene_internacion'       => 'nullable|boolean',
            'tiene_quirofano_req'     => 'nullable|boolean',
            'tiene_uti_req'           => 'nullable|boolean',
            'tiene_urgencias_req'     => 'nullable|boolean',
            'observacion'             => 'nullable|string|max:1000',
        ]);

        $est->update($validated);

        // Si cambió la complejidad, recalcular derivados
        if (isset($validated['complejidad'])) {
            $est->recalcularCamposDerivados();
        }

        return response()->json([
            'ok'      => true,
            'message' => 'Establecimiento actualizado.',
            'data'    => $est->fresh()->toResumenArray(),
        ]);
    }

    /**
     * GET /riiss/establecimientos/{id}
     */
    public function show(string $id): JsonResponse
    {
        $est = Establecimiento::where('id_establecimiento', $id)
            ->with('ultimaEvaluacion', 'homologaciones')
            ->firstOrFail();

        return response()->json([
            'ok'                 => true,
            'data'               => $est,
            'cartera_requisitos' => $this->carteraService->resumenRequisitos($est),
        ]);
    }

    /**
     * GET /riiss/establecimientos/filtros/opciones
     */
    public function filtros(): JsonResponse
    {
        return response()->json([
            'ok'   => true,
            'data' => [
                'departamentos' => Establecimiento::activos()->asistenciales()
                    ->select('departamento')->distinct()->orderBy('departamento')->pluck('departamento'),
                'microredes'    => Establecimiento::activos()->asistenciales()
                    ->select('microred')->distinct()->orderBy('microred')->pluck('microred'),
                'tipos'         => Establecimiento::activos()->asistenciales()
                    ->select('tipo_est')->distinct()->orderBy('tipo_est')->pluck('tipo_est')
                    ->mapWithKeys(fn($t) => [$t => \App\Enums\TipoEstablecimientoEnum::tryFrom($t)?->label() ?? $t]),
                'complejidades' => Establecimiento::activos()->asistenciales()
                    ->select('complejidad')->distinct()->orderBy('complejidad')->pluck('complejidad'),
                'prestadores'   => Establecimiento::activos()->asistenciales()
                    ->select('prestador')->distinct()->orderBy('prestador')->pluck('prestador'),
            ],
        ]);
    }

    /**
     * GET /riiss/establecimientos/estadisticas
     */
    public function estadisticas(): JsonResponse
    {
        $base = Establecimiento::activos()->asistenciales();

        return response()->json([
            'ok'   => true,
            'data' => [
                'total'           => (clone $base)->count(),
                'por_tipo'        => (clone $base)->select('tipo_est', DB::raw('count(*) as total'))
                    ->groupBy('tipo_est')->orderByDesc('total')->get(),
                'por_complejidad' => (clone $base)->select('complejidad', DB::raw('count(*) as total'))
                    ->groupBy('complejidad')->orderBy('complejidad')->get(),
                'por_departamento'=> (clone $base)->select('departamento', DB::raw('count(*) as total'))
                    ->groupBy('departamento')->orderByDesc('total')->get(),
                'por_prestador'   => (clone $base)->select('prestador', DB::raw('count(*) as total'))
                    ->groupBy('prestador')->orderByDesc('total')->get(),
                'con_evaluacion'  => (clone $base)->whereHas('evaluaciones')->count(),
                'sin_evaluacion'  => (clone $base)->whereDoesntHave('evaluaciones')->count(),
                'hospitalarios'   => (clone $base)->hospitalarios()->count(),
                'con_internacion' => (clone $base)->conInternacion()->count(),
            ],
        ]);
    }

    /**
     * GET /riiss/establecimientos/resolver-alias
     */
    public function resolverAlias(Request $request): JsonResponse
    {
        $request->validate(['nombre' => 'required|string']);

        $est = Homologacion::resolverAlias($request->nombre);
        if (!$est) {
            $est = Establecimiento::whereRaw('LOWER(nombre_oficial) LIKE ?', ['%' . strtolower($request->nombre) . '%'])->first();
        }

        return response()->json([
            'ok'        => true,
            'data'      => $est ? $est->toResumenArray() : null,
            'encontrado'=> !is_null($est),
        ]);
    }

    /**
     * POST /riiss/establecimientos/recalcular-derivados
     */
    public function recalcular(): JsonResponse
    {
        $count = 0;
        Establecimiento::activos()->chunk(100, function ($items) use (&$count) {
            foreach ($items as $est) {
                $est->recalcularCamposDerivados();
                $count++;
            }
        });

        return response()->json(['ok' => true, 'message' => "Se recalcularon {$count} establecimientos"]);
    }
}
