<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Gamification\GamificationPoint;
use App\Services\GamificationService;
use App\Notifications\PuntosManualNotification;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\PeiProfileEdit;

class GamificationAdminController extends Controller
{
    /**
     * Recalcula retroactivamente los puntos e insignias de gamificación para todos los usuarios o uno en específico.
     */
    /**
     * Historial de puntos manuales para DataTables.
     */
    public function historialManual(Request $request, $idProfile)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            return response()->json(['data' => []], 403);
        }

        $query = GamificationPoint::with('user')
            ->where('pei_profile_id', $idProfile)
            ->where('action_type', 'manual_admin')
            ->orderByDesc('created_at');

        $total = $query->count();

        $rows = $query->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get()
            ->map(fn($p) => [
                'fecha'       => $p->created_at->format('d/m/Y H:i'),
                'funcionario' => $p->user?->name ?? '—',
                'motivo'      => $p->description,
                'puntos'      => $p->points,
                'id'          => $p->id,
            ]);

        return response()->json([
            'draw'            => intval($request->input('draw', 1)),
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $rows,
        ]);
    }

    /**
     * Busca usuarios por nombre para el Select2 de puntos manuales.
     * Solo devuelve usuarios del árbol del grupo raíz (evento) del PEI.
     */
    public function buscarUsuarios(Request $request, $idProfile)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            return response()->json([], 403);
        }

        $pei = PeiProfile::find($idProfile);
        if (!$pei || !$pei->group_id) {
            return response()->json([]);
        }

        $group = \App\Admin\Globales\Group::find($pei->group_id);
        if (!$group) {
            return response()->json([]);
        }

        $groupIds = \App\Admin\Globales\Group::descendantsAndSelf($group->id)->pluck('id')->toArray();

        // Usuarios por group_id directo en users
        $porGroupId = User::whereIn('group_id', $groupIds)->pluck('id');

        // Usuarios por tabla pivot groups_has_members
        $porPivot = \DB::table('groups_has_members')
            ->whereIn('group_id', $groupIds)
            ->pluck('user_id');

        $userIds = $porGroupId->merge($porPivot)->unique()->values();

        $q = $request->get('q', '');
        $users = User::whereIn('id', $userIds)
            ->when($q, fn($query) => $query->where('name', 'ILIKE', "%{$q}%"))
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name']);

        return response()->json($users);
    }

    /**
     * Otorga puntos manuales a un funcionario en el contexto de un PEI.
     */
    public function awardManual(Request $request, $idProfile)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            return response()->json(['message' => 'Sin permisos.'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'motivo'  => 'required|string|min:3|max:255',
            'puntos'  => 'required|in:5,10,50',
        ]);

        $user = User::findOrFail($request->user_id);

        $point = app(GamificationService::class)->awardPoints(
            $user,
            'manual_admin',
            $request->motivo,
            (int) $request->puntos,
            null,
            $idProfile
        );

        if (!$point) {
            return response()->json(['message' => 'No se pudo registrar el punto.'], 422);
        }

        // Notificación en sistema
        $pei = PeiProfile::find($idProfile);

        \App\Models\SystemNotification::crearPuntosManual(
            userId:              $user->id,
            puntos:              (int) $request->puntos,
            motivo:              $request->motivo,
            peiNombre:           $pei ? strip_tags($pei->name) : 'Plan PEI',
            adminNombre:         Auth::user()->name,
            gamificationPointId: $point->id
        );

        // Enviar notificación por email al funcionario
        try {
            $user->notify(new PuntosManualNotification(
                puntos:      (int) $request->puntos,
                motivo:      $request->motivo,
                peiNombre:   $pei ? strip_tags($pei->name) : 'Plan PEI',
                adminNombre: Auth::user()->name
            ));
        } catch (\Exception) {
            // No interrumpir si el mail falla
        }

        return response()->json([
            'success'   => true,
            'user_name' => $user->name,
            'puntos'    => $request->puntos,
            'motivo'    => $request->motivo,
        ]);
    }

    /**
     * Devuelve los motivos manuales ya usados en este PEI para autocomplete.
     */
    public function motivosSugeridos($idProfile)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            return response()->json([], 403);
        }

        $motivos = GamificationPoint::where('pei_profile_id', $idProfile)
            ->where('action_type', 'manual_admin')
            ->orderByDesc('created_at')
            ->limit(50)
            ->pluck('description')
            ->unique()
            ->values();

        return response()->json($motivos);
    }

    /**
     * Devuelve los editores de un nodo PEI con conteo y última edición.
     * Usado para visualizar quién ha editado cada elemento del plan.
     */
    public function editoresPorNodo(Request $request, $profileId)
    {
        $edits = PeiProfileEdit::with('user:id,name')
            ->where('pei_profile_id', $profileId)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('user_id')
            ->map(fn($group) => [
                'user_id'       => $group->first()->user_id,
                'name'          => $group->first()->user?->name ?? '—',
                'total_edits'   => $group->count(),
                'ultima_edicion'=> $group->first()->created_at->format('d/m/Y H:i'),
            ])
            ->values();

        return response()->json($edits);
    }

    public function recalculate(Request $request)
    {
        if (!Auth::user()->hasRole('Administrador')) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos de Administrador para recalcular puntos.'
            ], 403);
        }

        try {
            $params = ['--reset' => true];
            if ($request->filled('user_id')) {
                $params['--user'] = $request->input('user_id');
            }

            // Limpiar notificaciones de puntos manuales cuyo punto ya no existe
            \App\Models\SystemNotification::where('tipo', 'puntos_manual')
                ->whereNotNull('gamification_point_id')
                ->whereNotIn('gamification_point_id', GamificationPoint::where('action_type', 'manual_admin')->pluck('id'))
                ->delete();

            Artisan::call('gamification:recalculate', $params);
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => '¡Recálculo completado exitosamente! Los puntos e insignias de todos los equipos han sido actualizados con los parámetros correctos.',
                'output'  => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al recalcular puntos: ' . $e->getMessage()
            ], 500);
        }
    }
}
