<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    // ── Listado principal ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $roles       = Role::with('permissions')->orderBy('id', 'DESC')->paginate(20);
        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.index', compact('roles', 'permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    // ── AJAX: datos para editar ──────────────────────────────────────────────
    public function editAjax($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'role'            => $role,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    // ── AJAX: datos para ver permisos ────────────────────────────────────────
    public function showAjax($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json([
            'role'        => $role->only('id', 'name'),
            'permissions' => $role->permissions->map(fn($p) => ['id' => $p->id, 'name' => $p->name]),
        ]);
    }

    // ── Crear ────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique'   => 'Ya existe un rol con ese nombre.',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission', []));

        if ($request->ajax()) {
            return response()->json([
                'ok'      => true,
                'message' => 'Rol creado correctamente.',
            ]);
        }

        return redirect()->route('globales.roles.index')->with('success', 'Rol creado satisfactoriamente');
    }

    // ── Actualizar ───────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique'   => 'Ya existe un rol con ese nombre.',
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission', []));

        if ($request->ajax()) {
            return response()->json([
                'ok'      => true,
                'message' => 'Rol actualizado correctamente.',
            ]);
        }

        return redirect()->route('globales.roles.index')->with('success', 'Rol actualizado satisfactoriamente');
    }

    // ── Eliminar ─────────────────────────────────────────────────────────────
    public function destroy(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        if ($request->ajax()) {
            return response()->json(['ok' => true, 'message' => 'Rol eliminado.']);
        }

        return redirect()->route('globales.roles.index')->with('success', 'Rol eliminado satisfactoriamente');
    }

    // ── Helpers para AJAX externos (sin cambio) ───────────────────────────────
    public function getRoles()
    {
        return Role::orderBy('id', 'DESC')->get();
    }

    public function getRole($idUser)
    {
        return Role::findOrFail($idUser);
    }

    // Mantener compatibilidad con rutas legacy (show/create/edit redirigen al index)
    public function create()
    {
        return redirect()->route('globales.roles.index');
    }

    public function show($id)
    {
        return redirect()->route('globales.roles.index');
    }

    public function edit($id)
    {
        return redirect()->route('globales.roles.index');
    }

    // ── Guía Institucional de Roles y Permisos ────────────────────────────────
    public function guide()
    {
        return view('admin.roles.guide');
    }
}
