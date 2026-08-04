<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\HomeConfiguration;
use App\Models\Gamification\GamificationPoint;
use App\Services\GamificationService;

class UserProfileController extends Controller
{
    protected GamificationService $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->middleware('auth');
        $this->gamificationService = $gamificationService;
    }

    /**
     * Muestra el perfil del usuario con la jerarquía y gamificación estilo Stack Overflow
     */
    public function show(Request $request, $id = null)
    {
        $targetUser = $id ? User::with('group')->findOrFail($id) : Auth::user();
        $config     = HomeConfiguration::first();

        // PEI seleccionado en filtro o configurado
        $selectedPeiId = $request->pei_id ?? $config?->pei_profile_id;
        $peiSeleccionado = $selectedPeiId ? PeiProfile::find($selectedPeiId) : null;

        // Lista de Planes PEI corporativos principales para selector de filtro
        $peiPlanes = PeiProfile::whereNull('parent_id')
            ->where('type', 'corporative')
            ->orderByDesc('year_start')
            ->get();

        // Resumen de Gamificación y Jerarquía
        $gamification = $this->gamificationService->getUserGamificationSummary($targetUser, $selectedPeiId);

        // Leaderboard / Tabla de Posiciones para el PEI activo o global (Top 10)
        $leaderboard = User::with('group')
            ->get()
            ->map(function($u) use ($selectedPeiId) {
                $points = $this->gamificationService->getUserTotalPoints($u, $selectedPeiId);
                $u->total_points = $points;
                return $u;
            })
            ->sortByDesc('total_points')
            ->take(10)
            ->values();

        return view('admin.users.profile', compact(
            'targetUser',
            'config',
            'peiSeleccionado',
            'selectedPeiId',
            'peiPlanes',
            'gamification',
            'leaderboard'
        ));
    }

    /**
     * Devuelve el detalle de puntos de un usuario para el modal de la tabla de posiciones
     */
    public function pointsDetails(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $selectedPeiId = $request->pei_id ?? HomeConfiguration::first()?->pei_profile_id;
        $selectedPei = $selectedPeiId ? PeiProfile::find($selectedPeiId) : null;
        $peiName = $selectedPei ? strip_tags($selectedPei->name) : null;

        $pointsQuery = GamificationPoint::where('user_id', $user->id);
        if ($selectedPeiId) {
            $pointsQuery->where(function($q) use ($selectedPeiId) {
                $q->where('pei_profile_id', $selectedPeiId)
                    ->orWhereNull('pei_profile_id');
            });
        }

        $points = $pointsQuery->orderByDesc('created_at')->get()->map(function ($point) {
            return [
                'id' => $point->id,
                'created_at' => $point->created_at,
                'action_type' => $point->action_type,
                'action_type_label' => $point->getActionTypeLabel(),
                'description' => $point->description,
                'points' => $point->points,
                'reference_type' => $point->reference_type,
                'reference_id' => $point->reference_id,
                'reference_valid' => $point->isReferenceValid(),
            ];
        });

        return response()->json([
            'ok' => true,
            'user' => ['id' => $user->id, 'name' => $user->name],
            'selected_pei_id' => $selectedPeiId,
            'pei_name' => $peiName,
            'points' => $points,
        ]);
    }

    /**
     * Sube y actualiza la foto de perfil del usuario
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
        ], [
            'avatar.required' => 'Debe seleccionar una imagen.',
            'avatar.image'    => 'El archivo debe ser una imagen válida.',
            'avatar.mimes'    => 'La imagen debe ser de formato JPG, PNG, GIF o WEBP.',
            'avatar.max'      => 'La imagen no debe superar los 3 MB.',
        ]);

        $user = Auth::user();

        // Eliminar avatar anterior si existe
        if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }

        // Guardar la nueva imagen en public/storage/avatars
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'ok'         => true,
                'message'    => 'Foto de perfil actualizada con éxito',
                'avatar_url' => $user->avatar_url,
            ]);
        }

        return back()->with('success', 'Foto de perfil actualizada con éxito.');
    }

    /**
     * Actualiza la contraseña del usuario
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|string|min:8|confirmed',
        ], [
            'old_password.required' => 'Debe ingresar su contraseña actual.',
            'password.required'     => 'Debe ingresar una nueva contraseña.',
            'password.min'          => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'    => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'La contraseña actual no es correcta.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return back()->with('success_password', 'Contraseña actualizada con éxito.');
    }

    /**
     * Actualiza los datos personales del usuario (nombre y correo electrónico)
     */
    public function updateDetails(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ], [
            'name.required'  => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'Ingrese un correo electrónico válido.',
            'email.unique'   => 'Este correo electrónico ya está registrado por otro usuario.',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success_details', 'Datos personales actualizados con éxito.');
    }
}
