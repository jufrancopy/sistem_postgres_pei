<?php

namespace App\Http\Controllers\Admin\Proyectos\EPC;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Proyecto\EPC\Equipamiento;

class EPCController extends Controller
{
    public function getHome()
    {

        $equipamientos = Equipamiento::all();

        return view('admin.proyectos.epc.dashboard', get_defined_vars());
    }
}
