<?php

namespace App\Http\Controllers\Admin\Globales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Kalnoy\Nestedset\NodeTrait;
use App\Admin\Globales\Organigrama;


class OrganigramaController extends Controller
{
    public function index(Request $request)
    {
        // $dependencias = Organigrama::defaultOrder()->ancestorsOf($id);
        
        // $dependencias = Organigrama::withDepth()->with('descendants')->get()->toTree();
        $dependencias = Organigrama::defaultOrder()->get();
        // $dependencias = Organigrama::withDepth(0)->get();
        // $dependencias = Organigrama::reversed()->get();
        // $dependencias = Organigrama::withDepth()->having('depth', '=', 0)->get();
        // $depth = $dependencias->depth;

        //  $dependencias = Organigrama::get()->toTree();
        //  $dependencias = Organigrama::get()->toFlatTree();

        

        return view('admin.globales.organigramas.index', get_defined_vars())
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function verOrganigrama($id)
    {
        $dependencias = Organigrama::whereNull('dependency_id')
            ->where('id', $id)
            ->with('childrenDependencies')
            ->get();

        return view('admin.globales.organigramas.organigrama', get_defined_vars());
    }

    public function crearSubDependencia(Request $request, $idDependencia)
    {
        $dependencia_id = $request->idDependencia;
        $dependencia = Organigrama::where('dependency_id', $dependencia_id)->get();

        return view('admin.globales.organigramas.crear_sub_dependencia', get_defined_vars());
    }

    public function editarSubDependencia(Request $request, $idDependencia)
    {
        $idDependencia = $request->idDependencia;
        $dependencia = Organigrama::find($idDependencia);
        $dependencia_id = $dependencia->dependency_id;

        return view('admin.globales.organigramas.editar_sub_dependencia', get_defined_vars());
    }

    public function create(Request $request)
    {
        $parents = Organigrama::pluck('dependency', 'id');

        return view('admin.globales.organigramas.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        
        $dependecie = Organigrama::create([
            'dependency' => $request->dependency,
            'responsable' => $request->responsable,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        if ($request->parent) {
            $node = Organigrama::find($request->parent);
            $node->appendNode($dependecie);
        }

        return back()->with('success', 'Dependencia creada correctamente.');
    }

    public function show($id)
    {   
        // $dependencia = Organigrama::withDepth()->get();
        $dependencia = Organigrama::findOrFail($id)->with('descendants')->get()->toTree();
        // $dependencias = Organigrama::withDepth()->with('descendants')->get()->toTree();
        // return $dependencia = Organigrama::findOrFail($id)->get();
        // return $result = Organigrama::defaultOrder()->ancestorsOf(3);
        
        // $traverse = function ($dependencias, $prefix = '-') use (&$traverse) {
        //     foreach ($dependencias as $dependencia) {
        //         echo PHP_EOL . $prefix . ' ' . $dependencia->dependency;

        //         $traverse($dependencia->children, $prefix . '-');
        //     }
        // };

        // $traverse($dependencias);

        return view('admin.globales.organigramas.show', get_defined_vars());
    }

    public function edit($id)
    {
        $dependencia = Organigrama::find($id);
        $idDependencia = $id;

        return view('admin.globales.organigramas.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $dependencia = Organigrama::find($id);
        $dependencia->fill($request->all())->save();

        if ($dependencia->id) {
            return redirect()->route('globales.organigrama-gestionar', $dependencia->id);
        } else {
            return back()->with('success', 'Creado correctamente.');
        }
    }

    public function destroy($id)
    {
        Organigrama::find($id)->delete();

        return back()->with('success', 'Dependencia eliminada correctamente.');
    }
}
