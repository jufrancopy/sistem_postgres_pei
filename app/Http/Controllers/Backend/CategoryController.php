<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Categories\CreateRequest;
use App\Http\Requests\Backend\Categories\UpdatedRequest;
use Illuminate\Http\Request;

use App\Models\Backend\Category;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('backends.categories.index')->with('categories', $categories);
    }

    public function create()
    {
        $header = ['route' => 'globales.categories.store', 'method' => 'POST'];

        $data = [
            'title'  =>  'Crear Nueva Categoría',
            'button' =>  'Agregar'
        ];

        return view('backends.categories.create_or_edit')->with('header', $header)->with('data', $data);
    }
    
    public function store(CreateRequest $request)
    {
        $category = new Category;
        $category->create($request->all());
        session()->flash('message', [
            'alert' => 'success',
            'text' => 'Categoría creada Correctamente'
        ]);

        return redirect()->route('globales.categories.index');
    }

    public function show($id){
        $category = Category::find($id);

        if (is_null($category)) {
            return redirect()->route('globales.categories.index');
        }

        return redirect()->route('globales.categories.index', ['category_id' => $category->id]);
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        if (is_null($category)) return redirect()->route('backends.categories.index');

        $header = ['route' => ['globales.categories.update', $category->id], 'method' => 'PUT'];

        $data = [
            'route'     => route('globales.categories.update', $category->id),
            'title'     => 'Editando Categoría',
            'burtton'   => 'Actualizar Categ'
        ];
        
        return view('backends.categories.create_or_edit')->with('data', $data)
            ->with('category', $category)->with('header', $header);
    }

    public function update(UpdatedRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        if (is_null($category))
            return redirect()->route('globales.categories.index');

        $category->fill($request->all());
        $category->save();

        session()->flash('message', [
            'alert' => 'success',
            'text' => 'Categoría editada Correctamente'
        ]);

        return redirect()->route('globales.categories.index');
    }
    public function destroy(Request $request, $id)
    {
        if ($request->ajax()) {
            $category = Category::findOrFail($id);
            if (!is_null($category)) {
                $category->delete();
                return response()->json([
                    'response' => true,
                    'id'        => $category->id,
                    'message'   => 'Categoría eliminada exitosamente'
                ]);
            }
            return response()->json([
                'response' => false,
                'message'  => 'Ha ocurrido un error, vuelva a intentarlo'
            ]);
        }
    }
}
