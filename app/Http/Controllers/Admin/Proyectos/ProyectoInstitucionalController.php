<?php

namespace App\Http\Controllers\Admin\Proyectos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

use App\Models\Proyectos\ProyectoInstitucional;
use App\Models\Proyectos\ProyectoChecklist;
use App\Models\Proyectos\ProyectoConsultaGerencia;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\PeiProfile;

class ProyectoInstitucionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ProyectoInstitucional::with([
                'dependenciaSolicitante', 'dependenciaEjecutora', 'analista', 'peiProfile'
            ])
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->when($request->q,      fn($q) => $q->where('nombre', 'like', '%'.$request->q.'%'))
            ->when($request->pei_profile_id, function($q) use ($request) {
                $perfil = PeiProfile::find($request->pei_profile_id);
                if ($perfil) {
                    $accionIds = $perfil->descendants()->pluck('id')->push($perfil->id);
                    $q->whereIn('pei_profile_id', $accionIds);
                }
            })
            ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('estado_badge', fn($r) =>
                    '<span class="badge '.ProyectoInstitucional::estadoBadge($r->estado).'">'.
                    ProyectoInstitucional::estadoLabel($r->estado).'</span>'
                )
                ->addColumn('pei_vinculo', fn($r) =>
                    $r->peiProfile
                        ? '<span class="badge badge-success"><i class="fa fa-link mr-1"></i>Vinculado</span>'
                        : '<span class="badge badge-warning"><i class="fa fa-unlink mr-1"></i>Sin vincular</span>'
                )
                ->addColumn('checklist_pct', fn($r) =>
                    '<div class="progress" style="height:8px;min-width:60px">'.
                    '<div class="progress-bar bg-'.($r->pctChecklist()>=100?'success':($r->pctChecklist()>=50?'warning':'danger')).'" style="width:'.$r->pctChecklist().'%"></div>'.
                    '</div><small>'.$r->pctChecklist().'%</small>'
                )
                ->addColumn('action', fn($r) =>
                    '<a href="'.route('proyectos-institucionales.show', $r->id).'" class="btn btn-info btn-circle" title="Ver detalle"><i class="fa fa-eye"></i></a> '.
                    '<a href="'.route('proyectos-institucionales.edit', $r->id).'" class="btn btn-primary btn-circle" title="Editar"><i class="fa fa-edit"></i></a>'
                )
                ->rawColumns(['estado_badge','pei_vinculo','checklist_pct','action'])
                ->make(true);
        }

        // KPIs para el dashboard
        $kpis = [
            'total'        => ProyectoInstitucional::count(),
            'activos'      => ProyectoInstitucional::activos()->count(),
            'en_ejecucion' => ProyectoInstitucional::enEjecucion()->count(),
            'sin_pei'      => ProyectoInstitucional::whereNull('pei_profile_id')->count(),
            'finalizados'  => ProyectoInstitucional::where('estado','finalizado')->count(),
        ];

        $estados = ProyectoInstitucional::ESTADOS;

        return view('admin.proyectos.institucionales.index', compact('kpis', 'estados'));
    }

    // ── Crear ─────────────────────────────────────────────────────────────────
    public function create()
    {
        $estados = ProyectoInstitucional::ESTADOS;
        return view('admin.proyectos.institucionales.create', compact('estados') + ['perfil' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        // Validación de alineación PEI — solo advertencia al crear, no bloqueo
        // El bloqueo real ocurre al intentar avanzar de estado en cambiarEstado()

        $proyecto = ProyectoInstitucional::create([
            'codigo'                    => ProyectoInstitucional::generarCodigo(),
            'nombre'                    => $request->nombre,
            'descripcion'               => $request->descripcion,
            'estado'                    => 'solicitud',
            'pei_profile_id'            => $request->pei_profile_id ?: null,
            'dependencia_solicitante_id'=> $request->dependencia_solicitante_id ?: null,
            'dependencia_ejecutora_id'  => $request->dependencia_ejecutora_id ?: null,
            'analista_id'               => $request->analista_id ?: null,
            'created_by'                => Auth::id(),
            'fecha_solicitud'           => now(),
            'presupuesto_estimado'      => $request->presupuesto_estimado,
            'fecha_fin_estimada'        => $request->fecha_fin_estimada,
        ]);

        // Crear checklist vacío
        foreach (array_keys(ProyectoInstitucional::CHECKLIST_ITEMS) as $item) {
            ProyectoChecklist::create([
                'proyecto_id' => $proyecto->id,
                'item'        => $item,
                'completado'  => false,
            ]);
        }

        // Registrar en historial
        $proyecto->historial()->create([
            'estado_anterior' => null,
            'estado_nuevo'    => 'solicitud',
            'usuario_id'      => Auth::id(),
            'comentario'      => 'Proyecto creado.',
            'fecha'           => now(),
        ]);

        return redirect()->route('proyectos-institucionales.show', $proyecto->id)
            ->with('success', "Proyecto {$proyecto->codigo} creado correctamente.");
    }

    // ── Ver detalle ───────────────────────────────────────────────────────────
    public function show($id)
    {
        $proyecto = ProyectoInstitucional::with([
            'peiProfile', 'dependenciaSolicitante', 'dependenciaEjecutora',
            'analista', 'creadoPor', 'checklist', 'consultasGerencias.gerencia',
            'historial.usuario',
        ])->findOrFail($id);

        $checklistItems = ProyectoInstitucional::CHECKLIST_ITEMS;
        $estados        = ProyectoInstitucional::ESTADOS;

        // Transiciones permitidas desde el estado actual
        $transiciones = $this->transicionesPermitidas($proyecto->estado);

        return view('admin.proyectos.institucionales.show', compact(
            'proyecto', 'checklistItems', 'estados', 'transiciones'
        ));
    }

    // ── Editar ────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $proyecto = ProyectoInstitucional::with(['checklist'])->findOrFail($id);
        $checklistItems = ProyectoInstitucional::CHECKLIST_ITEMS;
        return view('admin.proyectos.institucionales.edit', compact('proyecto', 'checklistItems'));
    }

    public function update(Request $request, $id)
    {
        $proyecto = ProyectoInstitucional::findOrFail($id);

        $proyecto->update([
            'nombre'                    => $request->nombre,
            'descripcion'               => $request->descripcion,
            'pei_profile_id'            => $request->pei_profile_id ?: $proyecto->pei_profile_id,
            'dependencia_solicitante_id'=> $request->dependencia_solicitante_id ?: null,
            'dependencia_ejecutora_id'  => $request->dependencia_ejecutora_id ?: null,
            'analista_id'               => $request->analista_id ?: null,
            'presupuesto_estimado'      => $request->presupuesto_estimado,
            'presupuesto_aprobado'      => $request->presupuesto_aprobado,
            'presupuesto_ejecutado'     => $request->presupuesto_ejecutado ?? $proyecto->presupuesto_ejecutado ?? 0,
            'fecha_fin_estimada'        => $request->fecha_fin_estimada,
            'nro_resolucion'            => $request->nro_resolucion,
            'avance_pct'                => $request->avance_pct ?? $proyecto->avance_pct,
        ]);

        return redirect()->route('proyectos-institucionales.show', $proyecto->id)
            ->with('success', 'Proyecto actualizado.');
    }

    // ── Cambiar estado ────────────────────────────────────────────────────────
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado'     => 'required|string',
            'comentario' => 'nullable|string',
        ]);

        $proyecto = ProyectoInstitucional::findOrFail($id);

        // Validar alineación PEI antes de avanzar de solicitud
        if ($proyecto->estado === 'solicitud' && !$proyecto->pei_profile_id) {
            return response()->json([
                'error' => 'Debe vincular el proyecto a una acción del PEI antes de avanzar.'
            ], 422);
        }

        // Validar checklist completo antes de homologación
        if ($request->estado === 'en_homologacion' && !$proyecto->checklistCompleto()) {
            return response()->json([
                'error' => 'El checklist no está completo ('.$proyecto->pctChecklist().'%). Complete todos los ítems antes de enviar a homologación.'
            ], 422);
        }

        // Guardar motivo de rechazo
        if (in_array($request->estado, ['rechazado_docs', 'rechazado_tecnico'])) {
            $proyecto->motivo_rechazo = $request->comentario;
            $proyecto->tipo_rechazo   = $request->estado === 'rechazado_docs' ? 'docs' : 'tecnico';
            $proyecto->save();
        }

        $proyecto->avanzarEstado($request->estado, Auth::id(), $request->comentario);

        return response()->json([
            'success' => 'Estado actualizado a: '.ProyectoInstitucional::estadoLabel($request->estado),
            'estado'  => $proyecto->estado,
            'badge'   => ProyectoInstitucional::estadoBadge($proyecto->estado),
            'label'   => ProyectoInstitucional::estadoLabel($proyecto->estado),
        ]);
    }

    // ── Actualizar checklist ──────────────────────────────────────────────────
    public function updateChecklist(Request $request, $id)
    {
        $proyecto = ProyectoInstitucional::findOrFail($id);

        foreach ($request->checklist ?? [] as $item => $data) {
            $check = $proyecto->checklist()->where('item', $item)->first();
            if ($check) {
                $completado = isset($data['completado']) && $data['completado'];
                $check->update([
                    'completado'    => $completado,
                    'observacion'   => $data['observacion'] ?? null,
                    'completado_por'=> $completado ? Auth::id() : null,
                    'completado_at' => $completado ? now() : null,
                ]);
            }
        }

        return response()->json([
            'success' => 'Checklist actualizado.',
            'pct'     => $proyecto->fresh()->pctChecklist(),
            'completo'=> $proyecto->fresh()->checklistCompleto(),
        ]);
    }

    // ── Crear proyecto en el contexto de un perfil PEI ───────────────────────
    public function createForPerfil($profileId)
    {
        $perfil  = PeiProfile::findOrFail($profileId);
        $estados = ProyectoInstitucional::ESTADOS;
        return view('admin.proyectos.institucionales.create', compact('perfil', 'estados'));
    }

    // ── Solicitud pública de proyecto ─────────────────────────────────────────
    public function solicitarForm($profileId)
    {
        $perfil = PeiProfile::findOrFail($profileId);
        return view('public.proyectos.solicitar', compact('perfil'));
    }

    public function solicitarStore(Request $request, $profileId)
    {
        $perfil = PeiProfile::findOrFail($profileId);

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $proyecto = ProyectoInstitucional::create([
            'codigo'                     => ProyectoInstitucional::generarCodigo(),
            'nombre'                     => $request->nombre,
            'descripcion'                => $request->descripcion,
            'estado'                     => 'solicitud',
            'pei_profile_id'             => $perfil->id,
            'pei_accion_id'              => $request->pei_accion_id ?: null,
            'dependencia_solicitante_id' => $request->dependencia_solicitante_id ?: null,
            'fecha_solicitud'            => now(),
            'fecha_fin_estimada'         => $request->fecha_fin_estimada,
        ]);

        foreach (array_keys(ProyectoInstitucional::CHECKLIST_ITEMS) as $item) {
            ProyectoChecklist::create(['proyecto_id' => $proyecto->id, 'item' => $item, 'completado' => false]);
        }

        $proyecto->historial()->create([
            'estado_anterior' => null,
            'estado_nuevo'    => 'solicitud',
            'usuario_id'      => null,
            'comentario'      => 'Solicitud enviada desde formulario público.',
            'fecha'           => now(),
        ]);

        return back()->with('success', "Solicitud {$proyecto->codigo} enviada correctamente. Un analista se pondrá en contacto.");
    }

    // ── API: acciones filtradas por perfil ────────────────────────────────────
    public function getAccionesDePerfil(Request $request, $profileId)
    {
        $perfil = PeiProfile::findOrFail($profileId);
        $ids    = $perfil->descendants()->where('level', 'action')->pluck('id');

        $data = PeiProfile::whereIn('id', $ids)
            ->when($request->q, fn($q) => $q->where('name', 'ilike', '%'.$request->q.'%'))
            ->limit(50)
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'text' => strip_tags($p->name)]);

        return response()->json($data);
    }

    // ── API: buscar acciones del PEI para vincular (genérico, sin contexto) ───
    public function getPeiAcciones(Request $request)
    {
        $data = PeiProfile::where('level', 'action')
            ->when($request->q, fn($q) => $q->where('name', 'ilike', '%'.$request->q.'%'))
            ->with(['parent.parent'])
            ->limit(30)
            ->get()
            ->map(function($p) {
                // Construir contexto: Eje > Objetivo > Acción
                $partes = [];
                if ($p->parent?->parent) $partes[] = strip_tags($p->parent->parent->name);
                if ($p->parent)          $partes[] = strip_tags($p->parent->name);
                $contexto = implode(' › ', $partes);
                return [
                    'id'   => $p->id,
                    'text' => strip_tags($p->name) . ($contexto ? ' [' . \Illuminate\Support\Str::limit($contexto, 60) . ']' : ''),
                ];
            });

        return response()->json($data);
    }

    // ── Transiciones permitidas por estado ───────────────────────────────────
    private function transicionesPermitidas(string $estado): array
    {
        return match($estado) {
            'solicitud'          => ['en_analisis'],
            'en_analisis'        => ['en_desarrollo', 'rechazado_docs'],
            'en_desarrollo'      => ['en_homologacion', 'rechazado_tecnico'],
            'en_homologacion'    => ['consulta_gerencias', 'rechazado_tecnico'],
            'consulta_gerencias' => ['en_tramite', 'rechazado_tecnico'],
            'en_tramite'         => ['aprobado', 'rechazado_docs'],
            'aprobado'           => ['en_ejecucion'],
            'en_ejecucion'       => ['finalizado'],
            default              => [],
        };
    }
}
