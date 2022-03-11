<?php

namespace App\Http\Controllers\Admin\Planificacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Planificacion\Pei\PeiPerfil;
use App\Admin\Planificacion\Foda\FodaPerfil;

class PlanificacionController extends Controller
{
    public function dashboard(){
        
        $totalPeiPerfil = PeiPerfil::count();
        $totalFodaPerfil = FodaPerfil::count();
        

        return view('admin.planificacion.dashboard', get_defined_vars());
    }
}
