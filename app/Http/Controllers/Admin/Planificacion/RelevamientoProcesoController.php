<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planificacion\RelevamientoProceso;
use App\Models\Planificacion\RelevamientoPaso;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Organigrama;
use App\Models\User;
use App\Services\RelevamientoAiAnalysisService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class RelevamientoProcesoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = RelevamientoProceso::with(['peiProfile', 'organigrama', 'responsables', 'pasos']);

            return DataTables::of($query)
                ->addColumn('servicio_label', function ($row) {
                    return $row->organigrama ? $row->organigrama->nombre : '<span class="text-muted">No asignado</span>';
                })
                ->addColumn('pei_label', function ($row) {
                    return $row->peiProfile ? Str::limit($row->peiProfile->name, 40) : '<span class="text-muted">Sin Acción PEI</span>';
                })
                ->addColumn('responsables_badges', function ($row) {
                    $html = '';
                    foreach ($row->responsables as $resp) {
                        $html .= '<span class="badge badge-info mr-1 mb-1" title="' . e($resp->email) . '"><i class="fas fa-user-check mr-1"></i>' . e($resp->name) . '</span>';
                    }
                    return $html ?: '<span class="badge badge-secondary">Sin responsables</span>';
                })
                ->addColumn('lead_time_formatted', function ($row) {
                    return '<strong>' . $row->lead_time_total . ' min</strong> (' . round($row->lead_time_total / 60, 1) . 'h)';
                })
                ->addColumn('eficiencia_badge', function ($row) {
                    $ef = $row->eficiencia;
                    $class = $ef >= 70 ? 'badge-success' : ($ef >= 50 ? 'badge-warning' : 'badge-danger');
                    return '<span class="badge ' . $class . ' badge-pill px-2 py-1" style="font-size:0.9rem;">' . $ef . '%</span>';
                })
                ->addColumn('cuellos_count', function ($row) {
                    $count = $row->conteo_cuellos_botella;
                    if ($count > 0) {
                        return '<span class="badge badge-danger badge-pill"><i class="fas fa-exclamation-triangle mr-1"></i>' . $count . ' crítico(s)</span>';
                    }
                    return '<span class="badge badge-success badge-pill"><i class="fas fa-check-circle mr-1"></i>0 cuellos</span>';
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a href="' . route('pei.procesos.show', $row->id) . '" class="btn btn-sm btn-primary shadow-sm mr-1" title="Ver Flujograma & Diagnóstico"><i class="fas fa-project-diagram mr-1"></i>Ver Flujograma</a>';
                    $btn .= '<a href="' . route('pei.procesos.exportPdf', $row->id) . '" target="_blank" class="btn btn-sm btn-danger shadow-sm mr-1" title="Imprimir Reporte PDF"><i class="fas fa-file-pdf"></i></a>';
                    return $btn;
                })
                ->rawColumns(['servicio_label', 'pei_label', 'responsables_badges', 'lead_time_formatted', 'eficiencia_badge', 'cuellos_count', 'actions'])
                ->make(true);
        }

        $peiProfiles = PeiProfile::whereIn('level', ['action', 'goal', 'axi'])->orderBy('name')->get();
        $organigramas = Organigrama::orderBy('nombre')->get();
        $users = User::orderBy('name')->get();

        return view('admin.planificacion.procesos.index', compact('peiProfiles', 'organigramas', 'users'));
    }

    public function store(Request $request, RelevamientoAiAnalysisService $aiService)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contexto_motivo' => 'nullable|string',
            'pei_profile_id' => 'nullable|exists:planificacion.pei_profiles,id',
            'organigrama_id' => 'nullable|exists:organigramas,id',
            'fecha_relevamiento' => 'nullable|date',
            'responsables' => 'nullable|array',
            'responsables.*' => 'exists:users,id',
        ]);

        $proceso = RelevamientoProceso::create([
            'nombre' => $request->nombre,
            'contexto_motivo' => $request->contexto_motivo,
            'pei_profile_id' => $request->pei_profile_id,
            'organigrama_id' => $request->organigrama_id,
            'tipo_relevamiento' => 'circuito_paciente',
            'estado' => 'en_relevamiento',
            'fecha_relevamiento' => $request->fecha_relevamiento ?: now(),
            'objetivo' => $request->objetivo,
            'created_by' => auth()->id(),
        ]);

        if ($request->has('responsables')) {
            $proceso->responsables()->sync($request->responsables);
        }

        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Relevamiento registrado con éxito.',
            'redirect' => route('pei.procesos.show', $proceso->id)
        ]);
    }

    public function show($id)
    {
        $proceso = RelevamientoProceso::with(['peiProfile', 'organigrama', 'responsables', 'pasos.organigrama'])->findOrFail($id);
        $organigramas = Organigrama::orderBy('nombre')->get();
        $users = User::orderBy('name')->get();

        $mermaidGraph = $this->generarMermaidGraph($proceso);

        return view('admin.planificacion.procesos.show', compact('proceso', 'organigramas', 'users', 'mermaidGraph'));
    }

    public function portalDoc(Request $request)
    {
        $procesos = RelevamientoProceso::with(['peiProfile', 'organigrama', 'responsables', 'pasos'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.planificacion.procesos.portal_doc', compact('procesos'));
    }

    public function storePaso(Request $request, $procesoId, RelevamientoAiAnalysisService $aiService)
    {
        $proceso = RelevamientoProceso::findOrFail($procesoId);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'required|integer|min:1',
            'organigrama_id' => 'nullable|exists:organigramas,id',
            'rol_responsable' => 'nullable|string',
            'tiempo_atencion_min' => 'required|integer|min:0',
            'tiempo_espera_min' => 'required|integer|min:0',
            'tiempo_traslado_min' => 'required|integer|min:0',
            'herramienta_sistema' => 'nullable|string',
            'es_cuello_botella' => 'nullable|boolean',
            'criticidad' => 'required|string|in:baja,media,alta,critica',
            'causa_raiz' => 'nullable|string',
            'observacion_campo' => 'nullable|string',
            'propuesta_mejora' => 'nullable|string',
        ]);

        if ($request->paso_id) {
            $paso = RelevamientoPaso::where('relevamiento_proceso_id', $procesoId)->findOrFail($request->paso_id);
            $paso->update([
                'orden' => $request->orden,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'organigrama_id' => $request->organigrama_id,
                'rol_responsable' => $request->rol_responsable,
                'tiempo_atencion_min' => $request->tiempo_atencion_min,
                'tiempo_espera_min' => $request->tiempo_espera_min,
                'tiempo_traslado_min' => $request->tiempo_traslado_min,
                'herramienta_sistema' => $request->herramienta_sistema,
                'es_cuello_botella' => $request->has('es_cuello_botella') ? (bool)$request->es_cuello_botella : false,
                'criticidad' => $request->criticidad,
                'causa_raiz' => $request->causa_raiz,
                'observacion_campo' => $request->observacion_campo,
                'propuesta_mejora' => $request->propuesta_mejora,
            ]);
        } else {
            RelevamientoPaso::create([
                'relevamiento_proceso_id' => $procesoId,
                'orden' => $request->orden,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'organigrama_id' => $request->organigrama_id,
                'rol_responsable' => $request->rol_responsable,
                'tiempo_atencion_min' => $request->tiempo_atencion_min,
                'tiempo_espera_min' => $request->tiempo_espera_min,
                'tiempo_traslado_min' => $request->tiempo_traslado_min,
                'herramienta_sistema' => $request->herramienta_sistema,
                'es_cuello_botella' => $request->has('es_cuello_botella') ? (bool)$request->es_cuello_botella : false,
                'criticidad' => $request->criticidad,
                'causa_raiz' => $request->causa_raiz,
                'observacion_campo' => $request->observacion_campo,
                'propuesta_mejora' => $request->propuesta_mejora,
            ]);
        }

        $proceso->refresh();
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json(['status' => 'success', 'message' => 'Estación de atención guardada correctamente.']);
    }

    public function destroyPaso($procesoId, $pasoId, RelevamientoAiAnalysisService $aiService)
    {
        $paso = RelevamientoPaso::where('relevamiento_proceso_id', $procesoId)->findOrFail($pasoId);
        $paso->delete();

        $proceso = RelevamientoProceso::findOrFail($procesoId);
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json(['status' => 'success', 'message' => 'Estación eliminada.']);
    }

    public function generarIa($id, RelevamientoAiAnalysisService $aiService)
    {
        $proceso = RelevamientoProceso::findOrFail($id);
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json(['status' => 'success', 'analisis_ia' => $proceso->analisis_ia]);
    }

    public function exportPdf($id)
    {
        $proceso = RelevamientoProceso::with(['peiProfile', 'organigrama', 'responsables', 'pasos.organigrama'])->findOrFail($id);

        return view('admin.planificacion.procesos.pdf_report', compact('proceso'));
    }

    private function generarMermaidGraph(RelevamientoProceso $proceso): string
    {
        $pasos = $proceso->pasos;
        if ($pasos->isEmpty()) {
            return "graph TD\n    empty[\"⚠️ No hay estaciones registradas aún en el circuito\"]";
        }

        $lines = ["graph TD"];
        $lines[] = "    classDef normal fill:#e8f5e9,stroke:#2e7d32,stroke-width:2px,color:#1b5e20;";
        $lines[] = "    classDef warning fill:#fffde7,stroke:#fbc02d,stroke-width:2px,color:#f57f17;";
        $lines[] = "    classDef danger fill:#ffebee,stroke:#c62828,stroke-width:3px,color:#b71c1c;";

        $prevNodeId = null;
        foreach ($pasos as $p) {
            $nodeId = "Paso_" . str_replace('-', '_', $p->id);
            $areaName = $p->organigrama ? $p->organigrama->nombre : 'Área General';
            $label = "<b>#{$p->orden} {$p->nombre}</b><br/>📍 {$areaName}<br/>👤 {$p->rol_responsable}<br/>⏱️ Atenc: {$p->tiempo_atencion_min}m | Espera: {$p->tiempo_espera_min}m";
            
            if ($p->es_cuello_botella) {
                $label .= "<br/>🚨 <b>CUELLO DE BOTELLA</b>";
            }

            $safeLabel = str_replace('"', "'", $label);
            $lines[] = "    {$nodeId}[\"{$safeLabel}\"]";

            if ($p->es_cuello_botella) {
                $lines[] = "    class {$nodeId} danger;";
            } elseif ($p->tiempo_espera_min > 15 || $p->criticidad == 'media') {
                $lines[] = "    class {$nodeId} warning;";
            } else {
                $lines[] = "    class {$nodeId} normal;";
            }

            if ($prevNodeId) {
                $lines[] = "    {$prevNodeId} --> {$nodeId}";
            }
            $prevNodeId = $nodeId;
        }

        return implode("\n", $lines);
    }
}
