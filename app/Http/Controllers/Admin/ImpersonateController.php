<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Iniciar la impersonación de un usuario objetivo.
     */
    public function take(Request $request, $id)
    {
        $currentUser = Auth::user();

        // Permitir solo a Administradores (o a un administrador en sesión activa)
        if (!$currentUser->hasRole('Administrador') && !session()->has('impersonator_id')) {
            return redirect()->route('planificacion-dashboard')
                ->with('error', 'No tenés permisos para simular vistas de otros usuarios.');
        }

        $targetUser = User::findOrFail($id);

        // Guardar el ID del Administrador original si no existe aún en la sesión
        if (!session()->has('impersonator_id')) {
            session(['impersonator_id' => $currentUser->id]);
        }

        // Si el objetivo es la misma cuenta admin original, simplemente salimos del modo
        if (session('impersonator_id') == $targetUser->id) {
            return $this->leave();
        }

        Auth::login($targetUser);

        $rolesStr = $targetUser->roles->pluck('name')->implode(', ') ?: 'Sin rol asignado';

        return redirect()->route('planificacion-dashboard')
            ->with('success', "Modo Vista Previa activo: estás navegando como {$targetUser->name} ({$rolesStr})");
    }

    /**
     * Salir del modo impersonación y volver a la cuenta de Administrador.
     */
    public function leave()
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->to(route('globales.dashboard') . '#tab-usuarios');
        }

        $adminId = session('impersonator_id');
        session()->forget('impersonator_id');

        $adminUser = User::findOrFail($adminId);
        Auth::login($adminUser);

        return redirect()->to(route('globales.dashboard') . '#tab-usuarios')
            ->with('info', 'Has vuelto exitosamente a tu cuenta de Administrador.');
    }

    /**
     * Listar usuarios con sus roles para el modal de vista previa rápida.
     */
    public function listUsers(Request $request)
    {
        if (!Auth::user()->hasRole('Administrador') && !session()->has('impersonator_id')) {
            return response()->json([], 403);
        }

        $search = $request->get('q');
        $query = User::with('roles')->where('id', '!=', session('impersonator_id') ?: Auth::id());

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->take(50)->get();

        $data = $users->map(function($u) {
            $roleNames = $u->roles->pluck('name')->implode(', ') ?: 'Sin rol';
            return [
                'id'    => $u->id,
                'name'  => $u->name,
                'email' => $u->email,
                'roles' => $roleNames,
            ];
        });

        return response()->json($data);
    }
}
