<?php

namespace App\Http\Controllers\Admin\Globales\Formulario;

use App\Admin\Globales\EstructuraControl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\Globales\Formulario\Formulario;
use App\Admin\Globales\Formulario\Item;
use App\Admin\Globales\Formulario\Variable;
use App\Admin\Globales\Organigrama;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use App\ClasesPersonalizadas\Tree;


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


        return view('admin.globales.formularios.formularios.create', get_defined_vars());
    }


    public function store(Request $request)
    {
        $formulario = Formulario::updateOrCreate(
            ['id' => $request->id],
            [
                'formulario'                => $request->formulario,
                'dependencia_emisor_id'     => $request->dependencia_emisor_id,
                'dependencia_receptor_id'   => $request->dependencia_emisor_id,
                'user_id'                   => $request->user_id,
                'status'                    => $request->status
            ]
        );

        $status = $request->status;

        if (isset($status)) {
            $status = 1;
        } else {
            $status = 0;
        }

        $formularioVariableAttach = [];

        $status = $request->status;

        foreach ($request->variable_id as $key => $value) {
            
            if($request->selected[$key] == null){
                $status[$key] = 0;
            }else{
                $status[$key] = 1;
            }
            
            $formularioVariableAttach[$value] = [
                'selected_variable_id' => $request->selected[$key],
                'status' => $status[$key]
            ];
        }

        $countVariables = count($formulario->variables);

        $formulario->variables()->syncWithoutDetaching($formularioVariableAttach);

        return redirect()->route('globales.formularios.show', $formulario->id)
            ->with('success', 'Formulario creado exitosamente!');
    }

    public function postSelectedItem(Request $request, $idForm)
    {
        if ($request->ajax()) {
            $itemSelected = Formulario::findOrFail($idForm);
            if (!is_null($itemSelected)) {
                Formulario::setSelected($itemSelected, $request->selected);
                return response()->json([
                    'response'  => true,
                    'message'   => 'Item seleccionado'
                ]);
            }
            return response()->json([
                'response'  => false,
                'message'   => 'No seleccionado'
            ]);
        }
    }

    public function postResponse(Request $request, $idForm)
    {
        dd($idForm);
    }

    public function show(Request $request, $id)
    {
        $formulario = Formulario::findOrFail($id);

        $selectionChecked = [];

        foreach ($formulario->variables as $v) {
            $variable = $v->id;
            $selectionChecked[] = $v->pivot->selected_variable_id;
        }
        $countVariables = count($formulario->variables);

        $query = DB::table('estadistica.formulario_formulario_has_variables AS vars')
            ->join('estadistica.formulario_formularios AS form', 'vars.formulario_id', '=', 'form.id')
            ->join('estadistica.formulario_variables AS var', 'vars.variable_id', '=', 'var.id')
            ->select(DB::raw('var.id, var.parent_id, var.type, var.name, ARRAY[var.id] as ruta, 0 as deph'))
            ->whereNull('var.parent_id')
            ->where('vars.formulario_id', $id)
            ->unionAll(
                DB::table('estadistica.formulario_variables as variable')
                    ->select(DB::raw('variable.id,variable.parent_id, variable.type, variable.name,
                t.ruta || ARRAY[variable.id] as ruta, t.deph + 1 as deph'))
                    ->join('tree as t', 't.id', '=', 'variable.parent_id')
            );

        $collection = DB::table('tree')
            ->select(['*', DB::raw('array_to_json(ruta) as path')])
            ->withRecursiveExpression('tree', $query)
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

        return redirect()->route('globlales.formularios.index');
    }
}
