<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditQueryService;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditoriaController extends Controller
{
    public function index(Request $request, AuditQueryService $query): View|JsonResponse
    {
        $logs = $query->paginate($request);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $logs->getCollection()->map(fn (AuditLog $log) => $query->toArray($log)),
                'meta' => [
                    'current_page' => $logs->currentPage(),
                    'last_page' => $logs->lastPage(),
                    'per_page' => $logs->perPage(),
                    'total' => $logs->total(),
                ],
            ]);
        }

        return view('admin.bioestadistica.auditoria.index', [
            'logs' => $logs,
            'actions' => AuditLog::ACTIONS,
            'actors' => User::query()
                ->whereIn('id', AuditLog::query()->whereNotNull('user_id')->distinct()->pluck('user_id'))
                ->orderBy('name')
                ->get(['id', 'name']),
            'entityTypes' => AuditLog::query()
                ->select('entity_type')
                ->distinct()
                ->orderBy('entity_type')
                ->pluck('entity_type'),
            'filters' => $request->only(['entity_type', 'accion', 'user_id', 'entity_id', 'desde', 'hasta']),
        ]);
    }

    public function show(Request $request, AuditLog $auditoria, AuditQueryService $query): View|JsonResponse
    {
        $auditoria->load('actor:id,name,email');

        if ($request->wantsJson()) {
            return response()->json($query->toArray($auditoria));
        }

        $keys = collect(array_keys($auditoria->old_values ?? []))
            ->merge(array_keys($auditoria->new_values ?? []))
            ->unique()
            ->values();

        return view('admin.bioestadistica.auditoria.show', [
            'log' => $auditoria,
            'keys' => $keys,
        ]);
    }
}
