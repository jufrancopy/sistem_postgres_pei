<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Admin\Globales\Organigrama;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

Use App\User;


class GlobalesController extends Controller
{
    public function dashboard()
    {
        $totalUsuarios = User::count();
        $totalDependencias = Organigrama::whereNull('dependency_id')->count();

        return view('admin.globales.dashboard', get_defined_vars());
    }
}
