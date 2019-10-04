<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Organigrama;

class OrganigramaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $consejo = Organigrama::where('cod', '=', 'ca')->get();
        $gerencias = Organigrama::where('orden', '=', 0)->where('cod','!=', 'ca')->get();
        
        //Gerencia de Desarrollo y Tecnologia   
        $gdt = Organigrama::where('cod', '=', 'gdt')->take(1)->get();
        $dgdts = Organigrama::where('cod', '=', 'gdt' )->where('orden','!=', 0)->get();
        
        //Gerencia Administrativa y Financiera
        $gaf = Organigrama::where('cod', '=', 'gaf')->take(1)->get();
        $dgafs = Organigrama::where('cod', '=', 'gaf' )->where('orden','!=', 0)->get();
        
        //Gerencia de Salud
        $gs = Organigrama::where('cod', '=', 'gs')->take(1)->get();
        $dgss = Organigrama::where('cod', '=', 'gs' )->where('orden','!=', 0)->get();
        
        //Gerencia de Abastecimiento y Logistica
        $gal = Organigrama::where('cod', '=', 'gal')->take(1)->get();
        $dgals = Organigrama::where('cod', '=', 'gal' )->where('orden','!=', 0)->get();
        
        //Gerencia de Prestaciones Economicas del Seguro Social
        $gpess = Organigrama::where('cod', '=', 'gpess')->take(1)->get();
        $dgpesss = Organigrama::where('cod', '=', 'gpess' )->where('orden','!=', 0)->get();;
        



        return view('admin.organigramas.index', get_defined_vars());
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
    public function edit($id)
    {
        //
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
        //
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
