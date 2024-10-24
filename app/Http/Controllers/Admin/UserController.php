<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Administrador']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editUser"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteUser"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->addColumn('roles', function (User $user) {
                    $roleNames = $user->roles->pluck('name')->implode(', ');
                    return $roleNames;
                })
                ->addColumn('group', function ($row) {
                    return $row->group->name ?? '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getUsers(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = User::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }

        return response()->json($data);
    }

    public function dataUser(Request $request, $idSelection)
    {
        $data = User::findOrFail($idSelection);

        return response()->json($data);
    }

    public function getUsersForGroup(Request $request, $idGroup)
    {
        $data = User::where('group_id', $idGroup)->get();

        return response()->json($data);
    }

    public function getRoles(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = User::select("id", "name")
                ->where('name', 'LIKE', "%$search%")
                ->get();
        }

        return response()->json($data);
    }

    public function getRole(Request $request, $idUser)
    {
        $data = Role::findOrFail($idUser);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validationRules = [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->id)->where(function ($query) use ($request) {
                    return $query->where('email', '!=', $request->email); // Ignorar el correo electrónico del usuario actual solo si se cambia
                }),
            ],
            'confirm-password' => 'nullable|same:password',
        ];

        // Añadir la contraseña a los datos del usuario solo si se proporciona una nueva contraseña
        if ($request->filled('password') && $request->password !== '********') {
            $userData['password'] = bcrypt($request->password);
        }

        // Ejecuta la validación
        $request->validate($validationRules, [
            'name.required' => 'Por favor ingrese el nombre del usuario.',
            'email.required' => 'Por favor ingrese el correo electrónico del usuario.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.required' => 'Por favor ingrese una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'confirm-password.same' => 'Las contraseñas no coinciden.',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => $request->group_id
        ];

        // Añadir la contraseña a los datos del usuario solo si se proporciona una nueva contraseña
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user = User::updateOrCreate(
            ['id' => $request->user_id],
            $userData
        );

        // Asignar los roles al usuario
        $user->syncRoles($request->input('roles'));

        // Devolver una respuesta JSON con el mensaje de éxito o error. 
        if ($user) {
            if ($user->wasRecentlyCreated) {
                return response()->json(['success' => 'Usuario creado correctamente.']);
            } else {
                return response()->json(['success' => 'Usuario actualizado correctamente.']);
            }
        } else {
            return response()->json(['error' => 'Ha ocurrido un error al guardar el usuario.'], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $user = User::with('group')->findOrFail($id);

        return response()->json($user);
    }

    public function edit($id)
    {
        $data = [];
        $data['user'] = User::findOrFail($id);
        $password = $data['user']->password;
        if ($password) {
            $data['password'] = 'yes';
        } else {
            $data['password'] = 'no';
        }

        $rolesChecked = [];
        foreach ($data['user']->roles as $role) {
            $rolesChecked[] = ['text' => $role->name, 'id' => $role->id];
        }

        $data['rolesChecked'] = $rolesChecked;

        return response()->json($data);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id)->delete();

        return response()->json([$user]);
    }
}
