<?php

namespace App\Http\Controllers\Admin\PlanMaestro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlanMaestro\PlanMaestro;
use App\Models\PlanMaestro\PlanAccion;

class PlanMaestroController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // Solo rol Alta Gerencia o Administrador
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasAnyRole(['Alta Gerencia', 'Administrador'])) {
                abort(403, 'Acceso restringido a Alta Gerencia.');
            }
            return $next($request);
        });
    }

    /**
     * Listado de planes disponibles.
     */
    public function index()
    {
        $planes = PlanMaestro::where('activo', true)
            ->withCount(['acciones', 'diagnosticos', 'canillas', 'citas'])
            ->latest()
            ->get();

        return view('admin.plan_maestro.index', compact('planes'));
    }

    /**
     * Vista principal del buscador de un plan.
     */
    public function show(PlanMaestro $plan)
    {
        $ejes        = $plan->ejes()->with('acciones')->get();
        $diagnosticos = $plan->diagnosticos()->with('tags')->get();
        $canillas    = $plan->canillas;
        $citas       = $plan->citas;

        // Datos serializados para el JS (evitar fn() en @json de Blade)
        $diagnosticosJs = $diagnosticos->map(function($d) {
            return [
                'titulo'    => $d->titulo,
                'contenido' => $d->contenido,
                'tags'      => $d->tags->pluck('tag'),
            ];
        });
        $canillasJs = $canillas->map(function($c) {
            return [
                'tipo'       => $c->tipo,
                'ejemplos'   => $c->ejemplos,
                'estrategia' => $c->estrategia,
                'monto'      => $c->monto,
            ];
        });
        $citasJs = $citas->map(function($c) {
            return [
                'texto'    => $c->texto,
                'autor'    => $c->autor,
                'fecha'    => $c->fecha,
                'contexto' => $c->contexto,
            ];
        });

        // Stats rápidos
        $stats = [
            'total'     => $plan->acciones()->count(),
            'ejecutado' => $plan->acciones()->get()->filter(fn($a) => $a->estado_grupo === 'EJECUTADO')->count(),
            'en_curso'  => $plan->acciones()->get()->filter(fn($a) => $a->estado_grupo === 'EN CURSO')->count(),
            'pendiente' => $plan->acciones()->get()->filter(fn($a) => $a->estado_grupo === 'PENDIENTE')->count(),
        ];

        return view('admin.plan_maestro.show', compact(
            'plan', 'ejes', 'diagnosticos', 'canillas', 'citas', 'stats',
            'diagnosticosJs', 'canillasJs', 'citasJs'
        ));
    }

    /**
     * API: devuelve acciones filtradas en JSON para el buscador AJAX.
     */
    public function buscar(Request $request, PlanMaestro $plan)
    {
        $query = $plan->acciones()->with('eje');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('accion',        'ilike', "%$q%")
                   ->orWhere('justificacion','ilike', "%$q%")
                   ->orWhere('kpi',          'ilike', "%$q%")
                   ->orWhere('detalle',      'ilike', "%$q%")
                   ->orWhere('responsable',  'ilike', "%$q%")
                   ->orWhere('estado',       'ilike', "%$q%")
                   ->orWhere('codigo',       'ilike', "%$q%");
            });
        }

        if ($request->filled('eje')) {
            $query->whereHas('eje', fn($qb) => $qb->where('codigo', $request->eje));
        }

        if ($request->filled('momento')) {
            $query->where('momento', $request->momento);
        }

        if ($request->filled('estado_grupo')) {
            $acciones = $query->get()->filter(
                fn($a) => $a->estado_grupo === $request->estado_grupo
            )->values();
        } else {
            $acciones = $query->orderBy('orden')->get();
        }

        return response()->json([
            'acciones' => $acciones->map(fn($a) => [
                'id'            => $a->id,
                'codigo'        => $a->codigo,
                'eje_codigo'    => $a->eje->codigo,
                'eje_nombre'    => $a->eje->nombre,
                'eje_color'     => $a->eje->color,
                'eje_icono'     => $a->eje->icono,
                'momento'       => $a->momento,
                'accion'        => $a->accion,
                'justificacion' => $a->justificacion,
                'kpi'           => $a->kpi,
                'plazo'         => $a->plazo,
                'responsable'   => $a->responsable,
                'estado'        => $a->estado,
                'estado_grupo'  => $a->estado_grupo,
                'detalle'       => $a->detalle,
            ]),
            'total' => $acciones->count(),
        ]);
    }

    /**
     * Actualiza el estado de una acción (inline desde la vista).
     */
    public function actualizarEstado(Request $request, \App\Models\PlanMaestro\PlanAccion $accion)
    {
        $request->validate(['estado' => 'required|string|max:100']);
        $accion->update(['estado' => $request->estado]);
        return response()->json([
            'ok'           => true,
            'estado'       => $accion->estado,
            'estado_grupo' => $accion->estado_grupo,
        ]);
    }

    /**
     * Crea una nueva acción en el plan.
     */
    public function storeAccion(Request $request, \App\Models\PlanMaestro\PlanMaestro $plan)
    {
        $data = $request->validate([
            'eje_id'        => 'required|exists:plan_ejes,id',
            'momento'       => 'required|string|max:5',
            'accion'        => 'required|string',
            'justificacion' => 'nullable|string',
            'kpi'           => 'nullable|string',
            'plazo'         => 'nullable|string|max:200',
            'responsable'   => 'nullable|string|max:200',
            'estado'        => 'nullable|string|max:100',
            'detalle'       => 'nullable|string',
        ]);

        $eje    = \App\Models\PlanMaestro\PlanEje::findOrFail($data['eje_id']);
        $ultimo = $plan->acciones()->max('orden') ?? 0;
        $codigo = $eje->codigo . '-' . str_pad($plan->acciones()->whereHas('eje', fn($q) => $q->where('codigo', $eje->codigo))->count() + 1, 2, '0', STR_PAD_LEFT);

        $accion = $plan->acciones()->create(array_merge($data, [
            'plan_id' => $plan->id,
            'codigo'  => $codigo,
            'orden'   => $ultimo + 1,
            'estado'  => $data['estado'] ?? 'PENDIENTE',
        ]));

        return response()->json(['ok' => true, 'accion_id' => $accion->id]);
    }

    /**
     * Elimina una acción.
     */
    public function destroyAccion(\App\Models\PlanMaestro\PlanAccion $accion)
    {
        $accion->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Crea o actualiza una Iniciativa de Mejora vinculada directamente a un nodo del PEI (Acción PEI).
     */
    public function storeIniciativa(Request $request)
    {
        $data = $request->validate([
            'iniciativa_id'  => 'nullable|exists:plan_acciones,id',
            'pei_profile_id' => 'required|exists:planificacion.pei_profiles,id',
            'eje_id'         => 'nullable|exists:plan_ejes,id',
            'momento'        => 'required|string|max:10',
            'accion'         => 'required|string',
            'justificacion'  => 'nullable|string',
            'kpi'            => 'nullable|string',
            'plazo'          => 'nullable|string|max:200',
            'responsable'    => 'nullable|string|max:200',
            'estado'         => 'required|string|max:100',
            'detalle'        => 'nullable|string',
        ]);

        if (!empty($data['iniciativa_id'])) {
            $iniciativa = \App\Models\PlanMaestro\PlanAccion::findOrFail($data['iniciativa_id']);
            $iniciativa->update([
                'momento'     => $data['momento'],
                'accion'      => $data['accion'],
                'kpi'         => $data['kpi'] ?? null,
                'plazo'       => $data['plazo'] ?? null,
                'responsable' => $data['responsable'] ?? null,
                'estado'      => strtoupper($data['estado']),
                'detalle'     => $data['detalle'] ?? null,
            ]);

            return response()->json([
                'ok'         => true,
                'iniciativa' => $iniciativa,
                'mensaje'    => 'Iniciativa de Mejora actualizada exitosamente.'
            ]);
        }

        $plan = \App\Models\PlanMaestro\PlanMaestro::firstOrCreate(
            ['activo' => true],
            [
                'nombre'      => 'Plan de Gestión 2026',
                'institucion' => 'Instituto de Previsión Social',
                'descripcion' => 'Primeros 100 días + Hoja de Ruta 9 meses',
                'periodo'     => '2026',
            ]
        );

        $ejeId = $data['eje_id'] ?? \App\Models\PlanMaestro\PlanEje::where('plan_id', $plan->id)->first()?->id;
        if (!$ejeId) {
            $eje = \App\Models\PlanMaestro\PlanEje::create([
                'plan_id' => $plan->id,
                'codigo'  => 'A',
                'nombre'  => 'Gobernanza',
                'color'   => '#2a9d8f',
                'icono'   => 'fa-landmark',
            ]);
            $ejeId = $eje->id;
        }

        $ejeObj = \App\Models\PlanMaestro\PlanEje::find($ejeId);
        $prefix = $ejeObj->codigo ?? 'M';
        $count  = \App\Models\PlanMaestro\PlanAccion::where('eje_id', $ejeId)->count() + 1;
        $codigo = $prefix . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);

        $iniciativa = \App\Models\PlanMaestro\PlanAccion::create([
            'plan_id'        => $plan->id,
            'eje_id'         => $ejeId,
            'pei_profile_id' => $data['pei_profile_id'],
            'codigo'         => $codigo,
            'momento'        => $data['momento'],
            'accion'         => $data['accion'],
            'justificacion'  => $data['justificacion'] ?? null,
            'kpi'            => $data['kpi'] ?? null,
            'plazo'          => $data['plazo'] ?? null,
            'responsable'    => $data['responsable'] ?? null,
            'estado'         => strtoupper($data['estado']),
            'detalle'        => $data['detalle'] ?? null,
            'orden'          => \App\Models\PlanMaestro\PlanAccion::max('orden') + 1,
        ]);

        return response()->json([
            'ok'         => true,
            'iniciativa' => $iniciativa,
            'mensaje'    => 'Iniciativa de Mejora agregada exitosamente.'
        ]);
    }
}