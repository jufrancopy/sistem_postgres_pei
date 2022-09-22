<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Admin\Globales\Servicio;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $servicios=Servicio::name($request->get('name'))->orderBy('id','DESC')->paginate(10);

        return view('admin.globales.servicios.index', get_defined_vars())
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.globales.servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $servicio = Servicio::create($request->all());

        return redirect()->route('globales.servicios.index')
            ->with('success', 'Categoria creada satisfactoriamente');
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
        $servicio = Servicio::findOrFail($id);
        return view('admin.globales.servicios.edit', get_defined_vars());
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
        $servicio = Servicio::find($id);
        $servicio->fill($request->all())->save();

        return redirect()->route('globales.servicios.index')->with('info', 'Servicio actulizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $servicio = Servicio::findOrfail($id);
        $servicio->delete();

        return redirect()->route('globales.servicios.index')->with('info', 'Servicio eliminado con éxito');
    }
}
