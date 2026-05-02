<?php

namespace App\Http\Controllers\Admin\Estadistica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

use App\Models\Estadistica\SiessExtracto;
use App\Models\Estadistica\SiessModulo;
use App\Models\Estadistica\SiessIndicador;
use App\Models\Estadistica\SiessPeriodo;
use App\Models\Estadistica\SiessFuente;
use App\Admin\Globales\Organigrama;

class SiessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $modulos = SiessModulo::where('activo', true)->orderBy('orden')->get();

        // KPIs globales
        $kpis = [
            'total'                => SiessExtracto::count(),
            'pendientes'           => SiessExtracto::pendientes()->count(),
            'aprobados'            => SiessExtracto::aprobados()->count(),
            'objetados'            => SiessExtracto::where('estado', SiessExtracto::ESTADO_OBJETADO)->count(),
            'vencidos_hoy'         => SiessExtracto::vencidos()->count(),
            'aprobados_silencio'   => SiessExtracto::where('estado', SiessExtracto::ESTADO_APROBADO_SILENCIO)->count(),
        ];

        // Resumen por módulo
        $resumenModulos = $modulos->map(fn($m) => [
            'modulo'  => $m,
            'estados' => $m->resumenEstados(),
        ]);

        // Últimos extractos pendientes de validación (para alerta)
        $alertas = SiessExtracto::pendientes()
            ->with(['modulo', 'indicador', 'periodo', 'direccion'])
            ->orderBy('fecha_limite_validacion')
            ->limit(10)
            ->get()
            ->map(fn($e) => [
                'id'              => $e->id,
                'modulo'          => $e->modulo->nombre,
                'indicador'       => $e->indicador->codigo . ' — ' . $e->indicador->nombre,
                'periodo'         => $e->periodo->nombre,
                'dias_restantes'  => $e->diasRestantes(),
                'estado'          => $e->estado,
                'badge'           => SiessExtracto::estadoBadge($e->estado),
            ]);

        // Datos para gráfico de barras: extractos por módulo y estado
        $chartData = $modulos->map(fn($m) => [
            'label'    => $m->codigo,
            'aprobado' => $m->extractos()->aprobados()->count(),
            'pendiente'=> $m->extractos()->pendientes()->count(),
            'objetado' => $m->extractos()->where('estado', 'objetado')->count(),
        ]);

        return view('admin.estadisticas.siess.dashboard', compact(
            'modulos', 'kpis', 'resumenModulos', 'alertas', 'chartData'
        ));
    }

    // ── Listado de extractos ──────────────────────────────────────────────────

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SiessExtracto::with(['modulo', 'indicador', 'periodo', 'direccion', 'cargadoPor'])
                ->when($request->modulo_id, fn($q) => $q->where('modulo_id', $request->modulo_id))
                ->when($request->estado,    fn($q) => $q->where('estado', $request->estado))
                ->when($request->periodo_id,fn($q) => $q->where('periodo_id', $request->periodo_id))
                ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('modulo_nombre',    fn($r) => $r->modulo->nombre)
                ->addColumn('indicador_codigo', fn($r) => '<span class="badge badge-secondary">' . $r->indicador->codigo . '</span> ' . $r->indicador->nombre)
                ->addColumn('periodo_nombre',   fn($r) => $r->periodo->nombre)
                ->addColumn('estado_badge',     fn($r) => '<span class="badge ' . SiessExtracto::estadoBadge($r->estado) . '">' . SiessExtracto::estadoLabel($r->estado) . '</span>')
                ->addColumn('dias_restantes',   fn($r) => $r->diasRestantes() !== null ? $r->diasRestantes() . 'd' : '—')
                ->addColumn('action', fn($r) => $this->buildActionButtons($r))
                ->rawColumns(['indicador_codigo', 'estado_badge', 'action'])
                ->make(true);
        }

        $modulos   = SiessModulo::where('activo', true)->orderBy('orden')->get();
        $periodos  = SiessPeriodo::orderByDesc('anio')->orderByDesc('mes')->limit(24)->get();
        $estados   = [
            SiessExtracto::ESTADO_BORRADOR             => 'Borrador',
            SiessExtracto::ESTADO_PENDIENTE_VALIDACION => 'Pendiente de Validación',
            SiessExtracto::ESTADO_APROBADO             => 'Aprobado',
            SiessExtracto::ESTADO_OBJETADO             => 'Objetado',
            SiessExtracto::ESTADO_APROBADO_SILENCIO    => 'Aprobado por Silencio',
        ];

        return view('admin.estadisticas.siess.index', compact('modulos', 'periodos', 'estados'));
    }

    // ── Crear / Editar extracto ───────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modulo_id'    => 'required|integer',
            'indicador_id' => 'required|integer',
            'periodo_id'   => 'required|integer',
            'resumen'      => 'nullable|string|max:2000',
            'datos'        => 'nullable|string',
        ]);

        // Verificar existencia manualmente (evita problema de schema en exists:)
        if (!SiessModulo::find($request->modulo_id)) {
            return response()->json(['errors' => ['modulo_id' => ['Módulo no válido.']]], 422);
        }
        if (!SiessIndicador::find($request->indicador_id)) {
            return response()->json(['errors' => ['indicador_id' => ['Indicador no válido.']]], 422);
        }
        if (!SiessPeriodo::find($request->periodo_id)) {
            return response()->json(['errors' => ['periodo_id' => ['Período no válido.']]], 422);
        }

        $datosJson = null;
        if ($request->filled('datos')) {
            $decoded = json_decode($request->datos, true);
            $datosJson = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        $payload = [
            'modulo_id'    => $request->modulo_id,
            'indicador_id' => $request->indicador_id,
            'periodo_id'   => $request->periodo_id,
            'fuente_id'    => $request->fuente_id ?: null,
            'direccion_id' => $request->direccion_id ?: null,
            'resumen'      => $request->resumen,
            'datos'        => $datosJson,
            'cargado_por'  => Auth::id(),
        ];

        if ($request->filled('extracto_id')) {
            // Edición
            $extracto = SiessExtracto::findOrFail($request->extracto_id);
            $extracto->update($payload);
            $creado = false;
        } else {
            // Creación
            $payload['estado'] = SiessExtracto::ESTADO_BORRADOR;
            $extracto = SiessExtracto::create($payload);
            $creado = true;
        }

        return response()->json([
            'success'  => $creado ? 'Extracto creado correctamente.' : 'Extracto actualizado correctamente.',
            'extracto' => $extracto->load(['modulo', 'indicador', 'periodo']),
        ]);
    }

    public function edit($id)
    {
        $extracto = SiessExtracto::with([
            'modulo', 'indicador', 'periodo', 'fuente',
            'direccion',   // incluir la dirección para precargar en edición
            'validaciones.usuario'
        ])->findOrFail($id);

        return response()->json(['extracto' => $extracto]);
    }

    // ── Acciones del flujo de validación ─────────────────────────────────────

    public function enviarValidacion(Request $request, $id)
    {
        $extracto = SiessExtracto::findOrFail($id);

        if ($extracto->estado !== SiessExtracto::ESTADO_BORRADOR &&
            $extracto->estado !== SiessExtracto::ESTADO_OBJETADO) {
            return response()->json(['error' => 'Solo se pueden enviar extractos en estado Borrador u Objetado.'], 422);
        }

        $extracto->enviarAValidacion(Auth::id());

        return response()->json([
            'success'          => 'Extracto enviado a validación. La dirección tiene 5 días hábiles para responder.',
            'fecha_limite'     => $extracto->fecha_limite_validacion->format('d/m/Y'),
            'dias_restantes'   => $extracto->diasRestantes(),
        ]);
    }

    public function aprobar(Request $request, $id)
    {
        $extracto = SiessExtracto::findOrFail($id);

        if ($extracto->estado !== SiessExtracto::ESTADO_PENDIENTE_VALIDACION) {
            return response()->json(['error' => 'Solo se pueden aprobar extractos pendientes de validación.'], 422);
        }

        // El generador no puede aprobar su propio extracto
        if ($extracto->cargado_por == Auth::id()) {
            return response()->json(['error' => 'No podés aprobar un extracto que vos mismo generaste.'], 403);
        }

        $extracto->aprobar(Auth::id(), $request->comentario);

        return response()->json(['success' => 'Extracto aprobado. Disponible para reportes gerenciales.']);
    }

    public function objetar(Request $request, $id)
    {
        $request->validate(['observacion' => 'required|string|min:10']);

        $extracto = SiessExtracto::findOrFail($id);

        if ($extracto->estado !== SiessExtracto::ESTADO_PENDIENTE_VALIDACION) {
            return response()->json(['error' => 'Solo se pueden objetar extractos pendientes de validación.'], 422);
        }

        // El generador no puede objetar su propio extracto
        if ($extracto->cargado_por == Auth::id()) {
            return response()->json(['error' => 'No podés objetar un extracto que vos mismo generaste.'], 403);
        }

        $extracto->objetar(Auth::id(), $request->observacion);

        return response()->json(['success' => 'Extracto objetado. Planificación debe corregir y reenviar.']);
    }

    public function marcarFuenteUnica(Request $request, $id)
    {
        $extracto = SiessExtracto::findOrFail($id);

        if (!$extracto->estaAprobado()) {
            return response()->json(['error' => 'Solo se pueden marcar como fuente única los extractos aprobados.'], 422);
        }

        $extracto->marcarFuenteUnica(Auth::id());

        return response()->json(['success' => 'Marcado como fuente única de consulta (Art. 6 Res. 266/2022).']);
    }

    public function destroy($id)
    {
        $extracto = SiessExtracto::findOrFail($id);

        if ($extracto->estaAprobado()) {
            return response()->json(['error' => 'No se puede eliminar un extracto aprobado.'], 422);
        }

        $extracto->delete();

        return response()->json(['success' => 'Extracto eliminado.']);
    }

    // ── API para selects ──────────────────────────────────────────────────────

    public function getIndicadoresPorModulo($moduloId)
    {
        $indicadores = SiessIndicador::where('modulo_id', $moduloId)
            ->where('activo', true)
            ->get(['id', 'codigo', 'nombre', 'unidad', 'tipo_carga']);

        return response()->json($indicadores);
    }

    public function getPeriodos(Request $request)
    {
        $periodos = SiessPeriodo::where('activo', true)
            ->when($request->tipo, fn($q) => $q->where('tipo', $request->tipo))
            ->orderByDesc('anio')->orderByDesc('mes')
            ->limit(36)
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'text' => $p->nombre]);

        return response()->json($periodos);
    }

    // ── Notificaciones ────────────────────────────────────────────────────────

    public function notificaciones(Request $request)
    {
        try {
            $notifs = \App\Models\Estadistica\SiessNotificacion::where('user_id', Auth::id())
                ->with('extracto.modulo')
                ->orderByDesc('created_at')
                ->limit(20)
                ->get();

            $noLeidas = $notifs->where('leida', false)->count();

            return response()->json([
                'notificaciones' => $notifs->map(fn($n) => [
                    'id'      => $n->id,
                    'titulo'  => $n->titulo,
                    'mensaje' => $n->mensaje,
                    'tipo'    => $n->tipo,
                    'icono'   => $n->iconoTipo(),
                    'leida'   => $n->leida,
                    'fecha'   => $n->created_at->diffForHumans(),
                ]),
                'no_leidas' => $noLeidas,
            ]);
        } catch (\Exception $e) {
            return response()->json(['notificaciones' => [], 'no_leidas' => 0]);
        }
    }

    public function marcarNotificacionLeida(Request $request, $id)
    {
        $notif = \App\Models\Estadistica\SiessNotificacion::findOrFail($id);
        $notif->marcarLeida();
        return response()->json(['success' => true]);
    }

    public function marcarTodasLeidas(Request $request)
    {
        \App\Models\Estadistica\SiessNotificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true, 'leida_at' => now()]);

        return response()->json(['success' => true]);
    }

    // ── Home para usuarios Participantes ──────────────────────────────────────

    public function home()
    {
        $userId = Auth::id();

        // Organigrama del usuario (con su padre para mostrar jerarquía)
        $organigrama = \App\Admin\Globales\Organigrama::with('parent')
            ->where('user_id', $userId)->first();

        // Extractos pendientes de validación de su dirección — con todo el detalle
        $pendientesParaValidar = collect();
        $historialReciente     = collect();

        if ($organigrama) {
            $pendientesParaValidar = SiessExtracto::pendientes()
                ->where('direccion_id', $organigrama->id)
                ->with(['modulo', 'indicador', 'periodo', 'cargadoPor', 'validaciones'])
                ->orderBy('fecha_limite_validacion')
                ->get();

            // Historial: últimos 10 extractos ya respondidos de su dirección
            $historialReciente = SiessExtracto::where('direccion_id', $organigrama->id)
                ->whereIn('estado', [
                    SiessExtracto::ESTADO_APROBADO,
                    SiessExtracto::ESTADO_OBJETADO,
                    SiessExtracto::ESTADO_APROBADO_SILENCIO,
                ])
                ->with(['modulo', 'indicador', 'periodo'])
                ->orderByDesc('fecha_respuesta')
                ->limit(10)
                ->get();
        }

        return view('siess.home', compact('organigrama', 'pendientesParaValidar', 'historialReciente'));
    }

    // ── Helper privado ────────────────────────────────────────────────────────

    private function buildActionButtons(SiessExtracto $r): string
    {
        $userId       = Auth::id();
        $esGenerador  = $r->cargado_por == $userId;
        $esAdmin      = Auth::user()->hasRole('Administrador');
        $esDireccion  = $esAdmin && !$esGenerador; // Admin que no generó = actúa como dirección

        // Ver/Editar — siempre visible
        $btn = '<a href="javascript:void(0)" class="btn btn-info btn-circle editExtracto" data-id="' . $r->id . '" title="Ver/Editar"><i class="fa fa-eye"></i></a> ';

        // Enviar a validación — solo el generador, en borrador u objetado
        if ($esGenerador && in_array($r->estado, [SiessExtracto::ESTADO_BORRADOR, SiessExtracto::ESTADO_OBJETADO])) {
            $btn .= '<a href="javascript:void(0)" class="btn btn-warning btn-circle enviarValidacion" data-id="' . $r->id . '" title="Enviar a Validación"><i class="fa fa-paper-plane"></i></a> ';
        }

        // Aprobar / Objetar — solo Admin (dirección responsable), cuando está pendiente
        // El generador NO puede aprobar su propio extracto
        if ($esAdmin && !$esGenerador && $r->estado === SiessExtracto::ESTADO_PENDIENTE_VALIDACION) {
            $btn .= '<a href="javascript:void(0)" class="btn btn-success btn-circle aprobarExtracto" data-id="' . $r->id . '" title="Aprobar"><i class="fa fa-check"></i></a> ';
            $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-circle objetarExtracto" data-id="' . $r->id . '" title="Objetar"><i class="fa fa-times"></i></a> ';
        }

        // Marcar fuente única — solo Admin, cuando está aprobado
        if ($esAdmin && $r->estaAprobado()) {
            $btn .= '<a href="javascript:void(0)" class="btn btn-secondary btn-circle marcarFuenteUnica" data-id="' . $r->id . '" title="Marcar Fuente Única (Art. 6)"><i class="fa fa-bookmark"></i></a> ';
        }

        // Eliminar — solo en borrador u objetado, nunca en pendiente/aprobado
        if (in_array($r->estado, [SiessExtracto::ESTADO_BORRADOR, SiessExtracto::ESTADO_OBJETADO])) {
            $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-circle deleteExtracto" data-id="' . $r->id . '" title="Eliminar"><i class="fa fa-trash"></i></a>';
        }

        return $btn;
    }
}
