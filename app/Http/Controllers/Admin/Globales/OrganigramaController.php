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
        $dependencias = Organigrama::whereIsRoot()->paginate(10);

        return view('admin.globales.organigramas.index', get_defined_vars())
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function verOrganigrama($id)
    {
        $dependencia = Organigrama::findOrFail($id);

        return view('admin.globales.organigramas.organigrama', get_defined_vars());
    }

    public function crearSubDependencia(Request $request, $idDependencia)
    {
        $idDependencia = $request->idDependencia;
        $dependencia = Organigrama::find($idDependencia);
        $parentId = $dependencia->first()->id;


        return view('admin.globales.organigramas.crear_sub_dependencia', get_defined_vars());
    }

    public function editarSubDependencia(Request $request, $idDependencia)
    {
        $idDependencia = $request->idDependencia;
        $dependencia = Organigrama::find($idDependencia);
        $parentId = $dependencia->first()->id;
        $rootId = Organigrama::whereAncestorOf($dependencia)->whereIsRoot()->first()->id;

        return view('admin.globales.organigramas.editar_sub_dependencia', get_defined_vars());
    }

    public function getDependencies(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $search = $request->q;
            $data = Organigrama::select("id", "dependency")
                ->where('dependency', 'LIKE', "%$search%")
                ->where('parent_id', null)
                ->get();
        }
        return response()->json($data);
    }

    public function getDependenciesRoot(){
        $data = Organigrama::whereIsRoot()->get();

        return response()->json($data);
    }
    
    public function getDependenciesFromRoot(Request $request, $idRoot)
    {
        $rootNode = Organigrama::find($idRoot);

        if (!$rootNode) {
            return response()->json([]);
        }

        $search = $request->get('q', '');

        $ids = $rootNode->descendants()->pluck('id')->prepend($rootNode->id);

        $data = Organigrama::whereIn('id', $ids)
            ->where('dependency', 'LIKE', '%' . $search . '%')
            ->get(['id', 'dependency']);

        return response()->json($data);
    }

    public function getDependency(Request $request, $idSelection)
    {
        $data = Organigrama::findOrFail($idSelection);

        return response()->json($data);
    }

    public function getRootOfDependency($idSelection)
    {
        $dependencia = Organigrama::findOrFail($idSelection);

        // Si ya es raíz, devolver ella misma
        if ($dependencia->isRoot()) {
            return response()->json($dependencia);
        }

        // Buscar el ancestro raíz
        $raiz = Organigrama::whereAncestorOf($dependencia)->whereIsRoot()->first();

        return response()->json($raiz ?? $dependencia);
    }

    /**
     * Mover un nodo a un nuevo padre (drag & drop — Opción B)
     * POST /admin/globales/organigramas/{id}/mover
     */
    public function mover(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'parent_id' => 'required|integer|different:' . $id,
        ]);

        $nodo       = Organigrama::findOrFail($id);
        $nuevoPadre = Organigrama::findOrFail($request->parent_id);

        // Evitar que un nodo se mueva a uno de sus propios descendientes
        if ($nuevoPadre->isDescendantOf($nodo)) {
            return response()->json([
                'error' => 'No se puede mover un nodo dentro de uno de sus propios descendientes.'
            ], 422);
        }

        $nodo->appendToNode($nuevoPadre)->save();

        return response()->json([
            'success' => "'{$nodo->dependency}' movido correctamente a '{$nuevoPadre->dependency}'.",
        ]);
    }

    public function create(Request $request)
    {
        $parents = Organigrama::pluck('dependency', 'id');

        return view('admin.globales.organigramas.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $dependencia = Organigrama::create([
            'dependency'           => $request->dependency,
            'manager'              => $request->manager,
            'phone'                => $request->phone,
            'email'                => $request->email,
            'tipo_establecimiento' => $request->tipo_establecimiento ?: null,
            'nivel_complejidad'    => $request->nivel_complejidad ?: null,
            'tenencia'             => $request->tenencia ?: null,
            'tiene_aop'            => $request->has('tiene_aop'),
            'region'               => $request->region ?: null,
        ]);

        if ($request->parent_id) {
            $node = Organigrama::find($request->parent_id);
            $node->appendNode($dependencia);
        }

        if ($dependencia->parent_id == null) {
            return redirect()->route('globales.organigrama-gestionar', $dependencia->id);
        } else {
            return redirect()->route('globales.organigrama-gestionar', $dependencia->parent_id);
        }
    }

    public function show($id)
    {
        $dependencie = Organigrama::descendantsAndSelf($id)->toTree();

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

        $dependencia->fill([
            'dependency'           => $request->dependency,
            'manager'              => $request->manager,
            'phone'                => $request->phone,
            'email'                => $request->email,
            'tipo_establecimiento' => $request->tipo_establecimiento ?: null,
            'nivel_complejidad'    => $request->nivel_complejidad ?: null,
            'tenencia'             => $request->tenencia ?: null,
            'tiene_aop'            => $request->has('tiene_aop'),
            'region'               => $request->region ?: null,
        ])->save();

        $ancestro     = $dependencia->ancestorsAndSelf($id)->where('parent_id', null)->first();
        $parentRootId = $ancestro->id;

        return redirect()->route('globales.organigrama-gestionar', $parentRootId);
    }

    public function destroy($id)
    {
        Organigrama::find($id)->delete();

        return back()->with('success', 'Dependencia eliminada correctamente.');
    }
}
