<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditQueryService;
use App\Http\Controllers\Admin\Bioestadistica\Concerns\RespondsWithDataTables;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditoriaController extends Controller
{
    use RespondsWithDataTables;

    public function index(Request $request, AuditQueryService $query): View
    {
        return view('admin.bioestadistica.auditoria.index', [
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

    public function datatable(Request $request, AuditQueryService $query): JsonResponse
    {
        $base = AuditLog::query()
            ->with('actor:id,name,email')
            ->when($request->filled('entity_type'), function ($q) use ($request) {
                $type = (string) $request->string('entity_type');
                $q->where(function ($inner) use ($type) {
                    $inner->where('entity_type', $type)
                        ->orWhere('entity_type', 'like', '%\\'.$type);
                });
            })
            ->when($request->filled('accion'), fn ($q) => $q->where('accion', $request->string('accion')))
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->integer('user_id')))
            ->when($request->filled('entity_id'), fn ($q) => $q->where('entity_id', $request->integer('entity_id')))
            ->when($request->filled('desde'), fn ($q) => $q->whereDate('created_at', '>=', $request->date('desde')))
            ->when($request->filled('hasta'), fn ($q) => $q->whereDate('created_at', '<=', $request->date('hasta')));

        return $this->dataTablesJson(
            $request,
            $base,
            function ($q, string $search): void {
                $q->where(function ($inner) use ($search) {
                    $inner->where('accion', 'ilike', "%{$search}%")
                        ->orWhere('entity_type', 'ilike', "%{$search}%")
                        ->orWhere('ip', 'ilike', "%{$search}%")
                        ->orWhere('entity_id', 'like', "%{$search}%")
                        ->orWhereHas('actor', fn ($actors) => $actors->where('name', 'ilike', "%{$search}%"));
                });
            },
            [
                0 => 'created_at',
                1 => null,
                2 => 'accion',
                3 => 'entity_type',
                4 => 'ip',
                5 => null,
            ],
            function (AuditLog $log) {
                return [
                    'fecha' => e(optional($log->created_at)->format('d/m/Y H:i:s') ?? '—'),
                    'actor' => e($log->actor->name ?? 'Sistema'),
                    'accion' => '<span class="badge badge-info">'.e($log->accion).'</span>',
                    'entidad' => e(class_basename($log->entity_type).' #'.$log->entity_id),
                    'ip' => e($log->ip ?: '—'),
                    'acciones' => '<div class="bio-actions"><a class="btn btn-outline-primary btn-sm" href="'.e(route('bioestadistica.auditoria.show', $log)).'">Detalle</a></div>',
                ];
            },
            'created_at',
            'desc'
        );
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
