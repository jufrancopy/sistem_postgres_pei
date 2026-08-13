<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserTelemetryController extends Controller
{
    /**
     * Obtener informe analítico y de telemetría de un funcionario para el modal in-situ.
     */
    public function getTelemetry($id)
    {
        $user = User::with(['roles', 'group'])->findOrFail($id);

        $isOnline = $user->isOnline() || (auth()->id() == $user->id);
        $points   = $user->gamification_points;

        $activitiesQuery = UserActivity::where('user_id', $user->id)
            ->where('description', 'NOT LIKE', '%/siess/notificaciones%')
            ->where('description', 'NOT LIKE', '%/telemetry%')
            ->where('description', 'NOT LIKE', '%/get-dependencies%')
            ->where('description', 'NOT LIKE', '%/notifications%');
        $totalActivities = (clone $activitiesQuery)->count();
        $latestActivity  = (clone $activitiesQuery)->latest()->first();

        // Desglose por módulos
        $modulesBreakdown = (clone $activitiesQuery)
            ->selectRaw('module, COUNT(*) as count')
            ->groupBy('module')
            ->pluck('count', 'module');

        // Cronología reciente de las últimas 12 acciones
        $timeline = (clone $activitiesQuery)
            ->latest()
            ->take(12)
            ->get()
            ->map(function ($act) {
                return [
                    'id'          => $act->id,
                    'module'      => strtoupper($act->module),
                    'action'      => strtoupper($act->action),
                    'description' => $act->description,
                    'ip'          => $act->ip_address,
                    'fecha'       => Carbon::parse($act->created_at)->format('Y-m-d H:i:s'),
                    'hace'        => Carbon::parse($act->created_at)->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'user'    => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'avatar_url'  => $user->avatar_url,
                'group_name'  => $user->group->name ?? '— Sin grupo asignado —',
                'roles'       => $user->roles->pluck('name'),
                'is_online'   => $isOnline,
                'points'      => $points,
            ],
            'kpis' => [
                'total_activities' => $totalActivities,
                'last_activity'    => $latestActivity ? Carbon::parse($latestActivity->created_at)->diffForHumans() : 'Sin registros',
                'last_ip'          => $latestActivity->ip_address ?? '—',
            ],
            'modules_breakdown' => $modulesBreakdown,
            'timeline'          => $timeline,
        ]);
    }
}
