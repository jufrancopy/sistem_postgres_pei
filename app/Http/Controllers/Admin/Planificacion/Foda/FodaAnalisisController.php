<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade as PDF;

use App\Admin\Planificacion\Foda\FodaAspecto;
use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaModelo;
use App\Admin\Globales\Group;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;

use App\Admin\Planificacion\Task\Task;
use App\Admin\Planificacion\Task\TypeTask;
use Illuminate\Support\Facades\Auth;

class FodaAnalisisController extends Controller
{
    //antes de procesar el index() ejecuta el metodo consturctor
    function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $analizados = FodaAnalisis::orderBy('id', 'ASC')->get();
        $aspectos = $analizados->aspectos()->orderBy('nombre', 'ASC')->get();

        // Crear un array vacio para almacenar aspectos.
        $aspectosChecked = [];

        // Obtener las aspectos relacionados al analisis.
        foreach ($analizados->aspectos as $aspecto) {

            // Acumular las aspectos en el array '$aspectosChecked'.
            $aspectosChecked[] = $aspecto->id;
        }
        return view('admin.planificacion.fodas.analisis.index', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function seleccionarAmbiente(Request $request, $id)
    {
        $perfil = FodaPerfil::find($id);
        $categorias = $perfil->categories()->orderBy('name', 'ASC')->get();

        $categoriasChecked = [];

        foreach ($perfil->categories as $categoria) {
            $categoriasChecked[] = $categoria->id;
        }

        return view('admin.planificacion.fodas.analisis.ambientes', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function aspectosCategoriaEdit(Request $request)
    {
        $idPerfil = $request->idPerfil;
        $idCategoria = $request->idCategoria;
        $categoria = FodaCategoria::find($idCategoria);
        $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)->get();

        $aspectos = FodaAspecto::where('categoria_id', '=', $idCategoria)->get();
        if ($analisis->isNotEmpty()) {
            $aspectosChecked = [];
            foreach ($analisis as $v) {
                $aspectosChecked[] = $v->aspecto->id;
            }
            return view('admin.planificacion.fodas.analisis.aspectos-edit', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            // colección vacía
            return view('admin.fplanificacion.odas.analisis.error', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    }

    public function matriz(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfil = FodaPerfil::with(['group', 'model'])->find($idPerfil);

        // Verificar si el perfil y el grupo están definidos y si el grupo tiene miembros
        if ($perfil->group && $perfil->group->members) {
            // Acceder a los miembros del grupo
            $members = $perfil->group->members;
            // Ahora puedes trabajar con la colección de miembros
            foreach ($members as $member) {
                // Accede a las propiedades de cada miembro
                $userName = $member->name;
                $userEmail = $member->email;
            }
        }

        $matriz = config('foda.umbral_matriz');

        // Ambiente Interno - Debilidad
        $debilidades = FodaAnalisis::with('aspecto')
            ->where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(foda_analisis.ocurrencia::int * foda_analisis.impacto::int) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Debilidad')
            ->get();

        // Ambiente Interno - Fortaleza
        $fortalezas = FodaAnalisis::with('aspecto')
            ->where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Fortaleza')
            ->get();

        // Ambiente Externo - Oportunidad
        $oportunidades = FodaAnalisis::with('aspecto')
            ->where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Oportunidad')
            ->get();

        // Ambiente Externo - Amenaza
        $amenazas = FodaAnalisis::with('aspecto')
            ->where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz'))
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Amenaza')
            ->get();


        // Comprueba si es una solicitud AJAX
        if ($request->ajax()) {
            // Si es una solicitud AJAX, puedes devolver una respuesta JSON
            return response()->json([
                'debilidades' => $debilidades,
                'fortalezas' => $fortalezas,
                'oportunidades' => $oportunidades,
                'amenazas' => $amenazas,
                'profile' => $perfil,
                'members' => $members
            ]);
        }

        return view('admin.planificacion.fodas.analisis.matriz', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getListGroup(Request $request)
    {
        if ($request->ajax()) {
            // Si viene pei_id, filtramos por el grupo raíz del PEI
            $query = \App\Admin\Globales\Group::whereNull('parent_id');

            if ($request->filled('pei_id')) {
                $pei = PeiProfile::find($request->pei_id);
                if ($pei && $pei->group_id) {
                    $group = \App\Admin\Globales\Group::find($pei->group_id);
                    // Si el grupo del PEI es un subgrupo, usamos su padre como raíz
                    $rootId = $group->parent_id ?? $group->id;
                    $query->where('id', $rootId);
                }
            }

            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $consolidado = FodaPerfil::where('group_id', $row->id)
                        ->where('type', 'consolidado')
                        ->first();

                    // Verificar si algún subgrupo tiene perfiles FODA
                    $subgroupIds = \App\Admin\Globales\Group::where('parent_id', $row->id)->pluck('id');
                    $tieneFoda = FodaPerfil::whereIn('group_id', $subgroupIds)->exists()
                        || FodaPerfil::where('group_id', $row->id)->exists();

                    $btn = '<a href="' . route('foda-matriz-groups', $row->id) . '" class="btn btn-info btn-circle" title="Ver Matriz"><i class="fa fa-eye"></i></a>';

                    if ($consolidado) {
                        $btn .= ' <a href="' . route('foda-matriz-groups-crossing', $row->id) . '" class="btn btn-success btn-sm" title="Cruce de Ambientes"><i class="fa fa-random mr-1"></i>Cruce de Ambientes</a>';
                    } elseif ($tieneFoda) {
                        $btn .= ' <span class="badge badge-warning ml-1">Sin consolidado</span>';
                    }

                    return $btn;
                })
                ->addColumn('estado', function ($row) {
                    $consolidado = FodaPerfil::where('group_id', $row->id)
                        ->where('type', 'consolidado')->exists();
                    return $consolidado
                        ? '<span class="badge badge-success"><i class="fa fa-check mr-1"></i>Consolidado</span>'
                        : '<span class="badge badge-secondary">Sin consolidar</span>';
                })
                ->rawColumns(['action', 'estado'])
                ->make(true);
        }

        $peiId     = request('pei_id');
        $peiNombre = null;
        if ($peiId) {
            $pei = PeiProfile::find($peiId);
            $peiNombre = $pei ? strip_tags($pei->name) : null;
        }
        return view('admin.planificacion.fodas.groups.list_tasks', compact('peiId', 'peiNombre'));
    }

    public function getMatrizForGroup(Request $request, $idGroup)
    {
        $groups = Group::descendantsOf($idGroup);
        $groupId = [];

        $profile = FodaPerfil::where('group_id', $idGroup)->first();

        foreach ($groups as $group) {
            $groupId[] = $group->id;
        }

        $profiles = FodaPerfil::with(['group', 'model'])
            ->whereIn('group_id', $groupId)
            ->get();

        // Si no hay perfiles en los subgrupos, intentar con el grupo raíz
        if ($profiles->isEmpty()) {
            $profiles = FodaPerfil::with(['group', 'model'])
                ->where('group_id', $idGroup)
                ->get();
        }

        $modelId = $profiles->first()?->model_id;

        $perfilIds = $profiles->pluck('id');
        $matriz = config('foda.umbral_matriz');

        $fodaAnalisis = FodaAnalisis::with('aspecto')
            ->whereIn('perfil_id', $perfilIds)
            ->select(
                DB::raw('planificacion.foda_analisis.*'),
                DB::raw('(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz')
            )
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->whereIn('tipo', ['Debilidad', 'Fortaleza', 'Oportunidad', 'Amenaza'])
            ->get();

        // Filtrar y seleccionar los aspectos únicos con puntaje más alto
        $uniqueAspects = $fodaAnalisis->unique('aspecto_id')->map(function ($item, $key) use ($fodaAnalisis) {
            $maxScoreAspect = $fodaAnalisis->where('aspecto_id', $item->aspecto_id)->max('matriz');
            return $fodaAnalisis->where('aspecto_id', $item->aspecto_id)->first(function ($value, $key) use ($maxScoreAspect) {
                return $value->matriz == $maxScoreAspect;
            });
        });

        $debilidades = $uniqueAspects->where('tipo', 'Debilidad');
        $fortalezas = $uniqueAspects->where('tipo', 'Fortaleza');
        $oportunidades = $uniqueAspects->where('tipo', 'Oportunidad');
        $amenazas = $uniqueAspects->where('tipo', 'Amenaza');

        // $members = $perfil->group->members;
        // foreach ($members as $member) {
        //     $userName = $member->name;
        //     $userEmail = $member->email;
        // }

        // Comprueba si es una solicitud AJAX
        if ($request->ajax()) {
            // Si es una solicitud AJAX, puedes devolver una respuesta JSON
            return response()->json([
                'debilidades' => $debilidades,
                'fortalezas' => $fortalezas,
                'oportunidades' => $oportunidades,
                'amenazas' => $amenazas,
                'profiles' => $profiles,
            ]);
        }

        return view('admin.planificacion.fodas.groups.matriz', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getMatrizForCrossing(Request $request, $idGroup)
    {
        $groups = Group::descendantsOf($idGroup);
        $groupId = [];

        foreach ($groups as $group) {
            $groupId[] = $group->id;
        }
        $profile = FodaPerfil::where('group_id', $idGroup)->where('type', 'consolidado')->first()
            ?? FodaPerfil::whereIn('group_id', array_merge([$idGroup], $groupId))->first();

        if (!$profile) {
            abort(404, 'No se encontró un perfil FODA para este grupo.');
        }

        $idPerfil = $profile->id;


        $profiles = FodaPerfil::with(['group', 'model'])
            ->whereIn('group_id', $groupId)
            ->get();

        $perfilIds = $profiles->pluck('id');
        $matriz = config('foda.umbral_matriz');

        $fodaAnalisis = FodaAnalisis::with('aspecto')
            ->whereIn('perfil_id', $perfilIds)
            ->select(
                DB::raw('planificacion.foda_analisis.*'),
                DB::raw('(foda_analisis.ocurrencia * foda_analisis.impacto) as matriz')
            )
            ->whereRaw("(foda_analisis.ocurrencia * foda_analisis.impacto) > $matriz")
            ->whereIn('tipo', ['Debilidad', 'Fortaleza', 'Oportunidad', 'Amenaza'])
            ->get();

        // Filtrar y seleccionar los aspectos únicos con puntaje más alto
        $uniqueAspects = $fodaAnalisis->unique('aspecto_id')->map(function ($item, $key) use ($fodaAnalisis) {
            $maxScoreAspect = $fodaAnalisis->where('aspecto_id', $item->aspecto_id)->max('matriz');
            return $fodaAnalisis->where('aspecto_id', $item->aspecto_id)->first(function ($value, $key) use ($maxScoreAspect) {
                return $value->matriz == $maxScoreAspect;
            });
        });

        $debilidades = $uniqueAspects->where('tipo', 'Debilidad');
        $fortalezas = $uniqueAspects->where('tipo', 'Fortaleza');
        $oportunidades = $uniqueAspects->where('tipo', 'Oportunidad');
        $amenazas = $uniqueAspects->where('tipo', 'Amenaza');

        // $members = $perfil->group->members;
        // foreach ($members as $member) {
        //     $userName = $member->name;
        //     $userEmail = $member->email;
        // }
        $FOs = FodaCruceAmbiente::with(['fortalezas','oportunidades'])->where('tipo', '=', 'FO')->where('perfil_id', '=', $idPerfil)->get();
        $DOs = FodaCruceAmbiente::with(['debilidades','oportunidades'])->where('tipo', '=', 'DO')->where('perfil_id', '=', $idPerfil)->get();
        $FAs = FodaCruceAmbiente::with(['fortalezas','amenazas'])->where('tipo', '=', 'FA')->where('perfil_id', '=', $idPerfil)->get();
        $DAs = FodaCruceAmbiente::with(['debilidades','amenazas'])->where('tipo', '=', 'DA')->where('perfil_id', '=', $idPerfil)->get();

        // Mapa aspecto_id => [cuadrantes donde ya aparece]
        $fortalezasCubiertas = [];
        foreach ($FOs as $c) foreach ($c->fortalezas as $b) $fortalezasCubiertas[$b->id][] = 'FO';
        foreach ($FAs as $c) foreach ($c->fortalezas as $b) $fortalezasCubiertas[$b->id][] = 'FA';
        $debilidadesCubiertas = [];
        foreach ($DOs as $c) foreach ($c->debilidades as $b) $debilidadesCubiertas[$b->id][] = 'DO';
        foreach ($DAs as $c) foreach ($c->debilidades as $b) $debilidadesCubiertas[$b->id][] = 'DA';
        $oportunidadesCubiertas = [];
        foreach ($FOs as $c) foreach ($c->oportunidades as $b) $oportunidadesCubiertas[$b->id][] = 'FO';
        foreach ($DOs as $c) foreach ($c->oportunidades as $b) $oportunidadesCubiertas[$b->id][] = 'DO';
        $amenazasCubiertas = [];
        foreach ($FAs as $c) foreach ($c->amenazas as $b) $amenazasCubiertas[$b->id][] = 'FA';
        foreach ($DAs as $c) foreach ($c->amenazas as $b) $amenazasCubiertas[$b->id][] = 'DA';
        $fortalezasCubiertas    = array_map('array_unique', $fortalezasCubiertas);
        $debilidadesCubiertas   = array_map('array_unique', $debilidadesCubiertas);
        $oportunidadesCubiertas = array_map('array_unique', $oportunidadesCubiertas);
        $amenazasCubiertas      = array_map('array_unique', $amenazasCubiertas);

        // Comprueba si es una solicitud AJAX
        if ($request->ajax()) {
            // Si es una solicitud AJAX, puedes devolver una respuesta JSON
            return response()->json([
                'debilidades' => $debilidades,
                'fortalezas' => $fortalezas,
                'oportunidades' => $oportunidades,
                'amenazas' => $amenazas,
                'profiles' => $profiles,
            ]);
        }

        return view('admin.planificacion.fodas.groups.crossing-environments', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function listadoCategoriaAspectos(Request $request)
    {
        $idPerfil = $request->idPerfil;
        $idCategoria = $request->idCategoria;
        $categoria = FodaModelo::find($idCategoria);
        $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->join('planificacion.foda_models', 'planificacion.foda_analisis.aspecto_id', '=', 'planificacion.foda_models.id')
            ->where('planificacion.foda_models.parent_id', '=', $idCategoria) // Filtrar por parent_id
            ->select('planificacion.foda_analisis.*', 'planificacion.foda_models.name', 'planificacion.foda_models.environment', 'planificacion.foda_models.parent_id')
            ->with('aspecto')
            ->get();

        $perfil = FodaPerfil::find($idPerfil);

        if ($analisis->isNotEmpty()) {
            // colección no está vacía
            return view('admin.planificacion.fodas.analisis.listado-categorias-aspectos', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        } else {
            // colección vacía
            return view('admin.planificacion.fodas.analisis.error', get_defined_vars())
                ->with('i', ($request->input('page', 1) - 1) * 5);
        }
    }

    public function analisisCategoriasAmbienteInterno(Request $request, $idPerfil)
    {
        $analisis = FodaAnalisis::where('perfil_id', $idPerfil)->get();
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categories()->nombre($request->get('name'))->where('environment', 'Interno')->paginate(20);
        $environment = "Ambiente interno";

        foreach ($categorias as $categoria) {
            $v[] = $aspectos = FodaModelo::where('parent_id', $categoria->id)->get();
        }

        return view('admin.planificacion.fodas.analisis.analisis-categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function analisisCategoriasAmbienteExterno(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categories()->nombre($request->get('nombre'))->where('environment', 'Externo')->paginate(20);
        $environment = "Ambiente externo";

        return view('admin.planificacion.fodas.analisis.analisis-categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function categoriasAmbienteInterno(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categorias()->where('ambiente', 'Interno')->get();

        return view('admin.planificacion.fodas.analisis.categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function categoriasAmbienteExterno(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfiles = FodaPerfil::find($idPerfil);
        $categorias = $perfiles->categorias()->where('ambiente', 'Interno')->get();

        return view('admin.planificacion.fodas.analisis.categorias', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function aspectosCategoria(Request $request, $idPerfil, $idCategoria)
    {

        $idPerfil = $request->idPerfil;
        $idCategoria = $request->idCategoria;
        $categoria = FodaModelo::find($idCategoria);
        $analisis = FodaAnalisis::where('perfil_id', $idPerfil)->get();
        $aspectos = FodaModelo::where('parent_id', $idCategoria)->get();

        $aspectosChecked = [];

        foreach ($analisis as $v) {
            $aspectosChecked[] = $v->aspecto->id;
        }

        return view('admin.planificacion.fodas.analisis.aspectos', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(Request $request)
    {
        $idPerfil = $request->idPerfil;
        $idAspecto = $request->idAspecto;
        $perfil = FodaPerfil::where('id', '=', $idPerfil)->get();
        $aspecto = FodaAspecto::where('id', '=', $idAspecto)->get();
        $categoriaId = $aspecto[0]->categoria_id;
        $ambiente = FodaCategoria::where('id', '=', $categoriaId)->get();

        return view('admin.planificacion.fodas.analisis.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'ocurrencia'   => 'required',
                    'impacto'      => 'required',
                    'tipo'         => 'required',
                ],
                [
                    'ocurrencia.required'     => 'Debe Indicar un nivel de Ocurrencia',
                    'impacto.required'     => 'Debe indicar un nivel de Impacto',
                    'tipo.required'     => 'Indique el Tipo',
                ]
            );
        };

        $analysis = FodaAnalisis::updateOrCreate(
            ['id' => $request->analysis_id],
            [
                'user_id'            => Auth::id(),
                'perfil_id'          => $request->perfil_id,
                'ocurrencia'         => $request->ocurrencia,
                'impacto'            => $request->impacto,
                'tipo'               => $request->tipo,
                // MECIP 2015 — solo aplica a Debilidad/Amenaza
                'causa_raiz'         => in_array($request->tipo, ['Debilidad', 'Amenaza'])
                                            ? $request->causa_raiz
                                            : null,
                'accion_mejora'      => in_array($request->tipo, ['Debilidad', 'Amenaza'])
                                            ? $request->accion_mejora
                                            : null,
                'control_preventivo' => in_array($request->tipo, ['Debilidad', 'Amenaza'])
                                            ? $request->control_preventivo
                                            : null,
            ]
        );

        return response()->json(['success' => 'Análisis creado satisfactoriamente']);
    }
    // $count = count($request->input('aspecto_id'));
    // for ($i = 0; $i < $count; ++$i) {
    //     $analisis = FodaAnalisis::create([
    //         'user_id'       => $request->user_id,
    //         'aspecto_id'    => $request->aspecto_id[$i],
    //         'perfil_id'     => $request->perfil_id,
    //         'tipo'          => $request->tipo,
    //         'ocurrencia'    => $request->ocurrencia,
    //         'impacto'       => $request->impacto,
    //     ]);
    // }
    // $idPerfil = $analisis->perfil_id;
    // $aspectoID = $analisis->aspecto_id;
    // $aspectos = FodaModelo::find($aspectoID);
    // $categoriaID = $aspectos->parent_id;
    // $categoria = FodaModelo::find($categoriaID);

    // return redirect()->route('foda-listado-categorias-aspectos', ['idCategoria' => $categoriaID, 'idPerfil' => $idPerfil])
    //     ->with('success', 'Aspectos Listos para analizar');


    public function show(Request $request, $id)
    {
        $analizados = FodaAnalisis::where('aspecto_id', '=', $id)->get();
        return view('admin.planificacion.fodas.analisis.ponderaciones', get_defined_vars())
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function ponderaciones(Request $request, $idAspecto, $idPerfil)
    {
        $idAspecto = $request->idAspecto;
        $idPerfil = $request->idPerfil;
        $analisis = FodaAnalisis::where('perfil_id', '=', $idPerfil)->where('aspecto_id', '=', $idAspecto)->get();
        $aspecto = FodaAspecto::where('id', '=', $idAspecto)->get();
        $categoriaId = $aspecto[0]->categoria_id;
        $ambiente = FodaCategoria::where('id', '=', $categoriaId)->get();

        return view('admin.planificacion.fodas.analisis.edit', get_defined_vars());
    }

    public function edit(Request $request, $id)
    {
        // $analisis = FodaAnalisis::find($id);
        // $aspectoID = $analisis->aspecto_id;
        // $aspecto = FodaModelo::find($aspectoID);
        // $categoriaID = $aspecto->parent_id;
        // $categoria = FodaModelo::find($categoriaID);
        // $ambiente = $categoria->environment;

        // return view('admin.planificacion.fodas.analisis.edit', get_defined_vars());

        $data = FodaAnalisis::with('model')->where('id', $id)->first();


        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $analisis = FodaAnalisis::find($id);
        $idPerfil = $analisis->perfil_id;
        $aspectoID = $analisis->aspecto_id;
        $aspecto = FodaModelo::find($aspectoID);
        $categoriaID = $aspecto->parent_id;
        $categoria = FodaModelo::find($categoriaID);
        $analisis->fill($request->all())->save();


        return redirect()->route('foda-listado-categorias-aspectos', ['idCategoria' => $categoria->id, 'idPerfil' => $idPerfil])
            ->with('success', 'Analizado satisfactoriamente');
    }

    public function destroy($id)
    {
        FodaAnalisis::find($id)->delete();
        return back()->with('success', 'Aspecto eliminado correctamente.');
    }

    public function calcularIEA(Request $request, $id)
    {
        $request->validate([
            'promedio_desempeno_6m'   => 'required|numeric|min:0',
            'inversion_historica_6m'  => 'required|numeric|min:0.0001',
        ]);

        $analisis = FodaAnalisis::findOrFail($id);
        $analisis->promedio_desempeno_6m  = $request->promedio_desempeno_6m;
        $analisis->inversion_historica_6m = $request->inversion_historica_6m;
        $analisis->calcularIEA();

        return response()->json([
            'iea_valor'         => $analisis->iea_valor,
            'iea_clasificacion' => $analisis->iea_clasificacion,
        ]);
    }

    /**
     * Genera Acción de Mejora y Control Preventivo MECIP usando Groq / Llama 3.
     */
    public function generarMecipIA(Request $request)
    {
        $request->validate([
            'aspecto_nombre' => 'required|string',
            'tipo'           => 'required|in:Debilidad,Amenaza',
            'causa_raiz'     => 'required|string',
            'ocurrencia'     => 'required|numeric',
            'impacto'        => 'required|numeric',
        ]);

        try {
            $groq = new \App\Services\GroqService();

            // Contexto del perfil si viene
            $contexto = 'IPS Paraguay';
            if ($request->perfil_id) {
                $perfil   = FodaPerfil::find($request->perfil_id);
                $contexto = $perfil ? strip_tags($perfil->name) . ' — IPS Paraguay' : $contexto;
            }

            $resultado = $groq->generarMecip(
                $request->aspecto_nombre,
                $request->tipo,
                $request->causa_raiz,
                (float) $request->ocurrencia,
                (float) $request->impacto,
                $contexto
            );

            return response()->json($resultado);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
