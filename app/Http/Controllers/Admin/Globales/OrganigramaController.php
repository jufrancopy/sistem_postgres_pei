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
        $query = Organigrama::select("id", "dependency")
            ->whereNull('parent_id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('dependency', 'ILIKE', "%{$search}%")
                  ->orWhere('dependency', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->orderBy('dependency')->limit(50)->get();
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
            'parent_id' => 'nullable|integer',
            'before_id' => 'nullable|integer',
            'after_id'  => 'nullable|integer',
        ]);

        $nodo = Organigrama::findOrFail($id);
        $nuevoPadre = null;

        if ($request->filled('before_id')) {
            $sibling = Organigrama::findOrFail($request->before_id);
            if ($sibling->id === $nodo->id || $sibling->isDescendantOf($nodo)) {
                return response()->json(['error' => 'Posición inválida en la jerarquía.'], 422);
            }
            $nodo->beforeNode($sibling)->save();
            $nodo->refresh();
            $nuevoPadre = $nodo->parent;
        } elseif ($request->filled('after_id')) {
            $sibling = Organigrama::findOrFail($request->after_id);
            if ($sibling->id === $nodo->id || $sibling->isDescendantOf($nodo)) {
                return response()->json(['error' => 'Posición inválida en la jerarquía.'], 422);
            }
            $nodo->afterNode($sibling)->save();
            $nodo->refresh();
            $nuevoPadre = $nodo->parent;
        } elseif ($request->filled('parent_id')) {
            $nuevoPadre = Organigrama::findOrFail($request->parent_id);
            if ($nuevoPadre->id === $nodo->id || $nuevoPadre->isDescendantOf($nodo)) {
                return response()->json([
                    'error' => 'No se puede mover un nodo dentro de uno de sus propios descendientes.'
                ], 422);
            }
            $nodo->appendToNode($nuevoPadre)->save();
            $nodo->refresh();
        } else {
            return response()->json(['error' => 'Faltan parámetros de destino.'], 422);
        }

        $padreNombre = $nuevoPadre ? $nuevoPadre->dependency : 'Raíz';

        return response()->json([
            'success' => true,
            'message' => "'{$nodo->dependency}' movido correctamente a '{$padreNombre}'.",
            'node_id' => $nodo->id,
            'parent_name' => $padreNombre,
        ]);
    }

    public function create(Request $request)
    {
        $parents = Organigrama::pluck('dependency', 'id');

        return view('admin.globales.organigramas.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $userId = $request->user_id ?: null;
        $manager = $request->manager;
        $email = $request->email;
        if ($userId) {
            $u = \App\Models\User::find($userId);
            if ($u) {
                $manager = $manager ?: $u->name;
                $email = $email ?: $u->email;
            }
        }

        $dependencia = Organigrama::create([
            'dependency'           => $request->dependency,
            'manager'              => $manager,
            'phone'                => $request->phone,
            'email'                => $email,
            'user_id'              => $userId,
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
            'user_id'              => $request->user_id ?: null,
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

    // ── Búsqueda de usuarios para el select2 de responsable ──────────────────
    public function buscarUsuarios(\Illuminate\Http\Request $request)
    {
        $q = $request->get('q', '');
        $usuarios = \App\Models\User::where(function($query) use ($q) {
                $query->where('name', 'ilike', '%' . $q . '%')
                      ->orWhere('email', 'ilike', '%' . $q . '%');
            })
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'name', 'email']);

        return response()->json($usuarios);
    }
}
