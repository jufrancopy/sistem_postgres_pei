<?php

namespace App\Http\Controllers\Admin\Planificacion\Pei;

use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\Pei;
use App\Admin\Planificacion\Pei\PeiPerfil;
use App\ClasesPersonalizadas\Table;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class PeiController extends Controller
{
    public function index()
    {
        $niveles = Pei::whereNull('nivel_id')->get();

        return view('admin.planificacion.peis.peis.index', get_defined_vars());
    }


    public function create()
    {
        $dependencies = Organigrama::pluck('dependency', 'id');
        $dependenciesChecked = [];

        return view('admin.planificacion.peis.peis.create', get_defined_vars());
    }

    public function addSubNivel(Request $request, $idNivelSuperior, $id)
    {
        $idNivelSuperior;
        $nivel_id = $request->id;
        $nivel = Pei::where('id', $nivel_id)->get();


        $dependencies = Organigrama::pluck('dependency', 'id');
        $dependenciesChecked = [];


        return view('admin.planificacion.peis.peis.crear_sub_nivel', get_defined_vars());
    }

    public function editarSubNivel(Request $request, $idSubNivel)
    {

        $idSubNivel;
        $nivel_id = $request->id;
        $nivel = Pei::find($idSubNivel);

        $dependencies = Organigrama::pluck('dependency', 'id');
        $dependenciesChecked = [];

        foreach ($nivel->dependencies as $v) {
            $dependenciesChecked[] = $v->id;
        }


        return view('admin.planificacion.peis.peis.editar_sub_nivel', get_defined_vars());
    }


    public function store(Request $request)
    {

        $nivelSuperior = $request->idNivelSuperior;
        $nivel = Pei::create($request->except(['dependency_id', 'idNivelSuperior']));
        $nivel->save();

        $nivel->dependencies()->attach($request->dependency_id);

        if (!is_null) {
            return redirect()->route('peis.show', $nivelSuperior);
        } else
            return redirect()->route('peis.index');
    }


    public function show($id)
    {
        $niveles = Pei::where('nivel_id', '=', $id)
            ->with('childrenNiveles')
            ->get();

        $nivelSuperior = $id;
        $pei = Pei::find($id);

        return view('admin.planificacion.peis.peis.show', get_defined_vars());
    }


    public function verCuadroDeMando(Request $request, $id)
    {

        $query = DB::table('planificacion.peis as p')
            ->select(DB::raw('p.id, p.nivel_id, p.tipo, p.nivel, ARRAY[p.id] as ruta, 0 as deph'))
            ->whereNull('p.nivel_id')
            ->unionAll(
                DB::table('planificacion.peis as p')
                    ->select(DB::raw('p.id,p.nivel_id, p.tipo, p.nivel,
                t.ruta || ARRAY[p.id] as ruta, t.deph + 1 as deph'))
                    ->join('tree as t', 't.id', '=', 'p.nivel_id')
            );

        $collection = DB::table('tree')
            ->select(['*', DB::raw('array_to_json(ruta) as path')])
            ->withRecursiveExpression('tree', $query)
            ->whereNotNull('nivel_id')
            ->orderBy('ruta')
            ->get();

        $closure = (function ($item) use (&$closure, $collection) {
            $key = $item->keys()->shift();
            if (!empty($key) || $key === 0) {
                if (!isset($collection[$key]->defined)) {
                    $collection[$key]->defined = true;
                    $collection[$key]->rowspan = 1;
                } else {
                    $collection[$key]->rowspan++;
                }

                $closure($collection->where('id', $collection[$key]->nivel_id));
            }
        });

        $collection->reverse()->map(function ($item, $key) use ($closure, $collection) {
            $item->last = false;
            $last = $collection->last();
            if (($last->id != $item->id && $collection[$key + 1]->deph <= $item->deph)
                || $last->id == $item->id
            ) {
                $item->last = true;
                $item->colspan = $collection->max('deph') - $item->deph + 1;
                $closure($collection->where('id', $item->nivel_id));
            }

            unset($item->defined);
        });
        return view('admin.planificacion.peis.tableros.index', get_defined_vars());
    }

    public function edit(Request $request, $id)
    {
        $nivel = Pei::find($id);
        $nivel_id = $nivel->nivel_id;

        $dependencies = Organigrama::pluck('dependency', 'id');
        $dependenciesChecked = [];

        foreach ($nivel->dependencies as $v) {
            $dependenciesChecked[] = $v->id;
        }

        return view('admin.planificacion.peis.peis.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $nivel = Pei::find($id);
        $nivel->fill($request->except('dependency_id'))->save();

        // Actualizar la tabla pivot
        $nivel->dependencies()->sync($request->dependency_id);

        return back()->with('info', 'Actualizado correctamente');
    }

    public function eliminarNivel(Request $request, $idNivelSuperior, $idNivel)
    {
        $nivel = Pei::find($idNivel);
        $nivel->delete();
        $niveles = Pei::whereNull('nivel_id')->get();

        if (is_null($idNivelSuperior)) {
            return view('admin.planificacion.peis.peis.index', get_defined_vars());
        } else {
            return redirect()->route('peis.show', $idNivelSuperior);
        }
    }

    public function destroy($id)
    {

        $nivel = Pei::find($id);
        $nivel->delete();

        $niveles = Pei::whereNull('nivel_id')->get();


        return view('admin.planificacion.peis.peis.index', get_defined_vars());
    }
}
