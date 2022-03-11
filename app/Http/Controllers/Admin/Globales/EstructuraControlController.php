<?php

namespace App\Http\Controllers\Admin\Globales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Admin\Globales\EstructuraControl;
use App\Admin\Globales\Organigrama;

class EstructuraControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estructuras = EstructuraControl::orderBy('id', 'ASC')->paginate(10);

        return view('admin.globales.estructuras-control.index', get_defined_vars())
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function getSubDependencias($id)
    {
       return Organigrama::where('dependency_id', $id)->with('childrenDependencies')->get();
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
           
        $dependencias = Organigrama::orderBy('id', 'ASC')->where('dependency', 'like', '%' .'Dirección'. '%')->pluck('dependency', 'id');
        
        $dependencies = Organigrama::orderBy('dependency', 'DESC')->pluck('dependency','id');
        
        $dependenciasChecked=[];

        return view ('admin.globales.estructuras-control.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estructura = EstructuraControl::create($request->except(['dependency_id']));
        $estructura->dependencias()->attach($request->dependency_id);
        
        return redirect()->route('estructuras-control.index')
            ->with('success','Estructura de Control creada satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estructura = EstructuraControl::find($id);
        
        return view('admin.globales.estructuras-control.show', get_defined_vars());

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $estructura = EstructuraControl::find($id);
        $dependencias = Organigrama::orderBy('id', 'ASC')->where('dependency', 'like', '%' .'Dirección'. '%')->pluck('dependency', 'id');
        $dependencies = Organigrama::orderBy('dependency', 'DESC')->pluck('dependency','id');
        
        $dependenciasChecked=[];

         foreach ($estructura->dependencias as $dependencia) {
            $dependenciasChecked[] = $dependencia->id;
        }

        return view ('admin.globales.estructuras-control.edit', get_defined_vars());
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
        $estructura = EstructuraControl::find($id);
        $estructura->dependencias()->sync($request->dependency_id);
        $estructura->fill($request->except(['dependency_id']))->save();        

        return redirect()->route('estructuras-control.index')
        ->with('success', 'Actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EstructuraControl::find($id)->delete();

        return redirect()->route('estructuras-control.index');
    }
}
