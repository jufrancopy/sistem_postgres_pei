<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaModelo;
use App\Admin\Globales\Organigrama;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Kalnoy\Nestedset\NodeTrait;
use Yajra\DataTables\DataTables;
use App\Admin\Planificacion\Foda\FodaAnalisis;



class FodaPerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = FodaPerfil::query();

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            } else {
                $query->where('type', '!=', 'consolidado');
            }

            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('dependency', function (FodaPerfil $profile) {
                    if ($profile->type === 'grupal' || $profile->type === 'consolidado') {
                        return $profile->group
                            ? '<span class="badge badge-' . ($profile->type === 'consolidado' ? 'success' : 'info') . '"><i class="fa fa-' . ($profile->type === 'consolidado' ? 'layer-group' : 'users') . ' mr-1"></i>' . $profile->group->name . '</span>'
                            : '<span class="badge badge-secondary">Sin grupo</span>';
                    }
                    return $profile->dependency
                        ? $profile->dependency->dependency
                        : '<span class="badge badge-secondary">—</span>';
                })
                ->addColumn('model', function (FodaPerfil $profile) {
                    return $profile->model ? $profile->model->name : '—';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary btn-circle editProfile" title="Editar"><i class="far fa-edit"></i></a>';

                    if ($row->type === 'individual') {
                        // Lupa → ver todos los perfiles del mismo grupo raíz
                        $groupRootId = null;
                        if ($row->group_id) {
                            $group = \App\Admin\Globales\Group::find($row->group_id);
                            $groupRootId = $group?->parent_id ?? $group?->id;
                        }

                        if ($groupRootId) {
                            $btn .= ' <a href="' . route('foda-matriz-groups', $groupRootId) . '" class="btn btn-info btn-circle" title="Ver todos los perfiles del grupo"><i class="fa fa-users"></i></a>';
                        } else {
                            $btn .= ' <a href="/foda-profiles/' . $row->id . '/details" class="btn btn-info btn-circle" title="Ver detalle"><i class="fa fa-search"></i></a>';
                        }

                        $btn .= ' <a href="' . route('foda-analisis-matriz', $row->id) . '" class="btn btn-warning btn-circle" title="Mi Matriz FODA"><i class="fa fa-th"></i></a>';

                    } elseif ($row->type === 'consolidado') {
                        $groupRootId = $row->group_id;
                        if ($groupRootId) {
                            $btn .= ' <a href="' . route('foda-matriz-groups-crossing', $groupRootId) . '" class="btn btn-warning btn-circle" title="Ver Cruce de Ambientes Consolidado"><i class="fa fa-random"></i></a>';
                            $btn .= ' <a href="' . route('foda-matriz-groups', $groupRootId) . '" class="btn btn-info btn-circle" title="Ver Matriz Consolidada"><i class="fa fa-layer-group"></i></a>';
                        } else {
                            $btn .= ' <a href="/foda-cruce-ambientes/' . $row->id . '" class="btn btn-warning btn-circle" title="Ver Cruce de Ambientes"><i class="fa fa-random"></i></a>';
                        }
                    } else {
                        // Grupal → ver la matriz consolidada del grupo
                        $groupRootId = $row->group_id;
                        $btn .= ' <a href="' . route('foda-matriz-groups', $groupRootId) . '" class="btn btn-info btn-circle" title="Ver Matriz Consolidada"><i class="fa fa-layer-group"></i></a>';
                        $btn .= ' <a href="/foda-profiles/' . $row->id . '/details" class="btn btn-warning btn-circle" title="Ver detalle"><i class="fa fa-search"></i></a>';
                    }

                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-circle deleteProfile" title="Eliminar"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'dependency'])
                ->make(true);
        }

        $dependencies = Organigrama::whereIsRoot()->pluck('dependency', 'id')->toArray();
        $categoriasChecked = [];

        return view('admin.planificacion.fodas.perfiles.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function addGroup(Request $request, $idPerfil)
    {
        $profile = FodaPerfil::findOrFail($idPerfil);
        if ($request->ajax()) {
            $data = FodaPerfil::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-circle editProfile"><i class="far fa-edit"></i></a>';

                    $btn = $btn . ' <a href="/foda-analisis-ambientes/' . $row->id . '" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-info btn-circle"><i class="far fa-eye" aria-hidden="true"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-circle deleteProfile"><i class="fa fa-trash" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->addColumn('group_name', function (FodaPerfil $profile) {
                    return $profile->group->name;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.planificacion.fodas.perfiles.add_group', get_defined_vars());
    }

    public function getCategorias($id)
    {
        return FodaCategoria::where('modelo_id', $id)->get();
    }

    public function create()
    {
        $modelos = FodaModelo::orderBy('id', 'ASC')->pluck('nombre', 'id');
        $categorias = FodaCategoria::orderBy('nombre', 'ASC')->pluck('nombre', 'id')->toArray();

        $categoriasChecked = [];

        return view('admin.planificacion.fodas.perfiles.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'context' => 'required',
            'type' => 'required',
            'model_id' => 'required',
            $request->type == 'individual' ? 'dependency_id' : 'group_id' => 'required',
        ], [
            'name.required' => 'Agregue el nombre del Modelo',
            'context.required' => 'Indique el Contexto',
            'type.required' => 'Indique el Tipo',
            $request->type == 'individual' ? 'dependency_id.required' : 'group_id.required' => $request->type == 'individual' ? 'Indique la Dependencia Responsable' : 'Seleccione un Grupo de Análisis',
            'model_id.required' => 'Seleccione el Modelo de Análisis'
        ]);

        $profileId = $request->profile_id;
        $isNewProfile = !$profileId;

        $data = [
            'name' => $request->name,
            'context' => $request->context,
            'type' => $request->type,
            'model_id' => $request->model_id,
        ];

        if ($request->type == 'individual') {
            $data['dependency_id'] = $request->dependency_id;
        } else {
            $data['group_id'] = $request->group_id;
        }

        $profile = $profileId ? FodaPerfil::find($profileId) : new FodaPerfil(['id' => Str::uuid()]);
        $profile->fill($data)->save();

        // Ahora que el perfil ha sido guardado, obtenemos su ID
        $profileId = $profile->id;

        // Obtener el modelo FodaModelo por su ID
        $modelo = FodaModelo::find($request->model_id);

        // Obtener los aspectos del modelo
        $nuevosAspectos = $modelo->descendants()->pluck('id');

        // Obtener los análisis existentes asociados al perfil
        $analisisExistente = FodaAnalisis::where('perfil_id', $profileId)->pluck('aspecto_id');

        // Determinar los aspectos que deben eliminarse
        $aspectosEliminar = $analisisExistente->diff($nuevosAspectos);

        // Eliminar los análisis que ya no están presentes en el nuevo modelo
        FodaAnalisis::where('perfil_id', $profileId)
            ->whereIn('aspecto_id', $aspectosEliminar)
            ->delete();

        // Determinar los aspectos que deben agregarse
        $aspectosAgregar = $nuevosAspectos->diff($analisisExistente);

        // Crear nuevos análisis para los aspectos que no estaban presentes antes
        foreach ($aspectosAgregar as $aspectoAgregar) {
            FodaAnalisis::create([
                'user_id' => Auth::id(),
                'perfil_id' => $profileId,
                'aspecto_id' => $aspectoAgregar,
                'tipo' => 'Pendiente',
                'ocurrencia' => null,
                'impacto' => null
            ]);
        }

        //Insert into pivot table 
        // $categories = $request->category_id;
        // $profile->categories()->sync($categories);

        $message = $profileId ? 'Perfil actualizado correctamente.' : 'Perfil creado correctamente.';
        return response()->json(['success' => $message]);
    }

    public function createGroupRootProfile(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'name'              => 'required',
                    'context'           => 'required',
                    'type'              => 'required',
                    'model_id'          => 'required',
                ],
                [
                    'name.required'             => 'Agregue el nombre del Modelo',
                    'context.required'          => 'Indique el Contexto',
                    'type.required'             => 'Indique el Tipo',
                    'model_id.required'         => 'Seleccione el Modelo de Análisis'

                ]
            );
        };

        $profileId = Str::uuid();
        $profile = FodaPerfil::updateOrCreate(
            ['id' => $profileId],
            [
                'name' => $request->name,
                'context' => $request->context,
                'type' => $request->type,
                'model_id' => $request->model_id,
                'dependency_id' => $request->dependency_id,
                'group_id' => $request->group_id,
            ]
        );
        $groupRootId = $profile->group->id;

        //Insert into pivot table 
        $categories = $request->category_id;
        $profile->categories()->sync($categories);

        if ($profile->wasRecentlyCreated) {
            // return response()->json(['redirect' => route('foda-matriz-groups', $profile->group_id), 'message' => 'Perfil Grupal creado correctamente.']);
            return redirect()->route('foda-matriz-groups', $groupRootId)->with(['success' => 'Perfil asignado correctamente']);
        } else {
            // return response()->json(['redirect' => route('foda-matriz-groups', $profile->group_id), 'message' => 'Perfil Grupal actualizado correctamente.']);
            return redirect()->route('foda-matriz-groups', $groupRootId)->with(['success' => 'Perfil asignado correctamente']);
        }
    }

    public function showDetails(Request $request, $idProfile)
    {
        $fodaProfile = FodaPerfil::findOrFail($idProfile);

        return view('admin.planificacion.fodas.perfiles.details', get_defined_vars());
    }

    function buildTreeForEnvironment($environment, $aspects, $idProfile)
    {
        // Filtrar los aspectos por entorno
        $environmentAspects = $aspects->where('environment', $environment);

        // Obtener las categorías únicas para este entorno
        $categories = $environmentAspects->where('type', 'category')->unique('name');
        // Inicializar un contador para los ids
        $idCounter = 1;
        // Construir el árbol para cada categoría
        $tree = $categories->map(function ($category) use ($environmentAspects, $environment, &$idCounter, $idProfile) {
            $children = $environmentAspects->where('parent_id', $category->id)->map(function ($aspect) use ($environment, &$idCounter, $idProfile) {
                // Generar un id único para cada nodo

                // Obtener los valores de análisis para este aspecto
                $buttonHTML = '<a href="#" class="editAspect" data-environment="' . $environment . '" data-id="' . $aspect->id . '">...</a>';

                $analysis = FodaAnalisis::where('aspecto_id', $aspect->id)->where('perfil_id', $idProfile)->first();

                // Aplicar lógica para determinar el valor de ocurrencia
                switch ($analysis->ocurrencia) {
                    case 0.1:
                        $ocurrencia = 'Baja';
                        break;
                    case 0.3:
                        $ocurrencia = 'Media';
                        break;
                    case 0.5:
                        $ocurrencia = 'Alta';
                        break;
                    case 0.7:
                        $ocurrencia = 'Muy Alta';
                        break;
                    case 0.9:
                        $ocurrencia = 'Cierta';
                        break;
                    default:
                        $ocurrencia = 'Pendiente de Análisis';
                }

                // Aplicar lógica para determinar el valor de impacto
                switch ($analysis->impacto) {
                    case 0.05:
                        $impacto = 'Muy Bajo';
                        break;
                    case 0.1:
                        $impacto = 'Bajo';
                        break;
                    case 0.2:
                        $impacto = 'Moderado';
                        break;
                    case 0.4:
                        $impacto = 'Alto';
                        break;
                    case 0.8:
                        $impacto = 'Muy Alto';
                        break;
                    default:
                        $impacto = 'Pendiente de Análisis';
                }

                // Aplicar lógica para determinar el valor de impacto
                switch ($analysis->tipo) {
                    case "Fortaleza":
                        $tipo = '<strong class="badge badge-success">Fortaleza</strong>';
                        break;
                    case "Debilidad":
                        $tipo = '<strong class="badge badge-danger">Debilidad</strong>';
                        break;
                    case "Oportunidad":
                        $tipo = '<strong class="badge badge-success">Oportunidad</strong>';
                        break;
                    case "Amenaza":
                        $tipo = '<strong class="badge badge-danger">Amenaza</strong>';
                        break;

                    default:
                        $tipo = 'Pendiente de Análisis';
                }

                // Calcular el total
                $total = $analysis->ocurrencia * $analysis->impacto;

                // Aplicar lógica para determinar el estilo y texto del total
                if ($total == 0.0) {
                    $totalHtml = '<strong class="badge badge-primary">Pendiente</strong>';
                } elseif ($total >= 0.18) {
                    $totalHtml = '<strong class="badge badge-success">Suficiente (' . $total . ')</strong>';
                } else {
                    $totalHtml = '<strong class="badge badge-danger">Insuficiente (' . $total . ')</strong>';
                }

                // Construir la tabla HTML con los valores de análisis
                $tableHTML = '<table class="table table-bordered">' .
                    '<tr>' .
                    '<th>Impacto</th>' .
                    '<th>Ocurrencia</th>' .
                    '<th>Tipo</th>' .
                    '<th>Ponderación</th>' .
                    '<th colspan=2>Acciones</th>' .
                    '</tr>' .
                    '<tr>' .
                    '<td>' . $impacto . ' (' . $analysis->impacto . ')</td>' .
                    '<td>' . $ocurrencia . ' (' . $analysis->ocurrencia . ')</td>' .
                    '<td>' . $tipo . '</td>' .
                    '<td>' . $totalHtml . '</td>' .
                    '<td>' .
                    '<a href="#" class="editAspect" data-node="' . $analysis->id . '" data-environment="' . $environment . '" data-id="' . $analysis->id . '"><i class="fa fa-search btn btn-info btn-circle" aria-hidden="true"></i></a>' .
                    '</td>' .
                    '</tr>' .
                    '</table>';

                // ── Bloque MECIP (solo Debilidad/Amenaza con datos cargados) ──
                $mecipHTML = '';
                if (in_array($analysis->tipo, ['Debilidad', 'Amenaza'])) {
                    $causasLabel = [
                        'operativa'   => 'Operativa',
                        'estructural' => 'Estructural',
                        'tecnologica' => 'Tecnológica',
                        'normativa'   => 'Normativa',
                        'otra'        => 'Otra',
                    ];
                    $causaLabel = $causasLabel[$analysis->causa_raiz] ?? null;

                    $mecipHTML = '<div style="margin-top:6px;padding:8px 10px;background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:6px;font-size:.8rem">'
                        . '<div style="font-weight:700;font-size:.7rem;letter-spacing:.06em;text-transform:uppercase;color:#92400e;margin-bottom:6px">'
                        . '<i class="fa fa-shield-alt" style="margin-right:4px"></i> MECIP 2015 — Gestión de Riesgo'
                        . '</div>';

                    if ($causaLabel) {
                        $mecipHTML .= '<div style="margin-bottom:4px">'
                            . '<span style="color:#6b7280;font-size:.72rem">Causa raíz:</span> '
                            . '<span class="badge badge-warning" style="font-size:.7rem">' . $causaLabel . '</span>'
                            . '</div>';
                    }

                    if ($analysis->accion_mejora) {
                        $mecipHTML .= '<div style="margin-bottom:4px">'
                            . '<span style="color:#6b7280;font-size:.72rem"><i class="fa fa-arrow-right" style="margin-right:2px"></i> Acción de mejora:</span> '
                            . '<span style="color:#374151">' . e($analysis->accion_mejora) . '</span>'
                            . '</div>';
                    }

                    if ($analysis->control_preventivo) {
                        $mecipHTML .= '<div>'
                            . '<span style="color:#6b7280;font-size:.72rem"><i class="fa fa-shield-alt" style="margin-right:2px"></i> Control preventivo:</span> '
                            . '<span style="color:#374151">' . e($analysis->control_preventivo) . '</span>'
                            . '</div>';
                    }

                    if (!$causaLabel && !$analysis->accion_mejora && !$analysis->control_preventivo) {
                        $mecipHTML .= '<span style="color:#9ca3af;font-style:italic;font-size:.75rem">'
                            . '<i class="fa fa-exclamation-circle" style="margin-right:4px"></i>'
                            . 'Sin análisis de riesgo completado — editá el aspecto para agregar causa raíz y acción de mejora.'
                            . '</span>';
                    }

                    $mecipHTML .= '</div>';
                }

                $tableHTML .= $mecipHTML;

                return [
                    'name' => '<sup class="badge badge-secondary">Aspecto</sup> ' . $aspect->name,
                    'id' => $aspect->id,
                    'children' => [
                        [
                            'name' => $tableHTML,
                            'button' => $buttonHTML
                        ]
                    ]
                ];
            });

            return [
                'name' => '<sup class="badge badge-secondary">Categoría</sup> ' . $category->name,
                'id' => $category->id,
                'children' => $children->values()->toArray()
            ];
        });

        // Devolver el árbol para este entorno
        return [
            'name' => '<sup class="badge badge-secondary">Ambiente</sup>' . $environment,
            'id' => $environment,
            'children' => $tree->values()->toArray()
        ];
    }

    public function getTree(Request $request, $idProfile)
    {
        $fodaProfile = FodaPerfil::findOrFail($idProfile);

        // Obtener los aspecto_id de los registros en FodaAnalisis
        $aspectIds = FodaAnalisis::where('perfil_id', $idProfile)->pluck('aspecto_id');

        // Obtener todos los aspectos relacionados directamente de la tabla FodaModelo
        $aspects = FodaModelo::whereIn('id', $aspectIds)->get();

        // Filtrar los aspectos por entorno (environment)
        $environments = $aspects->pluck('environment')->unique();

        // Construir el árbol para cada entorno
        $tree = $environments->map(function ($environment) use ($aspects, $idProfile) {
            return $this->buildTreeForEnvironment($environment, $aspects, $idProfile);
        })->values();

        return response()->json($tree);
    }

    public function show($id)
    {
        $perfil = FodaPerfil::find($id);
        // $categorias = $perfil->categorias()->orderBy('nombre', 'ASC')->get();
        $categoriasChecked = [];

        foreach ($perfil->categorias as $categoria) {
            $categoriasChecked[] = $categoria->id;
        }
        return view('admin.planificacion.fodas.perfiles.show', get_defined_vars());
    }

    public function edit($id)
    {
        $profile = FodaPerfil::with(['dependency', 'model', 'categories', 'group'])->find($id);

        $rootGroup = null;
        if ($profile->group) {
            $rootGroup = $profile->group->parent ?? $profile->group;
        }

        $rootDependency = null;
        if ($profile->dependency) {
            $rootDependency = $profile->dependency->parent ?? $profile->dependency;
        }

        $categoriesChecked = [];
        foreach ($profile->categories as $category) {
            $categoriesChecked[] = ['id' => $category->id, 'text' => $category->name];
        }

        return response()->json([
            'profile'          => $profile,
            'categoriesChecked'=> $categoriesChecked,
            'rootGroup'        => $rootGroup,
            'rootDependency'   => $rootDependency,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $analisis = FodaAnalisis::where('perfil_id', $id)->get();
        foreach ($analisis as $analysis) {
            $analysis->delete();
        }

        $perfil = FodaPerfil::find($id);
        $perfil->delete();

        return response()->json(['success' => 'Perfil eliminado correctamente.']);
    }

    public function getPerfilesSelect2(Request $request)
    {
        $q = $request->q ?? '';
        $perfiles = FodaPerfil::whereIn('type', ['individual', 'consolidado'])
            ->where('name', 'ILIKE', "%$q%")
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return response()->json(
            $perfiles->map(fn($p) => ['id' => $p->id, 'text' => '[' . $p->type . '] ' . $p->name])
        );
    }
}
