<?php

namespace App\Application\Bioestadistica\Audit;

use App\Models\Bioestadistica\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AuditQueryService
{
    public function paginate(Request $request, int $perPage = 30): LengthAwarePaginator
    {
        return AuditLog::query()
            ->with('actor:id,name,email')
            ->when($request->filled('entity_type'), function ($query) use ($request) {
                $type = (string) $request->string('entity_type');
                $query->where(function ($inner) use ($type) {
                    $inner->where('entity_type', $type)
                        ->orWhere('entity_type', 'like', '%\\'.$type);
                });
            })
            ->when($request->filled('accion'), fn ($query) => $query->where('accion', $request->string('accion')))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->when($request->filled('entity_id'), fn ($query) => $query->where('entity_id', $request->integer('entity_id')))
            ->when($request->filled('desde'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('desde')))
            ->when($request->filled('hasta'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('hasta')))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(AuditLog $log): array
    {
        return [
            'id' => $log->id,
            'user_id' => $log->user_id,
            'actor' => $log->actor?->only(['id', 'name', 'email']),
            'accion' => $log->accion,
            'entity_type' => $log->entity_type,
            'entity_id' => $log->entity_id,
            'old_values' => $log->old_values,
            'new_values' => $log->new_values,
            'ip' => $log->ip,
            'user_agent' => $log->user_agent,
            'metadata' => $log->metadata,
            'created_at' => optional($log->created_at)->toIso8601String(),
        ];
    }
}
