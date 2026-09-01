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
                    if (!$row->peiProfile) {
                        return '<span class="text-muted small">Sin Acción PEI</span>';
                    }
                    $cleanName = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode($row->peiProfile->name, ENT_QUOTES, 'UTF-8'))));
                    return '<span class="badge badge-info text-wrap text-left font-weight-bold p-1" style="white-space: normal !important; line-height: 1.3; max-width: 260px; display: inline-block;"><i class="fas fa-bullseye mr-1"></i>' . e(Str::limit($cleanName, 90)) . '</span>';
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
                    $btn = '<a href="' . route('pei.procesos.show', $row->id) . '" class="btn btn-sm btn-primary shadow-sm mr-1" title="Ver Flujograma & Diagnóstico"><i class="fas fa-project-diagram mr-1"></i>Flujograma</a>';
                    $btn .= '<button type="button" onclick="editarRelevamiento(\'' . $row->id . '\')" class="btn btn-sm btn-warning font-weight-bold shadow-sm mr-1" title="Editar Relevamiento"><i class="fas fa-edit"></i></button>';
                    $btn .= '<a href="' . route('pei.procesos.exportPdf', $row->id) . '" target="_blank" class="btn btn-sm btn-danger shadow-sm mr-1" title="Imprimir Reporte PDF"><i class="fas fa-file-pdf"></i></a>';
                    $btn .= '<button type="button" onclick="eliminarRelevamiento(\'' . $row->id . '\', \'' . addslashes($row->nombre) . '\')" class="btn btn-sm btn-outline-danger shadow-sm" title="Eliminar Relevamiento"><i class="fas fa-trash-alt"></i></button>';
                    return $btn;
                })
                ->rawColumns(['servicio_label', 'pei_label', 'responsables_badges', 'lead_time_formatted', 'eficiencia_badge', 'cuellos_count', 'actions'])
                ->make(true);
        }

        return redirect()->route('globales.dashboard', ['tab' => 'procesos']);
    }

    public function store(Request $request, RelevamientoAiAnalysisService $aiService)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contexto_motivo' => 'nullable|string',
            'pei_profile_id' => ['nullable', \Illuminate\Validation\Rule::exists(PeiProfile::class, 'id')],
            'organigrama_id' => 'nullable|exists:organigramas,id',
            'fecha_relevamiento' => 'nullable|date',
            'responsables' => 'nullable|array',
            'responsables.*' => 'exists:users,id',
        ]);

        $organigramaId = $request->organigrama_id;
        if (empty($organigramaId) && !empty($request->pei_profile_id)) {
            $pei = PeiProfile::find($request->pei_profile_id);
            if ($pei) {
                $organigramaId = $pei->effective_dependency_id;
            }
        }
        
        $extParticipantes = [];
        if ($request->has('participantes_externos_nombres') && is_array($request->participantes_externos_nombres)) {
            foreach ($request->participantes_externos_nombres as $idx => $nombre) {
                if (!empty(trim($nombre))) {
                    $extParticipantes[] = [
                        'id' => 'ext_' . time() . '_' . $idx,
                        'nombre' => trim($nombre),
                        'cargo' => $request->participantes_externos_cargos[$idx] ?? 'Funcionario / Interventor',
                        'dependencia' => $request->participantes_externos_dependencias[$idx] ?? '',
                    ];
                }
            }
        }

        $proceso = RelevamientoProceso::create([
            'nombre' => $request->nombre,
            'contexto_motivo' => $request->contexto_motivo,
            'pei_profile_id' => $request->pei_profile_id,
            'organigrama_id' => $organigramaId,
            'tipo_relevamiento' => 'circuito_paciente',
            'estado' => 'en_relevamiento',
            'fecha_relevamiento' => $request->fecha_relevamiento ?: now(),
            'objetivo' => $request->objetivo,
            'participantes_externos' => $extParticipantes,
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
        
        // Delimitar las dependencias al organigrama de la Corporación / Dependencia del PEI o servicio
        $organigramas = Organigrama::orderBy('dependency')->get();
        $targetOrgId = $proceso->organigrama_id ?: ($proceso->peiProfile ? $proceso->peiProfile->effective_dependency_id : null);
        if ($targetOrgId) {
            $mainOrg = Organigrama::find($targetOrgId);
            if ($mainOrg && method_exists($mainOrg, 'descendantsAndSelf')) {
                $scopedOrg = $mainOrg->descendantsAndSelf()->orderBy('dependency')->get();
                if ($scopedOrg->isNotEmpty()) {
                    $organigramas = $scopedOrg;
                }
            }
        }

        $users = User::orderBy('name')->get();
        $mermaidGraph = $this->generarMermaidGraph($proceso);

        return view('admin.planificacion.procesos.show', compact('proceso', 'organigramas', 'users', 'mermaidGraph'));
    }

    public function edit($id)
    {
        $proceso = RelevamientoProceso::with(['responsables', 'organigrama', 'peiProfile'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'proceso' => [
                'id' => $proceso->id,
                'nombre' => $proceso->nombre,
                'organigrama_id' => $proceso->organigrama_id,
                'pei_profile_id' => $proceso->pei_profile_id,
                'contexto_motivo' => $proceso->contexto_motivo,
                'fecha_relevamiento' => $proceso->fecha_relevamiento ? $proceso->fecha_relevamiento->format('Y-m-d') : null,
                'responsables' => $proceso->responsables->pluck('id')->toArray(),
                'participantes_externos' => $proceso->participantes_externos ?: [],
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $proceso = RelevamientoProceso::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'contexto_motivo' => 'nullable|string',
            'pei_profile_id' => ['nullable', \Illuminate\Validation\Rule::exists(PeiProfile::class, 'id')],
            'organigrama_id' => 'nullable|exists:organigramas,id',
            'fecha_relevamiento' => 'nullable|date',
            'responsables' => 'nullable|array',
            'responsables.*' => 'exists:users,id',
        ]);

        $organigramaId = $request->organigrama_id;
        if (empty($organigramaId) && !empty($request->pei_profile_id)) {
            $pei = PeiProfile::find($request->pei_profile_id);
            if ($pei) {
                $organigramaId = $pei->effective_dependency_id;
            }
        }

        $existingExtMap = [];
        if (is_array($proceso->participantes_externos)) {
            foreach ($proceso->participantes_externos as $ext) {
                if (isset($ext['id'])) {
                    $existingExtMap[$ext['id']] = $ext;
                }
            }
        }

        $extParticipantes = [];
        if ($request->has('participantes_externos_nombres') && is_array($request->participantes_externos_nombres)) {
            foreach ($request->participantes_externos_nombres as $idx => $nombre) {
                if (!empty(trim($nombre))) {
                    $extId = $request->participantes_externos_ids[$idx] ?? ('ext_' . time() . '_' . $idx);
                    $existingData = $existingExtMap[$extId] ?? [];
                    
                    $extParticipantes[] = [
                        'id' => $extId,
                        'nombre' => trim($nombre),
                        'cargo' => $request->participantes_externos_cargos[$idx] ?? 'Funcionario / Interventor',
                        'dependencia' => $request->participantes_externos_dependencias[$idx] ?? '',
                        'firma_digital' => $existingData['firma_digital'] ?? null,
                        'firmado_at' => $existingData['firmado_at'] ?? null,
                        'observaciones_firma' => $existingData['observaciones_firma'] ?? null,
                    ];
                }
            }
        }

        $proceso->update([
            'nombre' => $request->nombre,
            'contexto_motivo' => $request->contexto_motivo,
            'pei_profile_id' => $request->pei_profile_id,
            'organigrama_id' => $organigramaId,
            'fecha_relevamiento' => $request->fecha_relevamiento ?: $proceso->fecha_relevamiento,
            'participantes_externos' => $extParticipantes,
        ]);

        if ($request->has('responsables')) {
            $proceso->responsables()->sync($request->responsables);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Estudio de relevamiento actualizado exitosamente.',
                'proceso' => $proceso,
                'redirect' => route('pei.procesos.show', $proceso->id)
            ]);
        }

        return redirect()->back()->with('success', 'Estudio de relevamiento actualizado exitosamente.');
    }

    public function portalDoc(Request $request)
    {
        $procesos = RelevamientoProceso::with(['peiProfile', 'organigrama', 'responsables', 'pasos'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.planificacion.procesos.portal_doc', compact('procesos'));
    }

    public function storePaso(Request $request, $procesoId, RelevamientoAiAnalysisService $aiService)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'required|integer',
            'area_dependencia_custom' => 'required|string|max:255',
            'tiempo_atencion_min' => 'required|integer|min:0',
            'tiempo_espera_min' => 'required|integer|min:0',
            'tiempo_traslado_min' => 'required|integer|min:0',
            'criticidad' => 'required|string',
        ]);

        $proceso = RelevamientoProceso::findOrFail($procesoId);

        RelevamientoPaso::create([
            'relevamiento_proceso_id' => $proceso->id,
            'nombre' => $request->nombre,
            'orden' => $request->orden,
            'area_dependencia_custom' => $request->area_dependencia_custom,
            'organigrama_id' => $request->organigrama_id,
            'rol_responsable' => $request->rol_responsable,
            'tiempo_atencion_min' => $request->tiempo_atencion_min,
            'tiempo_espera_min' => $request->tiempo_espera_min,
            'tiempo_traslado_min' => $request->tiempo_traslado_min,
            'herramienta_sistema' => $request->herramienta_sistema,
            'es_cuello_botella' => $request->has('es_cuello_botella') ? true : false,
            'criticidad' => $request->criticidad,
            'causa_raiz' => $request->causa_raiz,
            'observacion_campo' => $request->observacion_campo,
            'propuesta_mejora' => $request->propuesta_mejora,
        ]);

        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json($this->buildProcesoPayload($proceso, 'Estación del circuito registrada con éxito.'));
    }

    public function updatePaso(Request $request, $procesoId, $pasoId, RelevamientoAiAnalysisService $aiService)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'orden' => 'required|integer',
            'area_dependencia_custom' => 'required|string|max:255',
            'tiempo_atencion_min' => 'required|integer|min:0',
            'tiempo_espera_min' => 'required|integer|min:0',
            'tiempo_traslado_min' => 'required|integer|min:0',
            'criticidad' => 'required|string',
        ]);

        $paso = RelevamientoPaso::where('relevamiento_proceso_id', $procesoId)->findOrFail($pasoId);

        $paso->update([
            'nombre' => $request->nombre,
            'orden' => $request->orden,
            'area_dependencia_custom' => $request->area_dependencia_custom,
            'organigrama_id' => $request->organigrama_id,
            'rol_responsable' => $request->rol_responsable,
            'tiempo_atencion_min' => $request->tiempo_atencion_min,
            'tiempo_espera_min' => $request->tiempo_espera_min,
            'tiempo_traslado_min' => $request->tiempo_traslado_min,
            'herramienta_sistema' => $request->herramienta_sistema,
            'es_cuello_botella' => $request->has('es_cuello_botella') ? true : false,
            'criticidad' => $request->criticidad,
            'causa_raiz' => $request->causa_raiz,
            'observacion_campo' => $request->observacion_campo,
            'propuesta_mejora' => $request->propuesta_mejora,
        ]);

        $proceso = RelevamientoProceso::findOrFail($procesoId);
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json($this->buildProcesoPayload($proceso, 'Estación actualizada con éxito.'));
    }

    public function deletePaso($procesoId, $pasoId, RelevamientoAiAnalysisService $aiService)
    {
        $paso = RelevamientoPaso::where('relevamiento_proceso_id', $procesoId)->findOrFail($pasoId);
        $paso->delete();

        $proceso = RelevamientoProceso::findOrFail($procesoId);
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json($this->buildProcesoPayload($proceso, 'Estación eliminada correctamente.'));
    }

    public function generarIa($id, RelevamientoAiAnalysisService $aiService)
    {
        $proceso = RelevamientoProceso::findOrFail($id);
        $proceso->analisis_ia = $aiService->generarDiagnostico($proceso);
        $proceso->save();

        return response()->json($this->buildProcesoPayload($proceso, 'Análisis de Inteligencia Artificial actualizado.'));
    }

    public function firmar(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'firma' => 'required|string',
            'observaciones' => 'nullable|string|max:255',
        ]);

        $proceso = RelevamientoProceso::findOrFail($id);

        if (str_starts_with((string)$request->user_id, 'ext_')) {
            $extList = is_array($proceso->participantes_externos) ? $proceso->participantes_externos : [];
            foreach ($extList as &$ext) {
                if (isset($ext['id']) && $ext['id'] === $request->user_id) {
                    $ext['firma_digital'] = $request->firma;
                    $ext['firmado_at'] = now()->format('Y-m-d H:i:s');
                    $ext['observaciones_firma'] = $request->observaciones;
                    break;
                }
            }
            $proceso->participantes_externos = $extList;
            $proceso->save();
        } else {
            $proceso->responsables()->updateExistingPivot($request->user_id, [
                'firma_digital' => $request->firma,
                'firmado_at' => now(),
                'observaciones_firma' => $request->observaciones,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Firma digital sellada y registrada correctamente.',
        ]);
    }

    private function buildProcesoPayload(RelevamientoProceso $proceso, string $message): array
    {
        $proceso->refresh();
        $proceso->load(['pasos' => function($q) {
            $q->orderBy('orden', 'asc');
        }, 'pasos.organigrama']);

        $mermaidGraph = $this->generarMermaidGraph($proceso);

        $pasosData = $proceso->pasos->map(function($p) {
            return [
                'id' => $p->id,
                'orden' => $p->orden,
                'nombre' => $p->nombre,
                'descripcion' => $p->descripcion,
                'area_nombre' => $p->area_nombre,
                'area_dependencia_custom' => $p->area_dependencia_custom,
                'organigrama_id' => $p->organigrama_id,
                'rol_responsable' => $p->rol_responsable ?: 'N/A',
                'tiempo_atencion_min' => $p->tiempo_atencion_min,
                'tiempo_espera_min' => $p->tiempo_espera_min,
                'tiempo_traslado_min' => $p->tiempo_traslado_min,
                'herramienta_sistema' => $p->herramienta_sistema ?: 'Manual',
                'es_cuello_botella' => (bool)$p->es_cuello_botella,
                'criticidad' => $p->criticidad,
                'causa_raiz' => $p->causa_raiz,
                'observacion_campo' => $p->observacion_campo,
                'propuesta_mejora' => $p->propuesta_mejora ?: 'Sin observaciones',
            ];
        });

        return [
            'status' => 'success',
            'message' => $message,
            'lead_time_total' => $proceso->lead_time_total,
            'lead_time_horas' => round($proceso->lead_time_total / 60, 1),
            'tiempo_atencion_total' => $proceso->tiempo_atencion_total,
            'tiempo_espera_total' => $proceso->tiempo_espera_total,
            'eficiencia' => $proceso->eficiencia,
            'conteo_cuellos_botella' => $proceso->conteo_cuellos_botella,
            'analisis_ia_html' => \Illuminate\Support\Str::markdown($proceso->analisis_ia ?: 'Generando análisis de diagnóstico...'),
            'mermaid_graph' => $mermaidGraph,
            'pasos' => $pasosData,
            'next_orden' => $proceso->pasos->count() + 1,
        ];
    }

    public function exportPdf($id)
    {
        $proceso = RelevamientoProceso::with(['peiProfile', 'organigrama', 'responsables', 'pasos.organigrama'])->findOrFail($id);

        return view('admin.planificacion.procesos.pdf_report', compact('proceso'));
    }

    public function destroy($id)
    {
        $proceso = RelevamientoProceso::findOrFail($id);
        $proceso->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Estudio de relevamiento eliminado exitosamente.'
            ]);
        }

        return redirect()->back()->with('success', 'Estudio de relevamiento eliminado exitosamente.');
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
