<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Admin\Globales\Activity;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\ActivityTaskEvidence;
use App\Admin\Globales\ActivityTaskComment;
use App\Notifications\ActividadTareaNotification;
use App\Notifications\ActividadComentarioNotification;

class ActivityController extends Controller
{
    // ── Actividades ──────────────────────────────────────────────

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Activity::with(['responsibles', 'peiProfile', 'group'])->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('pei_profile', function (Activity $a) {
                    if (!$a->peiProfile) return '<span class="text-muted small">—</span>';
                    return '<span class="badge badge-light text-dark font-weight-normal border"><i class="fa fa-bullseye text-info mr-1"></i>' . e(strip_tags($a->peiProfile->name)) . '</span>';
                })
                ->addColumn('group', function (Activity $a) {
                    if (!$a->group) return '<span class="text-muted small">—</span>';
                    return '<span class="badge badge-warning text-dark font-weight-normal border"><i class="fa fa-users mr-1"></i>' . e($a->group->name) . '</span>';
                })
                ->addColumn('responsibles', fn(Activity $a) => $a->responsibles->pluck('name')->implode(', '))
                ->addColumn('action', function ($row) {
                    $btn  = '<a href="' . route('globales.activities.show', $row->id) . '" class="btn btn-info btn-circle" title="Ver Tablero"><i class="fas fa-columns"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-circle editActivity"><i class="far fa-edit"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-circle deleteActivity"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['pei_profile', 'group', 'action'])
                ->make(true);
        }

        $groups = \App\Admin\Globales\Group::orderBy('name')->get();
        return view('admin.globales.activities.index', compact('groups'));
    }

    public function getPeiProfiles(Request $request)
    {
        $search = $request->get('q');
        $query = \App\Admin\Planificacion\Pei\PeiProfile::whereNull('parent_id')
            ->where('level', 'master')
            ->where('type', 'corporative');

        if ($search) {
            $query->where('name', 'ILIKE', "%{$search}%");
        }

        $profiles = $query->orderBy('name')->take(40)->get();

        $results = $profiles->map(function ($p) {
            $yearStr = $p->year_start ? ' (' . \Carbon\Carbon::parse($p->year_start)->format('Y') . ')' : '';
            return [
                'id'   => $p->id,
                'text' => strip_tags($p->name) . $yearStr,
            ];
        });

        return response()->json($results);
    }

    public function show($id)
    {
        $activity = Activity::with([
            'responsibles',
            'peiProfile',
            'group',
            'tasks.assignedTo',
            'tasks.completedBy',
            'tasks.evidences',
            'tasks.comments',
        ])->findOrFail($id);
        return view('admin.globales.activities.show', compact('activity'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required'], ['name.required' => 'El nombre es requerido']);

        $activity = Activity::updateOrCreate(
            ['id' => $request->activity_id],
            [
                'name'           => $request->name,
                'type'           => $request->type,
                'description'    => $request->description,
                'date_start'     => $request->date_start,
                'date_end'       => $request->date_end,
                'pei_profile_id' => $request->pei_profile_id ?: null,
                'group_id'       => $request->group_id ?: null,
            ]
        );

        $activity->responsibles()->sync($request->responsible_id ?? []);

        return response()->json([
            'success'  => $activity->wasRecentlyCreated ? 'Actividad creada' : 'Actividad actualizada',
            'activity' => $activity,
        ]);
    }

    public function edit($id)
    {
        $activity = Activity::with(['responsibles', 'peiProfile', 'group'])->findOrFail($id);
        $responsiblesChecked = $activity->responsibles->map(fn($r) => ['id' => $r->id, 'text' => $r->name]);
        $peiProfileSelected  = $activity->peiProfile ? [
            'id'   => $activity->peiProfile->id,
            'text' => strip_tags($activity->peiProfile->name) . ($activity->peiProfile->year_start ? ' (' . \Carbon\Carbon::parse($activity->peiProfile->year_start)->format('Y') . ')' : ''),
        ] : null;

        return response()->json([
            'activity'            => $activity,
            'responsiblesChecked' => $responsiblesChecked,
            'peiProfileSelected'  => $peiProfileSelected,
        ]);
    }

    public function destroy($id)
    {
        Activity::findOrFail($id)->delete();
        return response()->json(['success' => 'Eliminado correctamente']);
    }

    // ── Tareas del tablero ────────────────────────────────────────

    public function reasignarTarea(Request $request, $taskId)
    {
        $task = ActivityTask::findOrFail($taskId);
        $user = Auth::user();

        if (!$user->hasAnyRole(['Administrador', 'Gestor de Actividades']) && $task->assigned_to !== $user->id) {
            return response()->json(['error' => 'Sin permiso'], 403);
        }

        $task->update(['assigned_to' => $request->assigned_to]);

        return response()->json(['success' => 'Tarea reasignada', 'task' => $task->load('assignedTo')]);
    }

    public function storeTarea(Request $request, $activityId)
    {
        $request->validate(['title' => 'required'], ['title.required' => 'El título es requerido']);

        $payload = [
            'activity_id'       => $activityId,
            'title'             => $request->title,
            'details'           => $request->details,
            'etiqueta'          => $request->etiqueta,
            'color'             => $request->color ?? '#6b7280',
            'fecha_inicio'     => $request->fecha_inicio ?: null,
            'fecha_vencimiento' => $request->fecha_vencimiento ?: null,
            'assigned_to'       => $request->assigned_to,
            'status'            => $request->status ?? 0,
            'es_reunion'        => $request->boolean('es_reunion'),
        ];

        if (!$request->task_id) {
            $payload['created_by'] = Auth::id();
        }

        $task = ActivityTask::updateOrCreate(
            ['id' => $request->task_id ?: null],
            $payload
        );

        if ($task->wasRecentlyCreated && Auth::user()) {
            $peiProfileId = $task->activity?->pei_profile_id;
            app(\App\Services\GamificationService::class)->awardPoints(
                Auth::user(),
                'task_created',
                'Creación de tarea: ' . \Illuminate\Support\Str::limit($task->title, 30),
                15,
                $task,
                $peiProfileId
            );
        }

        return response()->json(['success' => 'Tarea guardada', 'task' => $task->load('assignedTo')]);
    }

    public function updateStatus(Request $request, $taskId)
    {
        $task      = ActivityTask::findOrFail($taskId);
        $newStatus = (int) $request->status;

        // Colaborador solo puede mover sus propias tareas
        $user = Auth::user();
        if (!$user->hasAnyRole(['Administrador', 'Gestor de Actividades'])
            && $task->assigned_to !== $user->id) {
            return response()->json(['error' => 'Sin permiso para mover esta tarea'], 403);
        }

        // Al completar: requiere nota de cierre
        if ($newStatus === 2) {
            $request->validate(
                ['completion_note' => 'required'],
                ['completion_note.required' => 'Debe ingresar un comentario de cierre']
            );
            $task->update([
                'status'          => 2,
                'completed_at'    => now(),
                'completed_by'    => Auth::id(),
                'completion_note' => $request->completion_note,
            ]);

            if (Auth::user()) {
                $peiProfileId = $task->activity?->pei_profile_id;
                app(\App\Services\GamificationService::class)->awardPoints(
                    Auth::user(),
                    'task_completed',
                    'Tarea completada: ' . \Illuminate\Support\Str::limit($task->title, 30),
                    25,
                    $task,
                    $peiProfileId
                );
            }
        } else {
            // Si retrocede desde completado, limpia los campos
            $task->update([
                'status'          => $newStatus,
                'completed_at'    => null,
                'completed_by'    => null,
                'completion_note' => null,
            ]);
        }

        return response()->json(['success' => 'Estado actualizado']);
    }

    public function destroyTarea($taskId)
    {
        $task = ActivityTask::with('evidences')->findOrFail($taskId);

        // Eliminar archivos físicos de evidencias
        foreach ($task->evidences as $evidence) {
            if ($evidence->type !== 'url' && Storage::exists($evidence->value)) {
                Storage::delete($evidence->value);
            }
        }

        $task->comments()->delete();
        $task->evidences()->delete();
        $task->delete();

        return response()->json(['success' => 'Tarea eliminada']);
    }

    // ── Comentarios ───────────────────────────────────────────────

    public function getComentarios($taskId)
    {
        $task = ActivityTask::with('comments.user')->findOrFail($taskId);
        return response()->json([
            'ok'         => true,
            'task_title' => $task->title,
            'comentarios'=> $task->comments->map(fn($c) => [
                'id'          => $c->id,
                'comentario'  => $c->comentario,
                'autor'       => $c->user->name,
                'initials'    => strtoupper(substr($c->user->name, 0, 2)),
                'fecha'       => $c->created_at->format('d/m H:i'),
                'es_mio'      => $c->user_id === Auth::id(),
            ]),
        ]);
    }

    public function reuniones(int $activityId)
    {
        $activity  = \App\Admin\Globales\Activity::findOrFail($activityId);
        $reuniones = ActivityTask::with(['assignedTo', 'evidences'])
            ->where('activity_id', $activityId)
            ->where('es_reunion', true)
            ->orderBy('fecha_inicio')
            ->get();

        return response()->json($reuniones->map(fn($t) => [
            'id'                => $t->id,
            'title'             => $t->title,
            'details'           => $t->details,
            'etiqueta'          => $t->etiqueta,
            'responsable'       => $t->assignedTo?->name ?? '—',
            'status'            => $t->status,
            'fecha_inicio'      => $t->fecha_inicio?->format('d/m/Y'),
            'fecha_vencimiento' => $t->fecha_vencimiento instanceof \Carbon\Carbon
                ? $t->fecha_vencimiento->format('d/m/Y')
                : $t->fecha_vencimiento,
            'evidencias'        => $t->evidences->map(fn($e) => [
                'id'    => $e->id,
                'type'  => $e->type,
                'label' => $e->label,
                'url'   => $e->type === 'url' ? $e->value : asset('storage/' . $e->value),
                'es_pdf'=> str_ends_with(strtolower($e->value ?? ''), '.pdf'),
            ]),
        ]));
    }

    public function detalleTarea($taskId)
    {
        $task = ActivityTask::with(['assignedTo', 'completedBy', 'evidences.user', 'comments.user'])->findOrFail($taskId);

        $venc = null; $vencColor = null;
        if ($task->fecha_vencimiento && $task->status !== 2) {
            $dias = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($task->fecha_vencimiento)->startOfDay(), false);
            if ($dias < 0)       { $vencColor = '#ef4444'; $venc = 'Vencida hace ' . abs($dias) . 'd'; }
            elseif ($dias === 0) { $vencColor = '#ef4444'; $venc = '¡Vence hoy!'; }
            elseif ($dias <= 3)  { $vencColor = '#f97316'; $venc = 'Vence en ' . $dias . 'd'; }
            else                 { $vencColor = '#22c55e'; $venc = \Carbon\Carbon::parse($task->fecha_vencimiento)->format('d/m/Y'); }
        }

        return response()->json([
            'ok'   => true,
            'data' => [
                'id'               => $task->id,
                'title'            => $task->title,
                'details'          => $task->details,
                'etiqueta'         => $task->etiqueta,
                'color'            => $task->color ?? '#6b7280',
                'status'           => $task->status,
                'es_reunion'       => $task->es_reunion ? 1 : 0,
                'assigned_to'      => $task->assigned_to,
                'responsable'      => $task->assignedTo?->name ?? 'Sin asignar',
                'completion_note'  => $task->completion_note,
                'completed_by'     => $task->completedBy?->name,
                'completed_at'     => $task->completed_at?->format('d/m/Y H:i'),
                'fecha_inicio_raw'      => $task->fecha_inicio?->format('Y-m-d'),
                'fecha_vencimiento_raw' => $task->fecha_vencimiento instanceof \Carbon\Carbon
                    ? $task->fecha_vencimiento->format('Y-m-d')
                    : $task->fecha_vencimiento,
                'fecha_vencimiento'=> $venc,
                'fecha_color'      => $vencColor,
                'evidencias'       => $task->evidences->map(fn($e) => [
                    'id'    => $e->id,
                    'type'  => $e->type,
                    'label' => $e->label,
                    'value' => $e->value,
                    'url'   => $e->type === 'url' ? $e->value : asset('storage/' . $e->value),
                    'autor' => $e->user?->name,
                ]),
                'comentarios' => $task->comments->map(fn($c) => [
                    'id'         => $c->id,
                    'comentario' => $c->comentario,
                    'autor'      => $c->user->name,
                    'initials'   => strtoupper(substr($c->user->name, 0, 2)),
                    'fecha'      => $c->created_at->format('d/m H:i'),
                    'es_mio'     => $c->user_id === Auth::id(),
                ]),
            ],
        ]);
    }

    public function storeComentario(Request $request, $taskId)
    {
        $request->validate(['comentario' => 'required|string|max:1000']);

        $task = ActivityTask::with([
            'activity.responsibles',
            'assignedTo',
            'comments.user',
        ])->findOrFail($taskId);

        $comment = ActivityTaskComment::create([
            'activity_task_id' => $taskId,
            'user_id'          => Auth::id(),
            'comentario'       => $request->comentario,
        ]);
        $comment->load('user');

        $this->notificarComentario($task, $comment);

        if (Auth::user()) {
            $peiProfileId = $task->activity?->pei_profile_id;
            app(\App\Services\GamificationService::class)->awardPoints(
                Auth::user(),
                'comment_created',
                'Comentario en tarea: ' . \Illuminate\Support\Str::limit($task->title, 30),
                5,
                $comment,
                $peiProfileId
            );
        }

        return response()->json([
            'ok'      => true,
            'success' => 'Comentario agregado',
            'item'    => [
                'id'        => $comment->id,
                'comentario'=> $comment->comentario,
                'autor'     => $comment->user->name,
                'initials'  => strtoupper(substr($comment->user->name, 0, 2)),
                'fecha'     => $comment->created_at->format('d/m H:i'),
                'es_mio'    => true,
            ],
        ]);
    }

    public function destroyComentario($commentId)
    {
        $comment = ActivityTaskComment::findOrFail($commentId);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Sin permiso'], 403);
        }
        $comment->delete();
        return response()->json(['ok' => true]);
    }

    private function notificarComentario(ActivityTask $task, ActivityTaskComment $comment): void
    {
        $autorId = Auth::id();
        $destinatarios = collect();

        if ($task->assigned_to && $task->assigned_to !== $autorId && $task->assignedTo?->email) {
            $destinatarios->put($task->assigned_to, $task->assignedTo);
        }

        foreach ($task->activity->responsibles as $responsable) {
            if ($responsable->id !== $autorId && $responsable->email) {
                $destinatarios->put($responsable->id, $responsable);
            }
        }

        foreach ($task->comments as $previo) {
            if ($previo->id !== $comment->id
                && $previo->user_id !== $autorId
                && $previo->user?->email) {
                $destinatarios->put($previo->user_id, $previo->user);
            }
        }

        foreach ($destinatarios as $user) {
            try {
                $user->notify(new ActividadComentarioNotification(
                    $task->activity,
                    $task,
                    $comment,
                    $comment->user->name
                ));
            } catch (\Exception $e) {
                \Log::warning("No se pudo notificar comentario a {$user->email}: " . $e->getMessage());
            }
        }
    }

    // ── Evidencias ────────────────────────────────────────────────

    public function storeEvidencia(Request $request, $taskId)
    {
        $task = ActivityTask::findOrFail($taskId);

        if ($request->type === 'url') {
            $request->validate([
                'label' => 'required',
                'value' => 'required|url',
            ], [
                'label.required' => 'Ingrese una descripción del enlace',
                'value.required' => 'Ingrese la URL',
                'value.url'      => 'La URL no es válida',
            ]);

            $evidence = ActivityTaskEvidence::create([
                'activity_task_id' => $task->id,
                'type'             => 'url',
                'label'            => $request->label,
                'value'            => $request->value,
                'user_id'          => Auth::id(),
            ]);

        } else {
            // Archivo (imagen o documento)
            $isImage   = $request->type === 'image';
            $maxMB     = $isImage ? 2 : 5;
            $maxKB     = $maxMB * 1024;
            $mimes     = $isImage ? 'jpeg,jpg,png,gif,webp' : 'pdf,doc,docx,xls,xlsx';

            $request->validate([
                'file' => "required|file|mimes:{$mimes}|max:{$maxKB}",
            ], [
                'file.required' => 'Seleccione un archivo',
                'file.mimes'    => $isImage ? 'Solo se permiten imágenes (jpg, png, gif, webp)' : 'Solo se permiten PDF, Word o Excel',
                'file.max'      => "El archivo supera los {$maxMB}MB. Suba el documento a su carpeta compartida y registre el enlace.",
            ]);

            $file = $request->file('file');
            $path = $file->store("evidencias/{$task->activity_id}/{$task->id}", 'public');

            $evidence = ActivityTaskEvidence::create([
                'activity_task_id' => $task->id,
                'type'             => $isImage ? 'image' : 'document',
                'label'            => $file->getClientOriginalName(),
                'value'            => $path,
                'user_id'          => Auth::id(),
            ]);
        }

        return response()->json([
            'success'  => 'Evidencia registrada',
            'evidence' => $evidence->load('user'),
        ]);
    }

    public function destroyEvidencia($evidenceId)
    {
        $evidence = ActivityTaskEvidence::findOrFail($evidenceId);

        if ($evidence->type !== 'url' && Storage::disk('public')->exists($evidence->value)) {
            Storage::disk('public')->delete($evidence->value);
        }

        $evidence->delete();

        return response()->json(['success' => 'Evidencia eliminada']);
    }

    // ── Notificaciones ────────────────────────────────────────────

    /**
     * POST /admin/globales/activities/{id}/notificar-todos
     * Envía email a todos los responsables con sus tareas asignadas.
     */
    public function notificarTodos($activityId)
    {
        $activity = Activity::with(['tasks.assignedTo'])->findOrFail($activityId);

        // Agrupar tareas por responsable
        $porResponsable = $activity->tasks
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to');

        $enviados = 0;
        foreach ($porResponsable as $userId => $tareas) {
            $user = $tareas->first()->assignedTo;
            if (!$user || !$user->email) continue;
            try {
                $user->notify(new ActividadTareaNotification($activity, $tareas, 'recordatorio'));
                $enviados++;
            } catch (\Exception $e) {
                \Log::warning("No se pudo notificar a {$user->email}: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => "Notificación enviada a {$enviados} responsable(s).",
            'enviados' => $enviados,
        ]);
    }

    /**
     * POST /admin/globales/activities/tareas/{taskId}/notificar
     * Envía email al responsable de una tarea específica.
     */
    public function notificarTarea($taskId)
    {
        $task = ActivityTask::with(['assignedTo', 'activity'])->findOrFail($taskId);

        if (!$task->assignedTo || !$task->assignedTo->email) {
            return response()->json(['error' => 'La tarea no tiene responsable asignado o no tiene email.'], 422);
        }

        try {
            $task->assignedTo->notify(
                new ActividadTareaNotification(
                    $task->activity,
                    collect([$task]),
                    'tarea_individual'
                )
            );
            return response()->json(['success' => 'Notificación enviada a ' . $task->assignedTo->name]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al enviar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /mis-actividades
     * Lista de actividades donde el analista es responsable o pertenece al Grupo de Trabajo.
     */
    public function misActividades()
    {
        $userId = Auth::id();

        // Grupos de los que forma parte el usuario (incluyendo jerarquía)
        $userGroupIds = \DB::table('groups_has_members')
            ->where('user_id', $userId)
            ->pluck('group_id')
            ->toArray();

        $allGroupIds = [];
        if (!empty($userGroupIds)) {
            foreach ($userGroupIds as $gId) {
                $grp = \App\Admin\Globales\Group::find($gId);
                if ($grp) {
                    $root = method_exists($grp, 'getRoot') ? $grp->getRoot() : $grp;
                    $desc = \App\Admin\Globales\Group::descendantsAndSelf($root->id)->pluck('id')->toArray();
                    $allGroupIds = array_merge($allGroupIds, $desc);
                }
            }
            $allGroupIds = array_unique($allGroupIds);
        }

        $actividades = Activity::with(['responsibles', 'tasks', 'group'])
            ->where(function($q) use ($userId, $allGroupIds) {
                $q->whereHas('responsibles', fn($q) => $q->where('users.id', $userId))
                  ->orWhereHas('tasks', fn($q) => $q->where('assigned_to', $userId));

                if (!empty($allGroupIds)) {
                    $q->orWhereIn('group_id', $allGroupIds);
                }
            })
            ->latest()->get();

        return view('admin.globales.activities.mis_actividades', compact('actividades', 'userId'));
    }

    /**
     * GET /mis-tareas/{activityId}
     * Vista del colaborador — ve todo el tablero, solo puede mover sus tareas.
     */
    public function misTareas($activityId)
    {
        $activity = Activity::with([
            'responsibles',
            'tasks.assignedTo',
            'tasks.completedBy',
            'tasks.evidences',
            'tasks.comments',
        ])->findOrFail($activityId);

        $userId = Auth::id();

        return view('admin.globales.activities.mis_tareas', compact('activity', 'userId'));
    }
}
