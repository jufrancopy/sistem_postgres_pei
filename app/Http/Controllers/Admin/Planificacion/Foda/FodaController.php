<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use App\Admin\Planificacion\Foda\FodaModelo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class FodaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fodaAmbInterno()
    {

        $fodas = FodaModelo::get()->groupBy('nivel');

        return view('admin.planificacion.fodas.dashboard', get_defined_vars());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fodas = FodaModelo::orderBy('aspecto', 'ASC')->get();
        return view('admin.planificacion.FodaModelos.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */



    public function edit(Request $request, $id)
    {
        $id = FodaModelo::where('id', '=', $id)->first();
        $nivel = $id->nivel;
        $FodaModelos = FodaModelo::where('nivel', '=', $nivel)->get();

        return view('admin.planificacion.fodas.listado', get_defined_vars());
    }

    public function analisisFortaleza(Request $request, $id)
    {
        $FodaModelo = FodaModelo::find($id);

        return view('admin.planificacion.fodas.fortaleza_edit', get_defined_vars());
    }

    public function analisisDebilidad(Request $request, $id)
    {
        $foda = FodaModelo::find($id);

        return view('admin.planificacion.fodas.debilidad_edit', get_defined_vars());
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

        $foda = FodaModelo::find($id);
        $foda->fill($request->all())->save();
        $fodas = FodaModelo::where('nivel', '=', $request->nivel)->get();

        return view('admin.planificacion.fodas.listado', get_defined_vars());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
