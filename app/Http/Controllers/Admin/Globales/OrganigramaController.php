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
        // $dependencias = Organigrama::whereNull('dependency_id')->paginate(10);

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
        $parents = Organigrama::latest()->get();

        return view('admin.globales.organigramas.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        // $dependencia = Organigrama::create($request->all());

        $parent = Organigrama::create([
            'dependency' => $request->dependency
        ]);
        if($request->parent){   
            $node = Organigrama::find
            $parent
        }

        return back()->with('success', 'Dependencia creada correctamente.');
    }

    public function show($id)
    {
        $dependencias = Organigrama::whereNull('dependency_id')
            ->where('id', $id)
            ->with('childrenDependencies')
            ->get();

        $id = $id;

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
