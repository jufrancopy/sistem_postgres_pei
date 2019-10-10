<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

use App\Admin\Foda\Foda;

class FodaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function fodaAmbInterno()
    {

        $fodas = Foda::get()->groupBy('nivel');

        return view('admin.fodas.dashboard', get_defined_vars());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fodas = Foda::orderBy('aspecto', 'ASC')->get();
        return view('admin.fodas.index', get_defined_vars());
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
        $id = Foda::where('id', '=', $id)->first();
        $nivel = $id->nivel;
        $fodas = Foda::where('nivel', '=', $nivel)->get();

        return view('admin.fodas.listado', get_defined_vars());
    }

    public function analisisFortaleza(Request $request, $id)
    {
        $foda = Foda::find($id);
        
        return view('admin.fodas.fortaleza_edit', get_defined_vars());
    }

    public function analisisDebilidad(Request $request, $id)
    {
        $foda = Foda::find($id);
        
        return view('admin.fodas.debilidad_edit', get_defined_vars());
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

        $foda = Foda::find($id);
        $foda->fill($request->all())->save();
        $fodas = Foda::where('nivel', '=', $request->nivel)->get();
        
        return view('admin.fodas.listado', get_defined_vars());
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
