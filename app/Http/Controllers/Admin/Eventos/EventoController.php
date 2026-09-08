<?php

namespace App\Http\Controllers\Admin\Eventos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Models\Eventos\Evento;
use App\Models\Eventos\EventoPaso;
use App\Models\Eventos\EventoTarea;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Activity;
use App\Admin\Globales\Organigrama;

class EventoController extends Controller
{
    /**
     * Listado Principal de Eventos Institucionales y Métricas
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Evento::with(['peiProfile', 'responsables', 'pasos.tareas'])->latest();

            if ($request->filled('pei_profile_id')) {
                $query->where('pei_profile_id', $request->pei_profile_id);
            }

            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->filled('tipo')) {
                $query->where('tipo', $request->tipo);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nombre_html', function (Evento $e) {
                    $color = $e->color ?: '#6366f1';
                    $html = '<div class="d-flex align-items-center">';
                    $html .= '<span class="mr-2" style="display:inline-block; width:12px; height:12px; border-radius:50%; background-color:' . e($color) . ';"></span>';
                    $html .= '<div>';
                    $html .= '<a href="' . route('eventos.show', $e->id) . '" class="font-weight-bold text-dark" style="font-size:0.92rem;">' . e($e->nombre) . '</a>';
                    if ($e->lugar_sede) {
                        $html .= '<div class="text-muted small"><i class="fa fa-map-marker-alt text-danger mr-1"></i>' . e($e->lugar_sede) . '</div>';
                    }
                    $html .= '</div></div>';
                    return $html;
                })
                ->addColumn('pei_profile', function (Evento $e) {
                    if (!$e->peiProfile) return '<span class="text-muted small">No vinculado</span>';
                    return '<span class="badge badge-light text-dark font-weight-normal border"><i class="fa fa-bullseye text-info mr-1"></i>' . e(\Illuminate\Support\Str::limit(strip_tags($e->peiProfile->name), 40)) . '</span>';
                })
                ->addColumn('fechas', function (Evento $e) {
                    return '<small class="text-dark font-weight-bold">' . $e->rango_fechas_formateado . '</small>';
                })
                ->addColumn('avance_html', function (Evento $e) {
                    $pct = $e->porcentaje_avance;
                    $total = $e->total_tareas;
                    $comp = $e->tareas_completadas;
                    $barColor = $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-primary' : 'bg-warning');

                    $html = '<div style="min-width: 130px;">';
                    $html .= '<div class="d-flex justify-content-between mb-1" style="font-size:0.75rem;">';
                    $html .= '<span class="font-weight-bold">' . $pct . '%</span>';
                    $html .= '<span class="text-muted">' . $comp . '/' . $total . ' tareas</span>';
                    $html .= '</div>';
                    $html .= '<div class="progress" style="height: 6px; border-radius: 4px; background:#e2e8f0;">';
                    $html .= '<div class="progress-bar ' . $barColor . '" role="progressbar" style="width: ' . $pct . '%; border-radius: 4px;"></div>';
                    $html .= '</div></div>';
                    return $html;
                })
                ->addColumn('responsables_html', function (Evento $e) {
                    $resps = $e->responsables;
                    if ($resps->isEmpty()) return '<span class="text-muted small">—</span>';
                    $html = '<div class="d-flex flex-wrap" style="gap:4px;">';
                    foreach ($resps->take(3) as $r) {
                        $html .= '<span class="badge badge-light border text-dark" style="font-size:0.72rem;"><i class="fa fa-user mr-1 text-muted"></i>' . e($r->name) . '</span>';
                    }
                    if ($resps->count() > 3) {
                        $html .= '<span class="badge badge-secondary" style="font-size:0.7rem;">+' . ($resps->count() - 3) . '</span>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('estado_html', fn(Evento $e) => $e->estado_badge_html)
                ->addColumn('action', function (Evento $e) {
                    $btn  = '<a href="' . route('eventos.show', $e->id) . '" class="btn btn-info btn-circle btn-sm shadow-sm" title="Centro de Mando del Evento"><i class="fas fa-tasks"></i></a>';
                    $btn .= ' <button type="button" class="btn btn-primary btn-circle btn-sm shadow-sm btnEditarEvento" data-id="' . $e->id . '" title="Editar Evento"><i class="far fa-edit"></i></button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-circle btn-sm shadow-sm btnEliminarEvento" data-id="' . $e->id . '" data-nombre="' . e($e->nombre) . '" title="Eliminar Evento"><i class="fa fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['nombre_html', 'pei_profile', 'fechas', 'avance_html', 'responsables_html', 'estado_html', 'action'])
                ->make(true);
        }

        // Métricas KPI
        $kpiTotalEventos   = Evento::count();
        $kpiEventosEnCurso = Evento::where('estado', 'en_curso')->count();
        $kpiTareasPend     = EventoTarea::where('completada', false)->count();
        $kpiTareasVencidas = EventoTarea::where('completada', false)->whereNotNull('fecha_limite')->where('fecha_limite', '<', Carbon::today())->count();
        
        $totalTareasAll    = EventoTarea::count();
        $totalCompletadas  = EventoTarea::where('completada', true)->count();
        $kpiAvanceGlobal   = $totalTareasAll > 0 ? (int) round(($totalCompletadas / $totalTareasAll) * 100) : 0;

        // Catálogos para selectores
        $usuarios = User::orderBy('name')->get(['id', 'name', 'email']);
        $peiPerfiles = PeiProfile::whereNull('parent_id')
            ->where('level', 'master')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $organigramas = Organigrama::orderBy('dependency')->select('id', 'dependency as name')->get();
        $actividades = Activity::latest()->take(50)->get(['id', 'name']);

        $selectedPeiId = $request->get('pei_profile_id');

        return view('admin.eventos.index', compact(
            'kpiTotalEventos',
            'kpiEventosEnCurso',
            'kpiTareasPend',
            'kpiTareasVencidas',
            'kpiAvanceGlobal',
            'usuarios',
            'peiPerfiles',
            'organigramas',
            'actividades',
            'selectedPeiId'
        ));
    }

    /**
     * Centro de Mando / Hub del Evento (Fases, Checklist y Calendario)
     */
    public function show($id)
    {
        $evento = Evento::with([
            'peiProfile',
            'activity',
            'organigrama',
            'creator',
            'responsables',
            'pasos' => function ($q) {
                $q->orderBy('orden')
                  ->with([
                      'responsablePrincipal',
                      'responsables',
                      'tareas' => fn($t) => $t->orderBy('orden')->with(['responsable', 'completadaPor']),
                  ]);
            },
        ])->findOrFail($id);

        $usuarios = User::orderBy('name')->get(['id', 'name', 'email']);
        $peiPerfiles = PeiProfile::whereNull('parent_id')->where('level', 'master')->where('is_active', true)->get();

        // Alertas específicas de este evento
        $tareasVencidas = $evento->tareas()->where('completada', false)->whereNotNull('fecha_limite')->where('fecha_limite', '<', Carbon::today())->get();
        $tareasProximas  = $evento->tareas()->where('completada', false)->whereNotNull('fecha_limite')->whereBetween('fecha_limite', [Carbon::today(), Carbon::today()->addDays(3)])->get();

        return view('admin.eventos.show', compact('evento', 'usuarios', 'peiPerfiles', 'tareasVencidas', 'tareasProximas'));
    }

    /**
     * Calendario Ejecutivo Global
     */
    public function calendario(Request $request)
    {
        $peiPerfiles = PeiProfile::whereNull('parent_id')->where('level', 'master')->where('is_active', true)->get();
        $usuarios = User::orderBy('name')->get(['id', 'name']);
        $selectedPeiId = $request->get('pei_profile_id');

        return view('admin.eventos.calendario', compact('peiPerfiles', 'usuarios', 'selectedPeiId'));
    }

    /**
     * Feed JSON de Eventos, Pasos y Tareas para FullCalendar
     */
    public function eventsFeed(Request $request)
    {
        $queryEventos = Evento::with(['responsables', 'peiProfile']);
        $queryPasos   = EventoPaso::with(['evento', 'responsablePrincipal']);
        $queryTareas  = EventoTarea::with(['evento', 'paso', 'responsable']);

        if ($request->filled('evento_id')) {
            $queryEventos->where('id', $request->evento_id);
            $queryPasos->where('evento_id', $request->evento_id);
            $queryTareas->where('evento_id', $request->evento_id);
        }

        if ($request->filled('pei_profile_id')) {
            $queryEventos->where('pei_profile_id', $request->pei_profile_id);
            $queryPasos->whereHas('evento', fn($q) => $q->where('pei_profile_id', $request->pei_profile_id));
            $queryTareas->whereHas('evento', fn($q) => $q->where('pei_profile_id', $request->pei_profile_id));
        }

        if ($request->filled('responsable_id')) {
            $respId = $request->responsable_id;
            $queryEventos->whereHas('responsables', fn($q) => $q->where('users.id', $respId));
            $queryPasos->where(function($q) use ($respId) {
                $q->where('responsable_principal_id', $respId)
                  ->orWhereHas('responsables', fn($r) => $r->where('users.id', $respId));
            });
            $queryTareas->where('responsable_id', $respId);
        }

        $feed = [];

        // 1. Eventos Generales
        foreach ($queryEventos->get() as $ev) {
            if ($ev->fecha_inicio) {
                $feed[] = [
                    'id'          => 'evento_' . $ev->id,
                    'title'       => '★ EVENTO: ' . $ev->nombre,
                    'start'       => $ev->fecha_inicio->format('Y-m-d'),
                    'end'         => $ev->fecha_fin ? Carbon::parse($ev->fecha_fin)->addDay()->format('Y-m-d') : null,
                    'color'       => $ev->color ?: '#6366f1',
                    'textColor'   => '#ffffff',
                    'url'         => route('eventos.show', $ev->id),
                    'className'   => 'fc-evento-principal shadow-sm',
                    'allDay'      => true,
                    'extendedProps' => [
                        'tipo_item'    => 'evento',
                        'lugar'        => $ev->lugar_sede,
                        'estado'       => $ev->estado,
                        'avance'       => $ev->porcentaje_avance . '%',
                    ],
                ];
            }
        }

        // 2. Pasos / Hitos
        foreach ($queryPasos->get() as $paso) {
            if ($paso->fecha_inicio) {
                $colorPaso = $paso->color ?: ($paso->evento ? $paso->evento->color : '#f59e0b');
                $feed[] = [
                    'id'          => 'paso_' . $paso->id,
                    'title'       => '► ' . $paso->nombre,
                    'start'       => $paso->fecha_inicio->format('Y-m-d'),
                    'end'         => $paso->fecha_fin ? Carbon::parse($paso->fecha_fin)->addDay()->format('Y-m-d') : null,
                    'color'       => $colorPaso,
                    'textColor'   => '#ffffff',
                    'url'         => route('eventos.show', $paso->evento_id),
                    'className'   => 'fc-evento-paso shadow-sm',
                    'allDay'      => true,
                    'extendedProps' => [
                        'tipo_item'    => 'paso',
                        'evento'       => $paso->evento?->nombre,
                        'responsable'  => $paso->responsablePrincipal?->name,
                        'estado'       => $paso->estado,
                        'avance'       => $paso->porcentaje_avance . '%',
                    ],
                ];
            }
        }

        // 3. Tareas con fecha límite
        foreach ($queryTareas->get() as $tar) {
            if ($tar->fecha_limite) {
                $colorTar = $tar->completada ? '#10b981' : ($tar->esta_vencida ? '#ef4444' : '#0ea5e9');
                $feed[] = [
                    'id'          => 'tarea_' . $tar->id,
                    'title'       => ($tar->completada ? '✓ ' : '• ') . $tar->nombre,
                    'start'       => $tar->fecha_limite->format('Y-m-d'),
                    'color'       => $colorTar,
                    'textColor'   => '#ffffff',
                    'url'         => route('eventos.show', $tar->evento_id),
                    'className'   => 'fc-evento-tarea ' . ($tar->completada ? 'fc-tarea-completada' : ''),
                    'allDay'      => true,
                    'extendedProps' => [
                        'tipo_item'    => 'tarea',
                        'paso'         => $tar->paso?->nombre,
                        'responsable'  => $tar->responsable?->name,
                        'completada'   => $tar->completada,
                        'prioridad'    => $tar->prioridad,
                    ],
                ];
            }
        }

        return response()->json($feed);
    }

    /**
     * Creación de Nuevo Evento (Redirige al Tablero para apertura de Modal In-Situ)
     */
    public function create(Request $request)
    {
        return redirect()->route('eventos.index', array_merge($request->query(), ['action' => 'create']));
    }

    /**
     * Guardar nuevo Evento
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
        ], [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        $evento = Evento::create([
            'nombre'                => $request->nombre,
            'tipo'                  => $request->tipo ?: 'Taller',
            'descripcion'           => $request->descripcion,
            'lugar_sede'            => $request->lugar_sede,
            'fecha_inicio'          => $request->fecha_inicio,
            'fecha_fin'             => $request->fecha_fin,
            'estado'                => $request->estado ?: 'planificado',
            'color'                 => $request->color ?: '#00bcd4',
            'pei_profile_id'        => $request->pei_profile_id,
            'activity_id'           => $request->activity_id,
            'organigrama_id'        => $request->organigrama_id,
            'presupuesto_estimado'  => $request->presupuesto_estimado ?: 0,
            'presupuesto_ejecutado' => $request->presupuesto_ejecutado ?: 0,
            'created_by'            => Auth::id(),
            'updated_by'            => Auth::id(),
        ]);

        if ($request->has('responsables')) {
            $evento->responsables()->sync($request->responsables);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Evento institucional creado exitosamente.',
            'evento'   => $evento,
            'redirect' => route('eventos.show', $evento->id),
        ]);
    }

    /**
     * Obtener datos para editar Evento vía Modal
     */
    public function edit($id)
    {
        $evento = Evento::with(['responsables'])->findOrFail($id);
        return response()->json($evento);
    }

    /**
     * Actualizar Evento
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $evento->update([
            'nombre'                => $request->nombre,
            'tipo'                  => $request->tipo ?: $evento->tipo,
            'descripcion'           => $request->descripcion,
            'lugar_sede'            => $request->lugar_sede,
            'fecha_inicio'          => $request->fecha_inicio,
            'fecha_fin'             => $request->fecha_fin,
            'estado'                => $request->estado ?: $evento->estado,
            'color'                 => $request->color ?: $evento->color,
            'pei_profile_id'        => $request->pei_profile_id,
            'activity_id'           => $request->activity_id,
            'organigrama_id'        => $request->organigrama_id,
            'presupuesto_estimado'  => $request->presupuesto_estimado ?: $evento->presupuesto_estimado,
            'presupuesto_ejecutado' => $request->presupuesto_ejecutado ?: $evento->presupuesto_ejecutado,
            'updated_by'            => Auth::id(),
        ]);

        if ($request->has('responsables')) {
            $evento->responsables()->sync($request->responsables);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Evento actualizado correctamente.',
                'evento'  => $evento,
            ]);
        }

        return redirect()->route('eventos.show', $evento->id)->with('success', 'Evento institucional actualizado correctamente.');
    }

    /**
     * Eliminar Evento
     */
    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evento eliminado correctamente.',
        ]);
    }

    // ── GESTIÓN DE PASOS / HITOS ─────────────────────────────────────────

    public function storePaso(Request $request, $eventoId)
    {
        $evento = Evento::findOrFail($eventoId);

        $request->validate([
            'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El título del paso es obligatorio.',
        ]);

        $maxOrden = $evento->pasos()->max('orden') ?: 0;

        $paso = EventoPaso::create([
            'evento_id'                => $evento->id,
            'orden'                    => $maxOrden + 1,
            'nombre'                   => $request->nombre,
            'descripcion'              => $request->descripcion,
            'fecha_inicio'             => $request->fecha_inicio ?: $evento->fecha_inicio,
            'fecha_fin'                => $request->fecha_fin ?: $evento->fecha_fin,
            'estado'                   => $request->estado ?: 'pendiente',
            'color'                    => $request->color ?: $evento->color,
            'responsable_principal_id' => $request->responsable_principal_id,
            'created_by'               => Auth::id(),
            'updated_by'               => Auth::id(),
        ]);

        if ($request->has('co_responsables')) {
            $paso->responsables()->sync($request->co_responsables);
        }

        return response()->json([
            'success' => true,
            'message' => 'Paso agregado exitosamente.',
            'paso'    => $paso->load(['responsablePrincipal', 'responsables', 'tareas']),
        ]);
    }

    public function updatePaso(Request $request, $eventoId, $pasoId)
    {
        $paso = EventoPaso::where('evento_id', $eventoId)->findOrFail($pasoId);

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $paso->update([
            'nombre'                   => $request->nombre,
            'descripcion'              => $request->descripcion,
            'fecha_inicio'             => $request->fecha_inicio,
            'fecha_fin'                => $request->fecha_fin,
            'estado'                   => $request->estado ?: $paso->estado,
            'color'                    => $request->color ?: $paso->color,
            'responsable_principal_id' => $request->responsable_principal_id,
            'updated_by'               => Auth::id(),
        ]);

        if ($request->has('co_responsables')) {
            $paso->responsables()->sync($request->co_responsables);
        }

        return response()->json([
            'success' => true,
            'message' => 'Paso actualizado correctamente.',
            'paso'    => $paso->load(['responsablePrincipal', 'responsables']),
        ]);
    }

    public function destroyPaso($eventoId, $pasoId)
    {
        $paso = EventoPaso::where('evento_id', $eventoId)->findOrFail($pasoId);
        $paso->delete();

        return response()->json([
            'success' => true,
            'message' => 'Paso eliminado correctamente.',
        ]);
    }

    // ── GESTIÓN DE TAREAS OPERATIVAS (CHECKLIST) ─────────────────────────

    public function storeTarea(Request $request, $pasoId)
    {
        $paso = EventoPaso::findOrFail($pasoId);

        $request->validate([
            'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre de la tarea es obligatorio.',
        ]);

        $maxOrden = $paso->tareas()->max('orden') ?: 0;

        $tarea = EventoTarea::create([
            'evento_paso_id' => $paso->id,
            'evento_id'      => $paso->evento_id,
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'responsable_id' => $request->responsable_id ?: $paso->responsable_principal_id,
            'fecha_limite'   => $request->fecha_limite ?: $paso->fecha_fin,
            'prioridad'      => $request->prioridad ?: 'media',
            'costo_estimado' => $request->costo_estimado ?: 0,
            'orden'          => $maxOrden + 1,
            'observaciones'  => $request->observaciones,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea agregada al checklist.',
            'tarea'   => $tarea->load(['responsable']),
        ]);
    }

    public function updateTarea(Request $request, $tareaId)
    {
        $tarea = EventoTarea::findOrFail($tareaId);

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $tarea->update([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'responsable_id' => $request->responsable_id,
            'fecha_limite'   => $request->fecha_limite,
            'prioridad'      => $request->prioridad ?: $tarea->prioridad,
            'costo_estimado' => $request->costo_estimado ?: $tarea->costo_estimado,
            'observaciones'  => $request->observaciones,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada correctamente.',
            'tarea'   => $tarea->load(['responsable']),
        ]);
    }

    public function toggleTareaCompletada(Request $request, $tareaId)
    {
        $tarea = EventoTarea::findOrFail($tareaId);
        $nuevaCondicion = !$tarea->completada;

        $tarea->update([
            'completada'     => $nuevaCondicion,
            'completada_el'  => $nuevaCondicion ? Carbon::now() : null,
            'completada_por' => $nuevaCondicion ? Auth::id() : null,
        ]);

        $evento = $tarea->evento;
        $paso   = $tarea->paso;

        return response()->json([
            'success'        => true,
            'completada'     => $tarea->completada,
            'completada_el'  => $tarea->completada_el ? $tarea->completada_el->format('d/m/Y H:i') : null,
            'completada_por' => $tarea->completadaPor?->name,
            'avance_paso'    => $paso->porcentaje_avance,
            'avance_evento'  => $evento->porcentaje_avance,
            'message'        => $tarea->completada ? 'Tarea completada exitosamente.' : 'Tarea marcada como pendiente.',
        ]);
    }

    public function destroyTarea($tareaId)
    {
        $tarea = EventoTarea::findOrFail($tareaId);
        $tarea->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada del checklist.',
        ]);
    }

    /**
     * Resumen de Alertas Diarias para Widgets / Notificaciones
     */
    public function alertasWidget(Request $request)
    {
        $vencidas = EventoTarea::vencidas()->with(['evento', 'paso', 'responsable'])->latest()->take(10)->get();
        $proximas = EventoTarea::proximasAVencer(3)->with(['evento', 'paso', 'responsable'])->orderBy('fecha_limite')->take(10)->get();

        return response()->json([
            'total_vencidas' => EventoTarea::vencidas()->count(),
            'total_proximas' => EventoTarea::proximasAVencer(3)->count(),
            'vencidas'       => $vencidas,
            'proximas'       => $proximas,
        ]);
    }
}
