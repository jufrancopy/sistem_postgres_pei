<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\PeiAccionReporte;
use App\Models\Planificacion\Indicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeiReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Notificar a TODOS los responsables del PEI ───────────────────────────
    public function notificarTodos(string $profileId)
    {
        $perfil = PeiProfile::findOrFail($profileId);

        // Obtener todas las acciones con sus responsables y sus organigramas con usuario
        $acciones = PeiProfile::with(['indicador', 'responsibles.user'])
            ->whereIn('id', $perfil->descendants()->where('level', 'action')->pluck('id'))
            ->get();

        // Agrupar acciones por usuario responsable
        $porUsuario = collect();
        foreach ($acciones as $accion) {
            foreach ($accion->responsibles as $org) {
                if (!$org->user || !$org->user->email) continue;
                $uid = $org->user->id;
                if (!$porUsuario->has($uid)) {
                    $porUsuario->put($uid, ['user' => $org->user, 'acciones' => collect()]);
                }
                $porUsuario[$uid]['acciones']->push($accion);
            }
        }

        $enviados = 0;
        foreach ($porUsuario as $data) {
            try {
                $data['user']->notify(new \App\Notifications\PeiAccionNotification(
                    $perfil, $data['acciones'], 'general'
                ));
                $enviados++;
            } catch (\Exception $e) {
                \Log::warning('PEI notif error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'ok'      => true,
            'message' => "Notificación enviada a {$enviados} responsable(s).",
            'enviados'=> $enviados,
        ]);
    }

    // ── Notificar solo al responsable de una acción específica ───────────────
    public function notificarAccion(string $profileId, string $accionId)
    {
        $perfil = PeiProfile::findOrFail($profileId);
        $accion = PeiProfile::with(['indicador', 'responsibles.user'])->findOrFail($accionId);

        $enviados = 0;
        foreach ($accion->responsibles as $org) {
            if (!$org->user || !$org->user->email) continue;
            try {
                $org->user->notify(new \App\Notifications\PeiAccionNotification(
                    $perfil, collect([$accion]), 'individual'
                ));
                $enviados++;
            } catch (\Exception $e) {
                \Log::warning('PEI notif accion error: ' . $e->getMessage());
            }
        }

        if ($enviados === 0) {
            return response()->json(['ok' => false, 'message' => 'No hay responsables con email asignado.'], 422);
        }

        return response()->json([
            'ok'      => true,
            'message' => "Notificación enviada a {$enviados} responsable(s) de esta acción.",
        ]);
    }



    // ── Dashboard del Analista de Monitoreo PEI ──────────────────────────────
    public function monitoreDashboard()
    {
        $userId = Auth::id();
        $orgId  = \App\Admin\Globales\Organigrama::where('user_id', $userId)->value('id');

        $planes = collect();
        $totalAcciones = 0;
        $totalReportadas = 0;

        if ($orgId) {
            $profileIds = \DB::table('planificacion.peis_profiles_has_responsibles')
                ->where('responsible_id', $orgId)
                ->pluck('profile_id');

            $masterIds = PeiProfile::whereIn('id', $profileIds)
                ->get()
                ->map(fn($p) => PeiProfile::where('_lft', '<=', $p->_lft)
                    ->where('_rgt', '>=', $p->_rgt)
                    ->where('level', 'master')
                    ->value('id'))
                ->filter()->unique()->values();

            $planes = PeiProfile::whereIn('id', $masterIds)
                ->orderBy('year_start', 'desc')
                ->get()
                ->map(function($plan) use ($orgId, $userId, $profileIds) {
                    // Acciones de este plan asignadas al usuario
                    $descendantIds = $plan->descendants()->where('level', 'action')->pluck('id')->toArray();
                    $misIds = \DB::table('planificacion.peis_profiles_has_responsibles')
                        ->where('responsible_id', $orgId)
                        ->whereIn('profile_id', $descendantIds)
                        ->pluck('profile_id')->toArray();

                    $reportadas = PeiAccionReporte::where('user_id', $userId)
                        ->whereIn('pei_profile_id', $misIds)
                        ->distinct('pei_profile_id')
                        ->count('pei_profile_id');

                    // Semáforo agregado
                    $acciones = PeiProfile::whereIn('id', $misIds)->get(['semaforo']);
                    $verde    = $acciones->where('semaforo', 'verde')->count();
                    $amarillo = $acciones->where('semaforo', 'amarillo')->count();
                    $rojo     = $acciones->where('semaforo', 'rojo')->count();

                    return [
                        'id'          => $plan->id,
                        'name'        => strip_tags($plan->name),
                        'year_start'  => $plan->year_start,
                        'year_end'    => $plan->year_end,
                        'total'       => count($misIds),
                        'reportadas'  => $reportadas,
                        'sin_reporte' => count($misIds) - $reportadas,
                        'verde'       => $verde,
                        'amarillo'    => $amarillo,
                        'rojo'        => $rojo,
                    ];
                });

            $totalAcciones   = $planes->sum('total');
            $totalReportadas = $planes->sum('reportadas');
        }

        $usuario = Auth::user();
        return view('admin.planificacion.reportes.dashboard', compact(
            'planes', 'totalAcciones', 'totalReportadas', 'usuario'
        ));
    }

    // ── Vista del responsable: ver el PEI y sus acciones asignadas ────────────
    public function misAcciones(string $profileId)
    {
        $profile = PeiProfile::with([
            'children.marcos',
            'children.strategies',
            'children.children.children.indicador',
            'children.children.children.responsibles',
        ])->findOrFail($profileId);

        $userId = Auth::id();

        // Etiquetas de niveles
        $nivelesDefault = ['master'=>'PEI','axi'=>'Nivel 1','goal'=>'Nivel 2','action'=>'Acción'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) $niveles = array_merge($nivelesDefault, $decoded);
        }

        // IDs de acciones donde el usuario es responsable — FILTRADO por este perfil/plan
        $descendantIds = $profile->descendants()->where('level', 'action')->pluck('id')->toArray();

        $misAccionesIds = \DB::table('planificacion.peis_profiles_has_responsibles')
            ->where('responsible_id', function($q) use ($userId) {
                $q->select('id')->from('organigramas')->where('user_id', $userId)->limit(1);
            })
            ->whereIn('profile_id', $descendantIds)
            ->pluck('profile_id')
            ->toArray();

        // Último reporte por acción
        $ultimosReportes = PeiAccionReporte::where('user_id', $userId)
            ->whereIn('pei_profile_id', $misAccionesIds)
            ->orderByDesc('fecha_reporte')
            ->get()
            ->groupBy('pei_profile_id')
            ->map(fn($group) => $group->first());

        $orgRaizId = $this->resolverRaizOrg($profile);

        return view('admin.planificacion.reportes.mis_acciones', compact(
            'profile', 'niveles', 'misAccionesIds', 'ultimosReportes', 'orgRaizId'
        ));
    }

    // ── Listar reportes de una acción ─────────────────────────────────────────
    public function index(string $accionId)
    {
        $accion = PeiProfile::findOrFail($accionId);

        $reportes = PeiAccionReporte::with('usuario')
            ->where('pei_profile_id', $accionId)
            ->orderByDesc('fecha_reporte')
            ->get();

        return response()->json($reportes->map(fn($r) => [
            'id'                => $r->id,
            'fecha_reporte'     => $r->fecha_reporte->format('d/m/Y'),
            'fecha_reporte_raw' => $r->fecha_reporte->format('Y-m-d'),
            'periodo_label'     => $r->periodo_label,
            'valor_numerador'   => $r->valor_numerador,
            'pct_avance'        => $r->pct_avance,
            'semaforo'          => $r->semaforo,
            'descripcion_avance'=> $r->descripcion_avance,
            'evidencia_url'     => $r->evidencia_url,
            'evidencia_label'   => $r->evidencia_label,
            'reportado_por'     => $r->usuario?->name ?? '—',
            'created_at'        => $r->created_at->format('d/m/Y H:i'),
        ]));
    }

    // ── Crear reporte ─────────────────────────────────────────────────────────
    public function store(Request $request, string $accionId)
    {
        $accion = PeiProfile::with('indicador')->findOrFail($accionId);

        $data = $request->validate([
            'fecha_reporte'      => 'required|date',
            'periodo_label'      => 'nullable|string|max:50',
            'valor_numerador'    => 'nullable|numeric',
            'descripcion_avance' => 'nullable|string',
            'evidencia_url'      => 'nullable|string|max:500',
            'evidencia_label'    => 'nullable|string|max:200',
        ]);

        $reporte = new PeiAccionReporte(array_merge($data, [
            'pei_profile_id' => $accionId,
            'user_id'        => Auth::id(),
        ]));

        // Calcular semáforo automáticamente si hay indicador vinculado
        if ($accion->indicador && $request->valor_numerador !== null) {
            $reporte->calcularSemaforo($accion->indicador);
        }

        $reporte->save();

        // Actualizar el semáforo y progress de la acción con el último reporte
        if ($reporte->semaforo && $reporte->semaforo !== 'sin-datos') {
            $accion->update([
                'semaforo' => $reporte->semaforo,
                'progress' => $reporte->valor_numerador ?? $accion->progress,
            ]);
        }

        return response()->json(['ok' => true, 'reporte' => $reporte], 201);
    }

    // ── Editar reporte ────────────────────────────────────────────────────────
    public function update(Request $request, string $accionId, int $id)
    {
        $reporte = PeiAccionReporte::where('pei_profile_id', $accionId)->findOrFail($id);
        $accion  = PeiProfile::with('indicador')->findOrFail($accionId);

        $data = $request->validate([
            'fecha_reporte'      => 'required|date',
            'periodo_label'      => 'nullable|string|max:50',
            'valor_numerador'    => 'nullable|numeric',
            'descripcion_avance' => 'nullable|string',
            'evidencia_url'      => 'nullable|string|max:500',
            'evidencia_label'    => 'nullable|string|max:200',
        ]);

        $reporte->fill($data);

        if ($accion->indicador && $request->valor_numerador !== null) {
            $reporte->calcularSemaforo($accion->indicador);
        }

        $reporte->save();

        return response()->json(['ok' => true, 'reporte' => $reporte]);
    }

    // ── Eliminar reporte ──────────────────────────────────────────────────────
    public function destroy(string $accionId, int $id)
    {
        $reporte = PeiAccionReporte::where('pei_profile_id', $accionId)->findOrFail($id);
        $reporte->delete();
        return response()->json(['ok' => true]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function resolverRaizOrg(PeiProfile $profile): ?int
    {
        if ($profile->dependency_id) {
            $nodo = \App\Admin\Globales\Organigrama::find($profile->dependency_id);
            if ($nodo) {
                return $nodo->isRoot() ? $nodo->id
                    : (\App\Admin\Globales\Organigrama::whereAncestorOf($nodo)->whereIsRoot()->first()?->id ?? $nodo->id);
            }
        }
        return \App\Admin\Globales\Organigrama::whereIsRoot()->value('id');
    }
}
