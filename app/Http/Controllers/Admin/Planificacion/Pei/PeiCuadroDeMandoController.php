<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use App\Admin\Planificacion\Pei\PeiPerfil;
use App\Admin\Planificacion\Pei\PeiObjetivo;
use App\Admin\Planificacion\Pei\PeiEstrategia;
use App\Admin\Planificacion\Pei\PeiPrograma;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeiCuadroDeMandoController extends Controller
{
    public function verCuadroDeMando(Request $request, $id){
        $perfil = PeiPerfil::find($id);
        
        
        // $tableros = DB::table('planificacion.pei_perfiles')
        //     ->join('planificacion.pei_objetivos', 'pei_perfiles.id', '=', 'pei_objetivos.perfil_id')
        //     ->join('planificacion.pei_estrategias', 'pei_objetivos.id', '=', 'pei_estrategias.objetivo_id')
        //     ->join('planificacion.pei_programas', 'pei_estrategias.id', '=', 'pei_programas.estrategia_id')
        //     ->select('pei_perfiles.*', 
        //         'pei_objetivos.objetivo', 
        //         'pei_estrategias.estrategia', 
        //         'pei_programas.programa'
        //         )
        //     ->get();
        // dd($perfil);
        // $perfil =   DB::table('planificacion.pei_perfiles')
        //     ->join('planificacion.pei_objetivos', function ($join) 
        //         {
        //             $join->on('pei_perfiles.id', '=', 'pei_objetivos.perfil_id');
        //         })
        //     ->get();    

        
        $objetivos = PeiObjetivo::where('perfil_id', $perfil->id)->get();
        $estrategias = PeiEstrategia::where('objetivo_id', $objetivos[0]->id)->get();

        return view('admin.planificacion.peis.tableros.index', get_defined_vars());
    }
}
