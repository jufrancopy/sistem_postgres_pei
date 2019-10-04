<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
Use App\User;

class AccesoController extends Controller

{
  public function __construct()
  {
    // Esto indica que para ver contenido de este controlador debe estar logueado
    $this->middleware('auth');
  }

  public function index()
  {
    $totalRoles = Role::count();
    $totalPermisos = Permission::count();
    $totalUsuarios = User::count();

    return view('admin.index', get_defined_vars());
  }
}
