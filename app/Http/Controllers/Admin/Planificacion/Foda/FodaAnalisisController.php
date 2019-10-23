<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade as PDF;

use App\Admin\Foda\FodaAspecto;
use App\Admin\Foda\FodaCategoria;
use App\Admin\Foda\FodaPerfil;
use App\Admin\Foda\FodaAnalisis;

class FodaAnalisisController extends Controller
{
    //antes de procesar el index() ejecuta el metodo consturctor
    function __construct()
    {
         $this->middleware('permission:role-list');
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $analizados = FodaAnalisis::orderBy('id', 'ASC')->get();
        $aspectos = $analizados->aspectos()->orderBy('nombre', 'ASC')->get();

        // Crear un array vacio para almacenar aspectos.
        $aspectosChecked = [];

        // Obtener las aspectos relacionados al analisis.
        foreach ($analizados->aspectos as $aspecto) {

            // Acumular las aspectos en el array '$aspectosChecked'.
            $aspectosChecked[] = $aspecto->id;
        }
        return view('admin.fodas.analisis.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function listadoPerfiles(Request $request)
    {

        $perfiles = FodaPerfil::nombre($request->get('nombre'))->orderBy('id', 'DESC')->paginate(10);

        return view('admin.fodas.analisis.perfiles', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function seleccionarAmbiente(Request $request, $id)
    {
        $perfil = FodaPerfil::find($id);
        $categorias = $perfil->categorias()->orderBy('nombre', 'ASC')->get();
        
        $categoriasChecked = [];
        
        foreach ($perfil->categorias as $categoria) {
            $categoriasChecked[] = $categoria->id;
        }

        return view('admin.fodas.analisis.ambientes', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    

    public function aspectosCategoriaEdit(Request $request)
    {
        $idPerfil = $request->idPerfil;
        $idCategoria = $request->idCategoria;
        $categoria = FodaCategoria::find($idCategoria);
        $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)->get();
        // $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)->get();
        
        //Array de aspectos asociados al perfil y la categoria
        $aspectos = FodaAspecto::where('categoria_id', '=', $idCategoria)->get();
            if ($analisis->isNotEmpty()){
                $aspectosChecked=[];
                    foreach ($analisis as $v) {
                        $aspectosChecked[] = $v->aspecto->id;
                    }
                    return view('admin.fodas.analisis.aspectos-edit', get_defined_vars())
                    ->with('i', ($request->input('page', 1) - 1) * 5);
            } else {
                // colección vacía
                return view('admin.fodas.analisis.error', get_defined_vars())
                    ->with('i', ($request->input('page', 1) - 1) * 5);
            }
    
    }
    public function matriz(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;    
        $perfil = FodaPerfil::find($idPerfil); 
        $matriz =    0.17; 

        //Ambiente Interno - Debilidad
        $debilidades = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Debilidad')
            ->get();
        
        //Ambiente Interno - Fortaleza
        $fortalezas = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Fortaleza')
            ->get();
        
        //Ambiente Externo - Oportunidad
        $oportunidades = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Oportunidad')
            ->get();
            
        //Ambiente Externo - Amenaza
        $amenazas = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Amenaza')
            ->get();    

        return view('admin.fodas.analisis.matriz', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function matrizPdf(Request $request, $idPerfil)
    {        
       
        $idPerfil = $request->idPerfil;
        $perfil = FodaPerfil::find($idPerfil);    
        $matriz =    0.17; 
        
        //Ambiente Interno - Debilidad
        $debilidades = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Debilidad')
            ->get();
        
        //Ambiente Interno - Fortaleza
        $fortalezas = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Fortaleza')
            ->get();
        
        //Ambiente Externo - Oportunidad
        $oportunidades = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Oportunidad')
            ->get();
            
        //Ambiente Externo - Amenaza
        $amenazas = FodaAnalisis::
            where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Amenaza')
            ->get(); 
            
        $pdf = PDF::loadView('admin.fodas.analisis.matriz-pdf', get_defined_vars());
        
        return $pdf->download('matriz.pdf');
    }
    
    public function listadoCategoriaAspectos(Request $request)
    {
        $idPerfil = $request->idPerfil;
        $idCategoria = $request->idCategoria;
        $categoria = FodaCategoria::find($idCategoria);
        $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)->where('categoria_id', '=', $idCategoria)
            ->join('foda_aspectos', 'foda_analisis.aspecto_id', '=', 'foda_aspectos.id')
            ->join('foda_categorias', 'foda_aspectos.categoria_id', '=', 'foda_categorias.id')
            ->select('foda_analisis.*', 'foda_aspectos.nombre', 'foda_categorias.nombre', 'foda_categorias.ambiente')
            ->with('aspecto')
            ->get();
        $perfil = FodaPerfil::find($idPerfil);
        
        if ($analisis->isNotEmpty()) {
            // colección no está vacía
            return view('admin.fodas.analisis.listado-categorias-aspectos', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            // colección vacía
            return view('admin.fodas.analisis.error', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    }

    public function analisisCategoriasAmbienteInterno(Request $request, $idPerfil)
    {
        $analisis = FodaAnalisis::where('perfil_id', $idPerfil)->get();
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categorias()->nombre($request->get('nombre'))->where('ambiente', 'Interno')->paginate(20);
        
        foreach ($categorias as $categoria){
            $v [] = $aspectos=FodaAspecto::where('categoria_id', $categoria->id)->get();
        }

        return view('admin.fodas.analisis.analisis-categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function analisisCategoriasAmbienteExterno(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categorias()->nombre($request->get('nombre'))->where('ambiente', 'Externo')->paginate(20);
        
        return view('admin.fodas.analisis.analisis-categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function categoriasAmbienteInterno(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categorias()->where('ambiente', 'Interno')->get();

        return view('admin.fodas.analisis.categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function categoriasAmbienteExterno(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categorias()->where('ambiente', 'Interno')->get();

        return view('admin.fodas.analisis.categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function aspectosCategoria(Request $request, $idPerfil, $idCategoria)
    {
        
        $idPerfil = $request->idPerfil;
        $idCategoria = $request->idCategoria;
        $categoria = FodaCategoria::find($idCategoria);
        $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)->get();
        
        //Array de aspectos asociados al perfil y la categoria
        $aspectos = FodaAspecto::where('categoria_id', '=', $idCategoria)->get();
        
        $aspectosChecked=[];
        
        foreach ($analisis as $v) {
            $aspectosChecked[] = $v->aspecto->id;
        }
        
        return view('admin.fodas.analisis.aspectos', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $idPerfil = $request->idPerfil;
        $idAspecto = $request->idAspecto;
        $perfil = FodaPerfil::where('id', '=', $idPerfil)->get();
        $aspecto = FodaAspecto::where('id', '=', $idAspecto)->get();
        $categoriaId = $aspecto[0]->categoria_id;
        $ambiente = FodaCategoria::where('id', '=', $categoriaId)->get();

        return view('admin.fodas.analisis.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $count = count($request->input('aspecto_id'));
        for ($i = 0; $i < $count; ++$i) {

            $analisis = FodaAnalisis::create([
                'user_id'       => $request->user_id,
                'aspecto_id'    => $request->aspecto_id[$i],
                'perfil_id'     => $request->perfil_id,
                'tipo'          => $request->tipo,
                'ocurrencia'    => $request->ocurrencia,
                'impacto'       => $request->impacto,
            ]);
        }
        
        $idPerfil = $analisis->perfil_id;
        $aspectoID = $analisis->aspecto_id;
        $aspectos = FodaAspecto::find($aspectoID);
        $categoriaID = $aspectos->categoria_id;
        $categoria = FodaCategoria::find($categoriaID); 
        
        return redirect()->route('foda-listado-categorias-aspectos', ['idCategoria' => $categoria->id, 'idPerfil' => $idPerfil])
            ->with('success', 'Aspectos Listos para analizar');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $analizados = FodaAnalisis::where('aspecto_id', '=', $id)->get();
        return view('admin.fodas.analisis.ponderaciones', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function ponderaciones(Request $request, $idAspecto, $idPerfil)
    {
        $idAspecto = $request->idAspecto;
        $idPerfil = $request->idPerfil;
        $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)->where('aspecto_id', '=', $idAspecto)->get();
        $aspecto = FodaAspecto::where('id', '=', $idAspecto)->get();
        $categoriaId = $aspecto[0]->categoria_id;
        $ambiente = FodaCategoria::where('id', '=', $categoriaId)->get();

        return view('admin.fodas.analisis.edit', get_defined_vars());
    }

    public function edit(Request $request, $id)
    {
        $analisis = FodaAnalisis::find($id);
        $aspectoID = $analisis->aspecto_id;
        $aspectos = FodaAspecto::find($aspectoID);
        $categoriaID = $aspectos->categoria_id;
        $categoria = FodaCategoria::find($categoriaID); 
        $ambiente = $categoria->ambiente;
        
        return view('admin.fodas.analisis.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $input = $request->all();
        // $i = 0;
        // $count = count($input['aspecto_id']);
        // while($i < $count){

        //     $data[] = array(
        //         'user_id'       => $request->user_id,
        //         'aspecto_id'    => $request->aspecto_id[$i],
        //         'perfil_id'     => $request->perfil_id,
        //         'tipo'          => $request->tipo,
        //         'ocurrencia'    => $request->ocurrencia,
        //         'impacto'       => $request->impacto,
        //     );
        //     $i++;
        // }

        // $j = 0;
        // $count1 = count($input['aspecto_id']);
        // while($j < $count1){
        //     FodaAnalisis::where('aspecto_id',$data[$j]['aspecto_id'])->updateOrCreate($data[$j]);
        //     $j++;
        // }
        $analisis = FodaAnalisis::find($id);
        $idPerfil = $analisis->perfil_id;
        $aspectoID = $analisis->aspecto_id;
        $aspectos = FodaAspecto::find($aspectoID);
        $categoriaID = $aspectos->categoria_id;
        $categoria = FodaCategoria::find($categoriaID); 
        $analisis->fill($request->all())->save();
            

             return redirect()->route('foda-listado-categorias-aspectos', ['idCategoria' => $categoria->id, 'idPerfil' => $idPerfil])
            ->with('success', 'Analizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FodaAnalisis::find($id)->delete();
        return back()->with('success', 'Aspecto eliminado correctamente.');
    }
}
