<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Globales\Group;
use App\Models\Planificacion\PeiChatMessage;
use App\Models\Planificacion\PeiChatRead;
use App\Models\User;

class PeiChatController extends Controller
{
    /**
     * Check if user has access to the PEI Profile's chat.
     */
    protected function checkUserAccess(PeiProfile $peiProfile, User $user): bool
    {
        // Administradores y Analistas de cualquier tipo tienen acceso siempre
        if ($user->hasAnyRole(['Administrador', 'Analista PEI', 'Analista de Planificación', 'Analista de Monitoreo PEI', 'Analista'])) {
            return true;
        }

        // Creador del PEI tiene acceso
        if ($peiProfile->user_id == $user->id) {
            return true;
        }

        // Analistas asignados al PEI tienen acceso
        if ($peiProfile->analysts && $peiProfile->analysts->contains('id', $user->id)) {
            return true;
        }

        // Si el PEI está vinculado a un grupo
        if ($peiProfile->group_id) {
            $group = Group::find($peiProfile->group_id);
            if ($group) {
                // Obtener grupo raíz + grupos descendientes usando la sintaxis estática de NestedSet
                $groupIds = Group::descendantsAndSelf($group->id)->pluck('id')->toArray();

                $isMember = DB::table('groups_has_members')
                    ->whereIn('group_id', $groupIds)
                    ->where('user_id', $user->id)
                    ->exists();

                if ($isMember) {
                    return true;
                }
            }
        }

        // Acceso por Actividades asociadas a este PEI (Responsables o Grupos de Trabajo de la Actividad)
        $activities = \App\Admin\Globales\Activity::where('pei_profile_id', $peiProfile->id)->get();
        foreach ($activities as $act) {
            // Es responsable directo de la actividad
            if ($act->responsibles->contains('id', $user->id)) {
                return true;
            }
            // Pertenece al Grupo de Trabajo (padre o hijo) asignado a la actividad
            if ($act->group_id) {
                $actGroupIds = Group::descendantsAndSelf($act->group_id)->pluck('id')->toArray();
                $isActGroupMember = DB::table('groups_has_members')
                    ->whereIn('group_id', $actGroupIds)
                    ->where('user_id', $user->id)
                    ->exists();
                if ($isActGroupMember) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Fetch messages and chat metadata for a PEI Profile.
     */
    public function getMessages(Request $request, $peiProfileId)
    {
        $user = auth()->user();
        $peiProfile = PeiProfile::findOrFail($peiProfileId);

        if (!$this->checkUserAccess($peiProfile, $user)) {
            return response()->json(['error' => 'No tienes permiso para acceder al chat de este PEI.'], 403);
        }

        $query = PeiChatMessage::where('pei_profile_id', $peiProfileId)
            ->where(function ($q) use ($user) {
                $q->whereNull('recipient_id')
                  ->orWhere('user_id', $user->id)
                  ->orWhere('recipient_id', $user->id);
            })
            ->with(['user:id,name,email', 'recipient:id,name', 'parent.user:id,name'])
            ->orderBy('created_at', 'asc');

        if ($request->has('since')) {
            $query->where('created_at', '>', $request->since);
        }

        $messages = $query->get()->map(function ($msg) use ($user) {
            return [
                'id' => $msg->id,
                'pei_profile_id' => $msg->pei_profile_id,
                'user_id' => $msg->user_id,
                'recipient_id' => $msg->recipient_id,
                'recipient_name' => $msg->recipient ? $msg->recipient->name : null,
                'is_private' => !is_null($msg->recipient_id),
                'user_name' => $msg->user ? $msg->user->name : 'Usuario Desconocido',
                'user_initials' => $msg->user ? mb_substr($msg->user->name, 0, 2) : 'US',
                'is_mine' => $msg->user_id === $user->id,
                'message' => e($msg->message),
                'attachments' => $msg->attachments ?? [],
                'is_system' => $msg->is_system,
                'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
                'time_ago' => $msg->created_at->diffForHumans(),
                'parent' => $msg->parent ? [
                    'id' => $msg->parent->id,
                    'user_name' => $msg->parent->user ? $msg->parent->user->name : 'Usuario',
                    'message' => Str::limit($msg->parent->message, 40),
                ] : null,
            ];
        });

        // Get group participants info
        $participants = [];
        if ($peiProfile->group_id) {
            $group = Group::find($peiProfile->group_id);
            if ($group) {
                $groupIds = Group::descendantsAndSelf($group->id)->pluck('id')->toArray();
                $participants = User::whereIn('id', function ($q) use ($groupIds) {
                    $q->select('user_id')->from('groups_has_members')->whereIn('group_id', $groupIds);
                })
                ->where('id', '!=', $user->id)
                ->select('id', 'name', 'email')->get();
            }
        }

        // Update read receipt
        $latestMessage = $messages->last();
        if ($latestMessage) {
            PeiChatRead::updateOrCreate(
                ['pei_profile_id' => $peiProfileId, 'user_id' => $user->id],
                ['last_read_message_id' => $latestMessage['id']]
            );
        }

        return response()->json([
            'messages' => $messages,
            'participants' => $participants,
            'pei_name' => $peiProfile->name,
        ]);
    }

    /**
     * Store a new chat message.
     */
    public function storeMessage(Request $request, $peiProfileId)
    {
        $user = auth()->user();
        $peiProfile = PeiProfile::findOrFail($peiProfileId);

        if (!$this->checkUserAccess($peiProfile, $user)) {
            return response()->json(['error' => 'Acceso denegado.'], 403);
        }

        $request->validate([
            'message' => 'nullable|string|max:5000',
            'recipient_id' => 'nullable|exists:users,id',
            'parent_id' => 'nullable|uuid|exists:pei_chat_messages,id',
            'files.*' => 'nullable|file|max:10240', // 10MB limit per file
        ]);

        if (empty(trim($request->message)) && !$request->hasFile('files')) {
            return response()->json(['error' => 'El mensaje no puede estar vacío.'], 422);
        }

        $attachments = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $path = $file->store("public/pei_chat_attachments/{$peiProfileId}");
                $url = Storage::url($path);

                $attachments[] = [
                    'name' => $originalName,
                    'url' => $url,
                    'ext' => strtolower($ext),
                    'size' => number_format($file->getSize() / 1024, 1) . ' KB',
                ];
            }
        }

        $msg = PeiChatMessage::create([
            'pei_profile_id' => $peiProfileId,
            'user_id' => $user->id,
            'recipient_id' => $request->recipient_id ?: null,
            'parent_id' => $request->parent_id,
            'message' => $request->message,
            'attachments' => $attachments,
            'is_system' => false,
        ]);

        $msg->load(['user:id,name', 'recipient:id,name', 'parent.user:id,name']);

        // Mark as read for sender
        PeiChatRead::updateOrCreate(
            ['pei_profile_id' => $peiProfileId, 'user_id' => $user->id],
            ['last_read_message_id' => $msg->id]
        );

        $payload = [
            'id' => $msg->id,
            'pei_profile_id' => $msg->pei_profile_id,
            'user_id' => $msg->user_id,
            'recipient_id' => $msg->recipient_id,
            'recipient_name' => $msg->recipient ? $msg->recipient->name : null,
            'is_private' => !is_null($msg->recipient_id),
            'user_name' => $user->name,
            'user_initials' => mb_substr($user->name, 0, 2),
            'is_mine' => true,
            'message' => e($msg->message),
            'attachments' => $msg->attachments ?? [],
            'is_system' => false,
            'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
            'time_ago' => 'Hace un momento',
            'parent' => $msg->parent ? [
                'id' => $msg->parent->id,
                'user_name' => $msg->parent->user ? $msg->parent->user->name : 'Usuario',
                'message' => Str::limit($msg->parent->message, 40),
            ] : null,
        ];

        return response()->json([
            'success' => true,
            'message' => $payload,
        ]);
    }

    /**
     * Get unread message count for a PEI Profile.
     */
    public function getUnreadCount($peiProfileId)
    {
        $user = auth()->user();
        $peiProfile = PeiProfile::find($peiProfileId);

        if (!$peiProfile || !$this->checkUserAccess($peiProfile, $user)) {
            return response()->json(['unread' => 0]);
        }

        $read = PeiChatRead::where('pei_profile_id', $peiProfileId)
            ->where('user_id', $user->id)
            ->first();

        $query = PeiChatMessage::where('pei_profile_id', $peiProfileId)
            ->where('user_id', '!=', $user->id);

        if ($read && $read->last_read_message_id) {
            $lastRead = PeiChatMessage::find($read->last_read_message_id);
            if ($lastRead) {
                $query->where('created_at', '>', $lastRead->created_at);
            }
        }

        return response()->json([
            'unread' => $query->count(),
        ]);
    }
}
