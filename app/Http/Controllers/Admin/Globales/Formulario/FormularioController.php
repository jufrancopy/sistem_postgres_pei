<?php

namespace App\Http\Controllers\Admin\Globales\Formulario;

use App\Admin\Globales\EstructuraControl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Globales\Formulario\Formulario;
use App\Admin\Globales\Formulario\Item;
use App\Admin\Globales\Formulario\Variable;
use App\Admin\Globales\Organigrama;
use Illuminate\Support\Facades\DB;

class FormularioController extends Controller
{
    public function index(Request $request)
    {
        $formularios = Formulario::orderBy('id', 'ASC')->paginate(10);

        return view('admin.globales.formularios.formularios.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getDependencies(Request $request)
    {

        $data = Organigrama::where('dependency_id', $request->dependency_id)
            ->with('childrenDependencies')
            ->get();

        return response()->json($data);
    }

    public function create()
    {
        $variables = Variable::whereIsRoot()->orderBy('id', 'ASC')->pluck('name', 'id');
        $variablesChecked = [];
        $dependencias = Organigrama::orderBy('id', 'ASC')->pluck('dependency', 'id');

        return view('admin.globales.formularios.formularios.create', get_defined_vars());
    }


    public function store(Request $request)
    {
        $formulario = Formulario::create($request->except(['variable_id']));

        $formulario->variables()->attach($request->variable_id);

        return redirect()->route('globales.formularios.index')
            ->with('success', 'Formulario creado exitosamente!');
    }

    public function show(Request $request, $id)
    {
        $formulario = Formulario::find($id);
        $dependencia = Organigrama::where('id', $formulario->dependencia_receptor_id)->with('descendants')->first();


        $query = DB::table('estadistica.formulario_variables as p')
            ->select(DB::raw('p.id, p.parent_id, p.type, p.name, ARRAY[p.id] as ruta, 0 as deph'))
            ->whereNull('p.parent_id')
            ->unionAll(
                DB::table('estadistica.formulario_variables as p')
                    ->select(DB::raw('p.id,p.parent_id, p.type, p.name,
                t.ruta || ARRAY[p.id] as ruta, t.deph + 1 as deph'))
                    ->join('tree as t', 't.id', '=', 'p.parent_id')
            );

        $collection = DB::table('tree')
            ->select(['*', DB::raw('array_to_json(ruta) as path')])
            ->withRecursiveExpression('tree', $query)
            // ->whereNotNull('parent_id')
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

                $closure($collection->where('id', $collection[$key]->parent_id));
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
                $closure($collection->where('id', $item->parent_id));
            }

            unset($item->defined);
        });

        return view('admin.globales.formularios.formularios.show', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function edit($id)
    {
        $formulario = Formulario::find($id);
        $variables = Variable::whereIsRoot()->orderBy('id', 'ASC')->pluck('name', 'id');
        $dependencias = Organigrama::orderBy('id', 'ASC')->pluck('dependency', 'id');

        $variablesChecked = [];
        foreach ($formulario->variables as $variable) {
            $variablesChecked[] = $variable->id;
        }

        return view('admin.globales.formularios.formularios.edit', get_defined_vars());
    }


    public function update(Request $request, $id)
    {
        $formulario = Formulario::find($id);
        $formulario->variables()->sync($request->variable_id);

        $formulario->fill($request->except(['variable_id']))->save();

        return redirect()->route('globales.formularios.index')
            ->with('success', 'Formulario actualizado exitosamente!');
    }


    public function destroy($id)
    {
        $formulario = Formulario::find($id);
        $formulario->delete();

        return redirect()->route('formulario-formularios.index');
    }
}
