<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\Evaluacion;
use App\Models\Riiss\Asignacion;
use App\Models\HomeConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RiissCenterController extends Controller
{
    /**
     * GET /riiss
     * Centro de Control Unificado RIISS.
     */
    public function index()
    {
        $config = HomeConfiguration::first();
        $targetPeiId = $config?->pei_profile_id ?: '766eb883-fdd0-4723-8f75-cf689aa8f0fa';

        return view('admin.riiss.index', compact('targetPeiId'));
    }

    /**
     * GET /riiss/configuracion
     * Espacio unificado para formularios dinámicos y criterios de complejidad.
     */
    public function configuracion()
    {
        $user = auth()->user();
        if ($user && $user->hasAnyRole(['Analista - RIISS', 'Analista RIISS']) && !$user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS'])) {
            return redirect()->route('riiss.index')
                ->with('warning', 'La configuración de formularios y complejidad está reservada a la Coordinación y Administración.');
        }

        return view('admin.riiss.configuracion');
    }

    /**
     * GET /riiss/datos-unificados
     * DataTable de Establecimientos, Asignaciones y Evaluaciones.
     */
    public function datosUnificados(Request $request)
    {
        $query = Establecimiento::query()->with([
            'asignaciones' => fn($q) => $q->with(['evaluador', 'peiProfile'])->latest(),
            'evaluaciones' => fn($q) => $q->latest()
        ])->withCount(['medicamentos', 'especialidades']);

        $buscar = trim($request->get('buscar', ''));

        if ($tipologia = trim($request->get('tipologia', ''))) {
            $query->where('tipologia_clasificacion', $tipologia);
        }

        if ($evaluadorId = $request->get('evaluador_id')) {
            $query->whereHas('asignaciones', function($q) use ($evaluadorId) {
                $q->where('evaluador_id', $evaluadorId);
            });
        }

        if ($conAsignacion = $request->get('con_asignacion')) {
            if ($conAsignacion === 'con') {
                $query->whereHas('asignaciones');
            } elseif ($conAsignacion === 'sin') {
                $query->whereDoesntHave('asignaciones');
            }
        }

        if ($conMedicamentos = $request->get('con_medicamentos')) {
            if ($conMedicamentos === 'con') {
                $query->whereHas('medicamentos');
            } elseif ($conMedicamentos === 'sin') {
                $query->whereDoesntHave('medicamentos');
            }
        }

        return DataTables::of($query)
            // Aplicar filtrado manual para la búsqueda global para evitar que Yajra
            // construya cláusulas usando nombres de columna enviados por el cliente
            // que podrían no existir (p. ej. `depto_nc`). Esto fuerza el uso de
            // solo las columnas que sabemos existen en la tabla `establecimientos`.
            ->filter(function ($q) use ($buscar) {
                if ($buscar !== '') {
                    $q->where(function($sq) use ($buscar) {
                        $sq->where('nombre_oficial', 'ILIKE', "%{$buscar}%")
                           ->orWhere('id_establecimiento', 'ILIKE', "%{$buscar}%")
                           ->orWhere('departamento', 'ILIKE', "%{$buscar}%");
                    });
                }
            }, true)
            ->addIndexColumn()
            ->addColumn('establecimiento', function ($est) {
                $medsCount = $est->medicamentos_count ?? 0;
                $espCount = $est->especialidades_count ?? 0;

                $medsBadge = '';
                if ($medsCount > 0) {
                    $medsBadge = ' <span class="badge badge-pill badge-success ml-1 shadow-sm" style="font-size: 0.72rem; font-weight: 600; vertical-align: middle;" title="' . $espCount . ' especialidades • ' . $medsCount . ' medicamentos asignados"><i class="fa fa-pills mr-1"></i>' . $medsCount . ' meds</span>';
                } else {
                    $medsBadge = ' <span class="badge badge-pill badge-light border text-muted ml-1" style="font-size: 0.70rem; vertical-align: middle;" title="Sin medicamentos registrados"><i class="fa fa-pills mr-1"></i>0 meds</span>';
                }

                return '<strong>' . e($est->nombre_oficial) . '</strong>' . $medsBadge . '<br><small class="text-muted">ID: ' . e($est->id_establecimiento) . '</small>';
            })
            ->addColumn('tipologia_ubicacion', function ($est) {
                $dept = $est->departamento ?: '—';
                return '<small>' . e($est->tipologia_clasificacion ?? '—') . '</small><br><small class="text-muted">' . e($dept) . '</small>';
            })
            ->addColumn('evaluador', function ($est) {
                $asig = $est->asignaciones->first();
                if (!$asig || !$asig->evaluador) return '<small class="text-muted">—</small>';
                return '<small>' . e($asig->evaluador->name) . '</small><br><small class="text-muted">' . e($asig->evaluador->email) . '</small>';
            })
            ->addColumn('pei_plan', function ($est) {
                $asig = $est->asignaciones->first();
                if (!$asig || !$asig->peiProfile) return '<small class="text-muted">—</small>';
                return '<small class="text-info font-weight-bold"><i class="fa fa-bullseye mr-1"></i>' . e(strip_tags($asig->peiProfile->name)) . '</small>';
            })
            ->addColumn('fecha_limite', function ($est) {
                $asig = $est->asignaciones->first();
                return '<small>' . ($asig?->fecha_limite?->format('d/m/Y') ?? '—') . '</small>';
            })
            ->addColumn('estado_supervision', function ($est) {
                $asig = $est->asignaciones->first();
                $st = $asig?->estado ?? 'sin_asignar';
                $lbl = str_replace('_', ' ', $st);
                return '<span class="estado-badge estado-' . e($st) . '">' . e($lbl) . '</span>';
            })
            ->addColumn('cumplimiento', function ($est) {
                $eval = $est->evaluaciones->first();
                $pct = $eval?->porcentaje_cumplimiento ? (float)$eval->porcentaje_cumplimiento : 0;
                return '<div style="height:5px;background:#e5e7eb;border-radius:3px;overflow:hidden;width:70px"><div style="height:100%;width:' . $pct . '%;background:#e91e63"></div></div><small>' . $pct . '%</small>';
            })
            ->addColumn('acciones', function ($est) {
                $user = auth()->user();
                $canManage = $user && $user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS']);

                $asig = $est->asignaciones->first();
                $eval = $est->evaluaciones->first();
                $asigIdStr = $asig ? $asig->id : 'null';
                $evalIdStr = $eval ? $eval->id : 'null';
                $nomEsc = addslashes($est->nombre_oficial);

                // Determinar si el usuario actual es el evaluador asignado
                $isAssignedEvaluator = $asig && $user && ($asig->evaluador_id == $user->id);

                $btn = '<div class="dt-body-center">';
                if ($canManage) {
                    $btn .= '<button class="circle-btn ' . ($asig ? 'circle-btn-warning' : 'circle-btn-info') . ' btn-sm" onclick="abrirModalAsignacion(' . $asigIdStr . ', \'' . e($est->id_establecimiento) . '\', \'' . e($nomEsc) . '\')" title="' . ($asig ? 'Editar asignación' : 'Nueva Asignación') . '"><i class="fa ' . ($asig ? 'fa-pencil-alt' : 'fa-plus') . '"></i></button>';
                    $btn .= '<button class="circle-btn circle-btn-info btn-sm" onclick="abrirEditarEstablecimiento(\'' . e($est->id_establecimiento) . '\')" title="Editar establecimiento"><i class="fa fa-edit"></i></button>';
                }

                if ($eval) {
                    if ($canManage || $isAssignedEvaluator) {
                        $btn .= '<a href="/riiss/evaluaciones/nueva/' . e($est->id_establecimiento) . '?evaluacion=' . $eval->id . '" class="circle-btn circle-btn-primary btn-sm" title="Continuar evaluación"><i class="fa fa-play"></i></a>';
                    }
                    $btn .= '<a href="/riiss/evaluaciones/' . $eval->id . '" class="circle-btn circle-btn-success btn-sm" title="Ver evaluación"><i class="fa fa-eye"></i></a>';
                } else {
                    if ($canManage || $isAssignedEvaluator) {
                        $btn .= '<a href="/riiss/evaluaciones/nueva/' . e($est->id_establecimiento) . '" class="circle-btn circle-btn-primary btn-sm" title="Nueva evaluación"><i class="fa fa-play"></i></a>';
                    }
                }
                $btn .= '</div>';

                return $btn;
            })
            ->rawColumns(['establecimiento', 'tipologia_ubicacion', 'evaluador', 'pei_plan', 'fecha_limite', 'estado_supervision', 'cumplimiento', 'acciones'])
            ->make(true);
    }

    public function buscarEstablecimientos(Request $request)
    {
        $search = trim($request->get('q', ''));
        $query = Establecimiento::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre_oficial', 'ILIKE', "%{$search}%")
                  ->orWhere('id_establecimiento', 'ILIKE', "%{$search}%")
                  ->orWhere('departamento', 'ILIKE', "%{$search}%");
            });
        }

        $establecimientos = $query->orderBy('nombre_oficial')->limit(20)->get();

        $results = $establecimientos->map(function($est) {
            return [
                'id' => $est->id_establecimiento,
                'text' => $est->nombre_oficial . ' (' . ($est->departamento ?: 'N/A') . ')',
            ];
        });

        return response()->json(['results' => $results]);
    }
}
