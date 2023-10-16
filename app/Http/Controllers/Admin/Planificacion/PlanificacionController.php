<?php

namespace App\Http\Controllers\Admin\Planificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Pei\PeiProfile;

class PlanificacionController extends Controller
{
    public function dashboard()
    {

        $totalPeiPerfil = PeiProfile::count();
        $totalFodaPerfil = FodaPerfil::count();


        return view('admin.planificacion.dashboard', get_defined_vars());
    }
}
