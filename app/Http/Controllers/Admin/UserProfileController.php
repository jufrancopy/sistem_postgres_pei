<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\HomeConfiguration;
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

        // Lista de Planes PEI principales para selector de filtro
        $peiPlanes = PeiProfile::whereIsRoot()->where('type', 'corporative')->orderByDesc('year_start')->get();

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
            'peiPlanes',
            'gamification',
            'leaderboard'
        ));
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
}
