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

        $complejidadTipos = \App\Models\Riiss\ComplejidadTipo::orderBy('grado')->get();

        return view('admin.riiss.establecimientos.index', compact('departamentos', 'tipos', 'complejidades', 'stats', 'complejidadTipos'));
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
        $user = auth()->user();
        if ($user && $user->hasAnyRole(['Analista - RIISS', 'Analista RIISS']) && !$user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS'])) {
            return response()->json(['ok' => false, 'message' => 'No tienes permisos para modificar datos del establecimiento.'], 403);
        }

        $est = Establecimiento::where('id_establecimiento', $id)->firstOrFail();

        $validated = $request->validate([
            'nombre_oficial'          => 'sometimes|string|max:300',
            'complejidad_tipo_id'     => 'sometimes|exists:complejidad_tipos,id',
            'tipologia_clasificacion' => 'sometimes|string|max:100',
            'departamento'            => 'sometimes|string|max:100',
            'microred'                => 'nullable|string|max:100',
            'prestador'               => 'nullable|string|max:100',
            'tiene_internacion'       => 'nullable|boolean',
            'tiene_quirofano_req'     => 'nullable|boolean',
            'tiene_uti_req'           => 'nullable|boolean',
            'tiene_urgencias_req'     => 'nullable|boolean',
            'observacion'             => 'nullable|string|max:1000',
            
            // Nuevos campos
            'latitude'                => 'nullable|numeric',
            'longitude'               => 'nullable|numeric',
            'condicion_inmueble'      => 'nullable|string|in:CONVENIO,ALQUILADO,PROPIO',
            
            'superficie_terreno'      => 'nullable|numeric',
            'superficie_construida'   => 'nullable|numeric',
            'plano_file'              => 'nullable|file|mimes:pdf|max:10240', // Hasta 10MB
            
            'nro_llamado'             => 'nullable|string|max:100',
            'nro_contrato_alquiler'   => 'nullable|string|max:100',
            'propietario'             => 'nullable|string|max:150',
            'vigencia_desde'          => 'nullable|date',
            'vigencia_hasta'          => 'nullable|date',
            'canon_mensual'           => 'nullable|numeric',
            'fecha_pago_alquiler'     => 'nullable|string|max:50',
            
            'nro_resolucion_convenio' => 'nullable|string|max:100',
            'vigencia_convenio_desde' => 'nullable|date',
            'vigencia_convenio_hasta' => 'nullable|date',
            'descripcion_convenio'    => 'nullable|string',
            'locales_convenio'        => 'nullable|string',
            'archivo_convenio_file'   => 'nullable|file|mimes:pdf|max:10240',
            
            // Programas de Patologías Crónicas (RCA 007-043/2022)
            'habilita_farmacia_cronicos'        => 'nullable|boolean',
            'habilita_empadronamiento_cronicos' => 'nullable|boolean',

            'contratos'               => 'nullable|array',
            'contratos.*.tipo_contrato' => 'required_with:contratos|string|in:AMPLIACION,MANTENIMIENTO',
            'contratos.*.nro_contrato'  => 'nullable|string|max:100',
            'contratos.*.descripcion'   => 'nullable|string',
            'contratos.*.costo_total'   => 'nullable|numeric',
            'contratos.*.porcentaje_avance' => 'nullable|integer',
        ]);

        if ($request->has('habilita_farmacia_cronicos')) {
            $validated['habilita_farmacia_cronicos'] = $request->boolean('habilita_farmacia_cronicos');
        }
        if ($request->has('habilita_empadronamiento_cronicos')) {
            $validated['habilita_empadronamiento_cronicos'] = $request->boolean('habilita_empadronamiento_cronicos');
        }

        if ($request->hasFile('plano_file')) {
            $path = $request->file('plano_file')->store('planos', 'public');
            $validated['plano_url'] = $path;
        }

        if ($request->hasFile('archivo_convenio_file')) {
            $pathConv = $request->file('archivo_convenio_file')->store('convenios', 'public');
            $validated['archivo_convenio_url'] = $pathConv;
        }

        $est->update($validated);

        if ($request->has('contratos')) {
            $est->inmuebleContratos()->delete();
            if (is_array($request->contratos)) {
                foreach ($request->contratos as $contrato) {
                    $est->inmuebleContratos()->create($contrato);
                }
            }
        }

        // Si cambió la complejidad, recalcular derivados
        if (isset($validated['complejidad_tipo_id'])) {
            $est->refresh();
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
            ->with(['ultimaEvaluacion', 'homologaciones', 'inmuebleContratos', 'especialidades', 'medicamentos'])
            ->firstOrFail();

        $especialidadesMap = $est->especialidades->keyBy('id');
        $carteraServicios = [];

        foreach ($est->medicamentos as $med) {
            $espId = $med->pivot->especialidad_id;
            if (!isset($carteraServicios[$espId])) {
                $carteraServicios[$espId] = [
                    'id' => $espId,
                    'nombre' => $especialidadesMap->has($espId) ? $especialidadesMap->get($espId)->nombre : 'Otra (Programas / Crónicos)',
                    'medicamentos' => []
                ];
            }
            $carteraServicios[$espId]['medicamentos'][] = [
                'id' => $med->id,
                'codigo' => $med->codigo,
                'nombre' => $med->nombre,
            ];
        }

        foreach ($est->especialidades as $esp) {
            if (!isset($carteraServicios[$esp->id])) {
                $carteraServicios[$esp->id] = [
                    'id' => $esp->id,
                    'nombre' => $esp->nombre,
                    'medicamentos' => []
                ];
            }
        }

        // Consolidar medicamentos únicos con sus especialidades vinculadas (sin duplicados)
        $medicamentosConsolidados = [];
        foreach ($est->medicamentos as $med) {
            $espId = $med->pivot->especialidad_id;
            $espNombre = $especialidadesMap->has($espId) ? $especialidadesMap->get($espId)->nombre : 'Pacientes Crónicos / Otras Áreas';

            $medKey = $med->codigo ? $med->codigo : ('ID_' . $med->id);
            if (!isset($medicamentosConsolidados[$medKey])) {
                $medicamentosConsolidados[$medKey] = [
                    'id'             => $med->id,
                    'codigo'         => $med->codigo ?: 'S/C',
                    'nombre'         => $med->nombre,
                    'especialidades' => []
                ];
            }
            if (!in_array($espNombre, $medicamentosConsolidados[$medKey]['especialidades'])) {
                $medicamentosConsolidados[$medKey]['especialidades'][] = $espNombre;
            }
        }

        $medicamentosConsolidados = array_values($medicamentosConsolidados);
        usort($medicamentosConsolidados, function($a, $b) {
            return strcmp($a['nombre'], $b['nombre']);
        });

        // Ordenar alfabéticamente por nombre de especialidad
        usort($carteraServicios, function($a, $b) {
            return strcmp($a['nombre'], $b['nombre']);
        });

        return response()->json([
            'ok'                       => true,
            'data'                     => $est,
            'cartera_servicios'        => $carteraServicios,
            'medicamentos_consolidados'=> $medicamentosConsolidados,
            'cartera_requisitos'       => $this->carteraService->resumenRequisitos($est),
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

    /**
     * GET /riiss/establecimientos/{id}/medicamentos-pdf
     * Genera PDF oficial de Medicamentos (Consolidado para Auditoría o por Especialidad).
     */
    public function exportarPdfMedicamentos(string $id, Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $est = Establecimiento::where('id_establecimiento', $id)
            ->with(['especialidades', 'medicamentos'])
            ->firstOrFail();

        $especialidadesMap = $est->especialidades->keyBy('id');

        // 1. Medicamentos Consolidados Únicos (sin duplicados)
        $medicamentosConsolidados = [];
        $especialidadesMedicamentos = [];
        $totalAsignaciones = 0;

        foreach ($est->medicamentos as $med) {
            $espId = $med->pivot->especialidad_id;
            $espNombre = $especialidadesMap->has($espId) ? $especialidadesMap->get($espId)->nombre : 'Pacientes Crónicos / Otras Áreas';

            // Agrupado por especialidad
            if (!isset($especialidadesMedicamentos[$espNombre])) {
                $especialidadesMedicamentos[$espNombre] = [];
            }
            $especialidadesMedicamentos[$espNombre][] = [
                'codigo' => $med->codigo,
                'nombre' => $med->nombre,
            ];
            $totalAsignaciones++;

            // Consolidado único
            $medKey = $med->codigo ? $med->codigo : ('ID_' . $med->id);
            if (!isset($medicamentosConsolidados[$medKey])) {
                $medicamentosConsolidados[$medKey] = [
                    'id'             => $med->id,
                    'codigo'         => $med->codigo ?: 'S/C',
                    'nombre'         => $med->nombre,
                    'especialidades' => []
                ];
            }
            if (!in_array($espNombre, $medicamentosConsolidados[$medKey]['especialidades'])) {
                $medicamentosConsolidados[$medKey]['especialidades'][] = $espNombre;
            }
        }

        // Agregar especialidades vacías si las hay
        foreach ($est->especialidades as $esp) {
            if (!isset($especialidadesMedicamentos[$esp->nombre])) {
                $especialidadesMedicamentos[$esp->nombre] = [];
            }
        }

        ksort($especialidadesMedicamentos);
        uasort($medicamentosConsolidados, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));

        $tipo = $request->get('tipo', 'consolidado'); // 'consolidado' (por defecto) o 'especialidad'

        $viewName = ($tipo === 'especialidad')
            ? 'admin.riiss.establecimientos.pdf_medicamentos_especialidad'
            : 'admin.riiss.establecimientos.pdf_medicamentos_consolidado';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, [
            'est'                        => $est,
            'medicamentosConsolidados'   => $medicamentosConsolidados,
            'especialidadesMedicamentos' => $especialidadesMedicamentos,
            'totalMedicamentosUnicos'    => count($medicamentosConsolidados),
            'totalAsignaciones'          => $totalAsignaciones,
            'totalEspecialidades'        => count($especialidadesMedicamentos),
            'fecha'                      => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $suffix = ($tipo === 'especialidad') ? '_Por_Especialidad' : '_Auditoria_Farmacia';
        $filename = 'RIISS_' . \Illuminate\Support\Str::slug($est->nombre_oficial) . $suffix . '.pdf';
        return $pdf->stream($filename);
    }
}
