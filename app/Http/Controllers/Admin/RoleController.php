<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    // ── Listado principal ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::with('permissions')->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function($row) {
                    return $row->name;
                })
                ->addColumn('permisos', function ($row) {
                    $permCount = $row->permissions->count();
                    $colorBadge = $permCount > 10 ? 'badge-danger' : ($permCount > 5 ? 'badge-warning' : ($permCount > 0 ? 'badge-success' : 'badge-secondary'));
                    return '<button class="btn btn-sm btn-link p-0 btnVerPermisos" data-id="' . $row->id . '" data-nombre="' . e($row->name) . '" title="Ver permisos">' .
                           '<span class="badge ' . $colorBadge . ' font-weight-bold px-2 py-1">' . $permCount . ' ' . ($permCount == 1 ? 'permiso' : 'permisos') . '</span>' .
                           '</button>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('role-edit')) {
                        $btn .= '<button class="btn btn-primary btn-circle btnEditarRol" data-id="' . $row->id . '" title="Editar"><i class="far fa-edit"></i></button>';
                    }
                    if (auth()->user()->can('role-delete')) {
                        $btn .= ' <button class="btn btn-danger btn-circle btnEliminarRol" data-id="' . $row->id . '" data-nombre="' . e($row->name) . '" title="Eliminar"><i class="fa fa-trash"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'permisos'])
                ->make(true);
        }

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

    // ── Crear / Guardar ───────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if ($request->filled('role_id')) {
            return $this->update($request, $request->role_id);
        }

        $request->validate([
            'name' => 'required|unique:roles,name',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique'   => 'Ya existe un rol con ese nombre.',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        if ($request->has('permission')) {
            $role->syncPermissions($request->input('permission', []));
        }

        if ($request->ajax()) {
            return response()->json([
                'ok'      => true,
                'role'    => $role,
                'message' => 'Rol creado correctamente.',
            ]);
        }

        return redirect()->route('globales.roles.index')->with('success', 'Rol creado satisfactoriamente');
    }

    // ── Actualizar ───────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($request->has('name')) {
            $request->validate([
                'name' => 'required|unique:roles,name,' . $id,
            ], [
                'name.required' => 'El nombre del rol es obligatorio.',
                'name.unique'   => 'Ya existe un rol con ese nombre.',
            ]);
            $role->name = $request->input('name');
            $role->save();
        }

        if ($request->has('permission')) {
            $role->syncPermissions($request->input('permission', []));
        }

        if ($request->ajax()) {
            return response()->json([
                'ok'      => true,
                'role'    => $role,
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
