<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Admin\Planificacion\Foda\FodaAspecto;
use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Globales\Group;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

class FodaCruceAmbienteController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:Administrador']);
        // $this->middleware(['auth', 'role:Analista']);
    }

    public function index(Request $request, $idPerfil)
    {
        // 1. Si $idPerfil pertenece a un PeiProfile, resolver su foda_perfil_id vinculado
        $peiProfile = \App\Admin\Planificacion\Pei\PeiProfile::find($idPerfil);
        if ($peiProfile && $peiProfile->foda_perfil_id) {
            $idPerfil = $peiProfile->foda_perfil_id;
        }

        // $idPerfil viene como parámetro de ruta (UUID del perfil consolidado FODA)
        $perfil  = FodaPerfil::where('id', '=', $idPerfil)->first();
        $profile = $perfil; // alias que espera la vista crossing-environments
        $matriz  = config('foda.umbral_matriz') ?? 0.17;

        // Determinar si es perfil de tipo consolidado / grupal o si tiene group_id o dependencia
        $perfilIds = [$idPerfil];
        if ($perfil) {
            if ($perfil->group_id || in_array($perfil->type, ['consolidado', 'grupal'])) {
                $groupId = $perfil->group_id;
                if ($groupId) {
                    $groups = Group::descendantsOf($groupId);
                    $groupIds = array_merge([$groupId], $groups->pluck('id')->toArray());
                    $subPerfilIds = FodaPerfil::whereIn('group_id', $groupIds)->pluck('id')->toArray();
                    if (!empty($subPerfilIds)) {
                        $perfilIds = array_unique(array_merge($perfilIds, $subPerfilIds));
                    }
                }
            }
            if ($perfil->dependency_id) {
                $subPerfilDepIds = FodaPerfil::where('dependency_id', $perfil->dependency_id)->pluck('id')->toArray();
                if (!empty($subPerfilDepIds)) {
                    $perfilIds = array_unique(array_merge($perfilIds, $subPerfilDepIds));
                }
            }
        }

        $allAnalisis = FodaAnalisis::with('aspecto')
            ->whereIn('perfil_id', $perfilIds)
            ->select(
                DB::raw('planificacion.foda_analisis.*'),
                DB::raw('(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz')
            )
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->whereIn('tipo', ['Debilidad', 'Fortaleza', 'Oportunidad', 'Amenaza'])
            ->get();

        if ($allAnalisis->isNotEmpty() && count($perfilIds) > 1) {
            $uniqueAspects = $allAnalisis->unique('aspecto_id')->map(function ($item) use ($allAnalisis) {
                $maxScoreAspect = $allAnalisis->where('aspecto_id', $item->aspecto_id)->max('matriz');
                return $allAnalisis->where('aspecto_id', $item->aspecto_id)->firstWhere('matriz', $maxScoreAspect);
            })->filter();

            $debilidades   = $uniqueAspects->where('tipo', 'Debilidad')->values();
            $fortalezas    = $uniqueAspects->where('tipo', 'Fortaleza')->values();
            $oportunidades = $uniqueAspects->where('tipo', 'Oportunidad')->values();
            $amenazas      = $uniqueAspects->where('tipo', 'Amenaza')->values();
        } else {
            $debilidades   = $allAnalisis->where('tipo', 'Debilidad')->values();
            $fortalezas    = $allAnalisis->where('tipo', 'Fortaleza')->values();
            $oportunidades = $allAnalisis->where('tipo', 'Oportunidad')->values();
            $amenazas      = $allAnalisis->where('tipo', 'Amenaza')->values();
        }

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

        if ($request->ajax() || $request->get('modal')) {
            return view('admin.planificacion.fodas.groups.partials.crossing_content', get_defined_vars());
        }

        return view('admin.planificacion.fodas.groups.crossing-environments', get_defined_vars());
    }

    public function getCrossings(Request $request)
    {
        $data = [];

        if ($request->has('q')) {
            $query = FodaCruceAmbiente::select('id', 'estrategia', 'tipo');

            // Prioridad 1: foda_perfil_id directo
            if ($request->foda_perfil_id) {
                $query->where('perfil_id', $request->foda_perfil_id);
            } elseif ($request->pei_id) {
                $pei = \App\Admin\Planificacion\Pei\PeiProfile::find($request->pei_id);
                if ($pei && $pei->foda_perfil_id) {
                    $query->where('perfil_id', $pei->foda_perfil_id);
                }
            }

            $data = $query->where('estrategia', 'LIKE', '%' . $request->q . '%')->get();
        }

        return response()->json($data);
    }

    // Creamos la Estrategia FO
    public function FO(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $matriz =    0.17;

        //Ambiente Interno - Fortaleza
        $fortalezas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Fortaleza')
            ->get();

        //Ambiente Externo - Oportunidad
        $oportunidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Oportunidad')
            ->get();

        return view('admin.planificacion.fodas.analisis.cruces.fo', get_defined_vars());
    }

    public function DO(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $matriz =    0.17;

        //Ambiente Interno - Debilidad
        $debilidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Debilidad')
            ->get();

        $debilidadesChecked = [];

        foreach ($debilidades as $v) {
            $debilidadesChecked[] = $v->aspecto->id;
        }

        //Ambiente Externo - Oportunidad
        $oportunidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Oportunidad')
            ->get();

        $oportunidadesChecked = [];

        foreach ($oportunidades as $v) {
            $oportunidadesChecked[] = $v->aspecto->id;
        }

        return view('admin.planificacion.fodas.analisis.cruces.do', get_defined_vars());
    }

    public function FA(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $matriz =    0.17;

        //Ambiente Interno - Fortaleza
        $fortalezas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Fortaleza')
            ->get();

        $fortalezasChecked = [];

        foreach ($fortalezas as $v) {
            $fortalezasChecked[] = $v->aspecto->id;
        }

        //Ambiente Externo - Amenaza
        $amenazas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Amenaza')
            ->get();

        $amenazasChecked = [];

        foreach ($amenazas as $v) {
            $amenazasChecked[] = $v->aspecto->id;
        }
        return view('admin.planificacion.fodas.analisis.cruces.fa', get_defined_vars());
    }

    public function DA(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $matriz =    0.17;

        //Ambiente Interno - Debilidad
        $debilidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Debilidad')
            ->get();

        $debilidadesChecked = [];

        foreach ($debilidades as $v) {
            $debilidadesChecked[] = $v->aspecto->id;
        }

        //Ambiente Externo - Amenaza
        $amenazas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Amenaza')
            ->get();

        $amenazasChecked = [];

        foreach ($amenazas as $v) {
            $amenazasChecked[] = $v->aspecto->id;
        }


        return view('admin.planificacion.fodas.analisis.cruces.da', get_defined_vars());
    }

    public function foGroup(Request $request, $idPerfil)
    {

        $idGroup = FodaPerfil::findOrFail($idPerfil)->group_id;

        $groups = Group::descendantsOf($idGroup);
        $groupId = [];

        foreach ($groups as $group) {
            $groupId[] = $group->id;
        }

        $profile = FodaPerfil::where('group_id', $idGroup)->first();
        $idPerfil = $profile->id;

        $profiles = FodaPerfil::with(['group', 'model'])
            ->whereIn('group_id', $groupId)
            ->get();

        $perfilIds = $profiles->pluck('id');
        $matriz = 0.17;

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

        $fortalezas = $uniqueAspects->where('tipo', 'Fortaleza');
        $oportunidades = $uniqueAspects->where('tipo', 'Oportunidad');

        return view('admin.planificacion.fodas.analisis.cruces.fo', get_defined_vars());
    }

    public function doGroup(Request $request, $idPerfil)
    {

        $idGroup = FodaPerfil::findOrFail($idPerfil)->group_id;

        $groups = Group::descendantsOf($idGroup);
        $groupId = [];

        foreach ($groups as $group) {
            $groupId[] = $group->id;
        }

        $profile = FodaPerfil::where('group_id', $idGroup)->first();
        $idPerfil = $profile->id;

        $profiles = FodaPerfil::with(['group', 'model'])
            ->whereIn('group_id', $groupId)
            ->get();

        $perfilIds = $profiles->pluck('id');
        $matriz = 0.17;

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

        $oportunidades = $uniqueAspects->where('tipo', 'Oportunidad');


        return view('admin.planificacion.fodas.analisis.cruces.do', get_defined_vars());
    }

    public function faGroup(Request $request, $idPerfil)
    {

        $idGroup = FodaPerfil::findOrFail($idPerfil)->group_id;

        $groups = Group::descendantsOf($idGroup);
        $groupId = [];

        foreach ($groups as $group) {
            $groupId[] = $group->id;
        }

        $profile = FodaPerfil::where('group_id', $idGroup)->first();
        $idPerfil = $profile->id;

        $profiles = FodaPerfil::with(['group', 'model'])
            ->whereIn('group_id', $groupId)
            ->get();

        $perfilIds = $profiles->pluck('id');
        $matriz = 0.17;

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

        $fortalezas = $uniqueAspects->where('tipo', 'Fortaleza');

        $amenazas = $uniqueAspects->where('tipo', 'Amenaza');


        return view('admin.planificacion.fodas.analisis.cruces.fa', get_defined_vars());
    }

    public function daGroup(Request $request, $idPerfil)
    {

        $idGroup = FodaPerfil::findOrFail($idPerfil)->group_id;

        $groups = Group::descendantsOf($idGroup);
        $groupId = [];

        foreach ($groups as $group) {
            $groupId[] = $group->id;
        }

        $profile = FodaPerfil::where('group_id', $idGroup)->first();
        $idPerfil = $profile->id;

        $profiles = FodaPerfil::with(['group', 'model'])
            ->whereIn('group_id', $groupId)
            ->get();

        $perfilIds = $profiles->pluck('id');
        $matriz = 0.17;

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

        $debilidades = $uniqueAspects->where('tipo', 'Fortaleza');

        $amenazas = $uniqueAspects->where('tipo', 'Amenaza');


        return view('admin.planificacion.fodas.analisis.cruces.da', get_defined_vars());
    }

    public function store(Request $request)
    {
        $data = $request->except(['fortaleza_id', 'debilidad_id', 'oportunidad_id', 'amenaza_id']);
        if (empty($data['user_id']) && Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $cruce = FodaCruceAmbiente::create($data);

        if (!empty($request->fortaleza_id)) {
            $cruce->fortalezas()->attach($request->fortaleza_id);
        }
        if (!empty($request->oportunidad_id)) {
            $cruce->oportunidades()->attach($request->oportunidad_id);
        }
        if (!empty($request->debilidad_id)) {
            $cruce->debilidades()->attach($request->debilidad_id);
        }
        if (!empty($request->amenaza_id)) {
            $cruce->amenazas()->attach($request->amenaza_id);
        }

        if (Auth::check()) {
            app(\App\Services\GamificationService::class)->awardPoints(
                Auth::user(),
                'foda_cruce',
                'Estrategia Cruce FODA: ' . $cruce->tipo,
                30,
                $cruce,
                $cruce->perfil_id
            );
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Estrategia creada satisfactoriamente.',
                'cruce_id' => $cruce->id,
            ]);
        }

        $idPerfil = $cruce->perfil_id;
        $fodaProfile = FodaPerfil::findOrFail($idPerfil);
        $type = $fodaProfile->type;
        $groupId = $fodaProfile->group_id;
        if ($type == 'consolidado') {
            return redirect()->route('foda-matriz-groups-crossing', $groupId)->with('success', 'Estrategia Creada Satisfactoriamente');
        } else {
            return redirect()->route('foda-cruce-ambientes', $idPerfil)->with('success', 'Estrategia Creada Satisfactoriamente');
        }
    }

    public function descargarCrucePdf(Request $request, $idPerfil)
    {
        $perfil = FodaPerfil::findOrFail($idPerfil);
        $matriz = 0.17;

        $fortalezas = FodaAnalisis::where('perfil_id', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Fortaleza')->get();

        $debilidades = FodaAnalisis::where('perfil_id', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Debilidad')->get();

        $oportunidades = FodaAnalisis::where('perfil_id', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Oportunidad')->get();

        $amenazas = FodaAnalisis::where('perfil_id', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Amenaza')->get();

        $FOs = FodaCruceAmbiente::with(['fortalezas', 'oportunidades'])->where('tipo', 'FO')->where('perfil_id', $idPerfil)->get();
        $DOs = FodaCruceAmbiente::with(['debilidades', 'oportunidades'])->where('tipo', 'DO')->where('perfil_id', $idPerfil)->get();
        $FAs = FodaCruceAmbiente::with(['fortalezas', 'amenazas'])->where('tipo', 'FA')->where('perfil_id', $idPerfil)->get();
        $DAs = FodaCruceAmbiente::with(['debilidades', 'amenazas'])->where('tipo', 'DA')->where('perfil_id', $idPerfil)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.planificacion.fodas.analisis.cruce-pdf',
            compact('perfil', 'fortalezas', 'debilidades', 'oportunidades', 'amenazas', 'FOs', 'DOs', 'FAs', 'DAs')
        )
        ->setPaper('a4', 'landscape')
        ->setOption(['isPhpEnabled' => true, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'DejaVu Sans']);

        $nombre = 'cruce-ambientes-' . \Str::slug(strip_tags($perfil->name ?? 'foda')) . '.pdf';

        return $pdf->download($nombre);
    }

    public function edit(Request $request, $id)
    {
        $cruce = FodaCruceAmbiente::with('fortalezas')->findOrFail($id);
        $idPerfil = $cruce->perfil_id;
        $matriz =    0.17;

        $fodaProfile = FodaPerfil::findOrFail($idPerfil);
        $type = $fodaProfile->type;

        $idGroup = $fodaProfile->group_id;

        if ($type == 'consolidado') {
            $groups = Group::descendantsOf($idGroup);
            $groupId = [];

            foreach ($groups as $group) {
                $groupId[] = $group->id;
            }

            $perfil = FodaPerfil::where('group_id', $idGroup)->first();
            $idPerfil = $perfil->id;

            $profiles = FodaPerfil::with(['group', 'model'])
                ->whereIn('group_id', $groupId)
                ->get();

            $perfilIds = $profiles->pluck('id');
            $matriz = 0.17;

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
            $FOs = FodaCruceAmbiente::where('tipo', '=', 'FO')->where('perfil_id', '=', $idPerfil)->get();
            $DOs = FodaCruceAmbiente::where('tipo', '=', 'DO')->where('perfil_id', '=', $idPerfil)->get();
            $FAs = FodaCruceAmbiente::where('tipo', '=', 'FA')->where('perfil_id', '=', $idPerfil)->get();
            $DAs = FodaCruceAmbiente::where('tipo', '=', 'DA')->where('perfil_id', '=', $idPerfil)->get();
            // Comprueba si es una solicitud AJAX

            $fortalezasChecked = [];
            foreach ($cruce->fortalezas as $v) {
                $fortalezasChecked[] = $v->id;
            }

            $oportunidadesChecked = [];
            foreach ($cruce->oportunidades as $v) {
                $oportunidadesChecked[] = $v->id;
            }

            $debilidadesChecked = [];

            foreach ($cruce->debilidades as $v) {
                $debilidadesChecked[] = $v->id;
            }

            $amenazasChecked = [];

            foreach ($cruce->amenazas as $v) {
                $amenazasChecked[] = $v->id;
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'tipo'         => $cruce->tipo,
                    'estrategia'   => $cruce->estrategia,
                    'fortalezas'   => $cruce->fortalezas->pluck('id'),
                    'oportunidades'=> $cruce->oportunidades->pluck('id'),
                    'debilidades'  => $cruce->debilidades->pluck('id'),
                    'amenazas'     => $cruce->amenazas->pluck('id'),
                ]);
            }

            return view('admin.planificacion.fodas.analisis.cruces.edit', get_defined_vars());
        } else {
            //Ambiente Interno - Fortaleza
            $fortalezas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
                ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
                ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
                ->where('tipo', 'Fortaleza')
                ->get();

            //Ambiente Externo - Oportunidad
            $oportunidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
                ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
                ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
                ->where('tipo', 'Oportunidad')
                ->get();

            //Ambiente Interno - Debilidad
            $debilidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
                ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
                ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
                ->where('tipo', 'Debilidad')
                ->get();

            //Ambiente Externo - Amenaza
            $amenazas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
                ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
                ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
                ->where('tipo', 'Amenaza')
                ->get();
            $fortalezasChecked = [];
            foreach ($cruce->fortalezas as $v) {
                $fortalezasChecked[] = $v->id;
            }

            $oportunidadesChecked = [];
            foreach ($cruce->oportunidades as $v) {
                $oportunidadesChecked[] = $v->id;
            }

            $debilidadesChecked = [];

            foreach ($cruce->debilidades as $v) {
                $debilidadesChecked[] = $v->id;
            }

            $amenazasChecked = [];

            foreach ($cruce->amenazas as $v) {
                $amenazasChecked[] = $v->id;
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'tipo'         => $cruce->tipo,
                    'estrategia'   => $cruce->estrategia,
                    'fortalezas'   => $cruce->fortalezas->pluck('id'),
                    'oportunidades'=> $cruce->oportunidades->pluck('id'),
                    'debilidades'  => $cruce->debilidades->pluck('id'),
                    'amenazas'     => $cruce->amenazas->pluck('id'),
                ]);
            }

            return view('admin.planificacion.fodas.analisis.cruces.edit', get_defined_vars());
        }
    }

    public function update(Request $request, $id)
    {
        $cruce = FodaCruceAmbiente::find($id);

        $idPerfil = $cruce->perfil_id;
        $cruce->fill($request->except(['fortaleza_id', 'oportunidad_id', 'debilidad_id', 'amenaza_id']))->save();

        $cruce->fortalezas()->sync($request->fortaleza_id);
        $cruce->oportunidades()->sync($request->oportunidad_id);
        $cruce->debilidades()->sync($request->debilidad_id);
        $cruce->amenazas()->sync($request->amenaza_id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Estrategia actualizada satisfactoriamente.',
                'cruce_id' => $cruce->id,
            ]);
        }

        $idPerfil = $cruce->perfil_id;
        $fodaProfile = FodaPerfil::findOrFail($idPerfil);
        $type = $fodaProfile->type;
        $groupId = $fodaProfile->group_id;

        if ($type == 'consolidado') {
            return redirect()->route('foda-matriz-groups-crossing', $groupId)->with('success', 'Estrategia Creada Satisfactoriamente');;
        } else {
            return redirect()->route('foda-cruce-ambientes', $idPerfil)->with('success', 'Estrategia Creada Satisfactoriamente');
        }
    }

    public function destroy(Request $request, $id)
    {
        $cruce = FodaCruceAmbiente::find($id);
        if ($cruce) {
            $cruce->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Estrategia eliminada correctamente.',
            ]);
        }

        return back()->with('info', 'Estrategia eliminada correctamente.');
    }

    /**
     * Genera una estrategia FODA usando Groq / Llama 3 vía GroqService.
     * POST /foda-cruce-ambientes-ia
     */
    public function generarConIA(Request $request)
    {
        $request->validate([
            'tipo'    => 'required|in:FO,DO,FA,DA',
            'grupo1'  => 'required|array|min:1',
            'grupo2'  => 'required|array|min:1',
        ]);

        $tipo   = $request->tipo;
        $grupo1 = $request->grupo1;   // [['id'=>..,'name'=>..,'prefix'=>..], ...]
        $grupo2 = $request->grupo2;

        // Obtener contexto institucional desde el perfil si se pasa perfil_id
        $contexto = 'IPS Paraguay';
        if ($request->perfil_id) {
            $perfil = FodaPerfil::find($request->perfil_id);
            if ($perfil && $perfil->name) {
                $contexto = strip_tags($perfil->name);
            }
        }

        try {
            $groq      = new \App\Services\GroqService();
            $estrategia = $groq->generarEstrategiaFoda($tipo, $grupo1, $grupo2, $contexto);

            return response()->json(['estrategia' => $estrategia]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}