<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Admin\Planificacion\Foda\FodaAspecto;
use App\Admin\Planificacion\Foda\FodaCategoria;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;
use App\Admin\Globales\Group;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\ClasesPersonalizadas\Pdf;


class FodaCruceAmbienteController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:Administrador']);
        // $this->middleware(['auth', 'role:Analista']);
    }

    public function index(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $perfil = FodaPerfil::where('id', '=', $idPerfil)->first();
        $matriz =    0.17;

        //Ambiente Interno - Debilidad
        $debilidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Debilidad')
            ->get();

        //Ambiente Interno - Fortaleza
        $fortalezas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Fortaleza')
            ->get();

        //Ambiente Externo - Oportunidad
        $oportunidades = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Oportunidad')
            ->get();

        //Ambiente Externo - Amenaza
        $amenazas = FodaAnalisis::where('perfil_id', '=', $idPerfil)
            ->select(DB::raw('planificacion.foda_analisis.*,(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) as matriz'))
            ->whereRaw("(planificacion.foda_analisis.ocurrencia * planificacion.foda_analisis.impacto) > $matriz")
            ->where('tipo', 'Amenaza')
            ->get();

        $FOs = FodaCruceAmbiente::where('tipo', '=', 'FO')->where('perfil_id', '=', $idPerfil)->get();
        $DOs = FodaCruceAmbiente::where('tipo', '=', 'DO')->where('perfil_id', '=', $idPerfil)->get();
        $FAs = FodaCruceAmbiente::where('tipo', '=', 'FA')->where('perfil_id', '=', $idPerfil)->get();
        $DAs = FodaCruceAmbiente::where('tipo', '=', 'DA')->where('perfil_id', '=', $idPerfil)->get();

        return view('admin.planificacion.fodas.analisis.cruce-ambientes', get_defined_vars());
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

        $debilidades = $uniqueAspects->where('tipo', 'Fortaleza');

        $amenazas = $uniqueAspects->where('tipo', 'Amenaza');


        return view('admin.planificacion.fodas.analisis.cruces.da', get_defined_vars());
    }

    public function store(Request $request)
    {
        $cruce = FodaCruceAmbiente::create($request->except(['fortaleza_id', 'debilidad_id', 'oportunidad_id', 'amenaza_id']));

        $cruce->fortalezas()->attach($request->fortaleza_id);
        $cruce->oportunidades()->attach($request->oportunidad_id);
        $cruce->debilidades()->attach($request->debilidad_id);
        $cruce->amenazas()->attach($request->amenaza_id);

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

    public function descargarCrucePdf(Request $request, $idPerfil)
    {

        $idPerfil = $request->idPerfil;
        $cruce = FodaCruceAmbiente::where('perfil_id', $idPerfil)->first();
        $perfilNombre = $cruce->perfil->name ?? '';
        $contexto = $cruce->perfil->context ?? '';
        $autor = $cruce->user->name ?? '';
        $modelo = $cruce->perfil->model->name ?? '';

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(138, 135, 135);


        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 0, iconv('UTF-8', 'CP1252', 'Perfil: '));
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 0, iconv('UTF-8', 'CP1252', $perfilNombre), 0, 1, 'L');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 0, iconv('UTF-8', 'CP1252', 'Analista: '));
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 0, iconv('UTF-8', 'CP1252', $autor), 0, 1, 'L');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 0, iconv('UTF-8', 'CP1252', 'Contexto: '));
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 0, iconv('UTF-8', 'CP1252', $contexto), 0, 1, 'L');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 0, iconv('UTF-8', 'CP1252', 'Modelo de Análisis: '));
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 0, iconv('UTF-8', 'CP1252', $modelo), 0, 1, 'L');
        $pdf->Ln(10);

        $pdf->SetTitle($perfilNombre, true);
        $pdf->SetAuthor($autor, true);

        $column_width = $pdf->GetPageWidth() - 20;

        $titles = [
            'FO' => 'Estrategias Ofensivas (FO)',
            'DO' => 'Estrategias de Reorientación (DO)',
            'FA' => 'Estrategias Defensivas (FA)',
            'DA' => 'Estrategias de Supervivencia (DA)',
        ];

        // Crear un arreglo para almacenar los datos de la tabla
        $data = [];

        foreach ($titles as $tipo => $title) {
            $estrategias = FodaCruceAmbiente::where('tipo', $tipo)
                ->where('perfil_id', $idPerfil)
                ->get();

            $data[] = array(
                $title,
                '',
            );

            $counter = 1;
            foreach ($estrategias as $sample_text) {
                $data[] = array(
                    '',
                    $counter . '. - ' . $sample_text->estrategia,
                );
                $counter++;
            }
        }

        // Llamar a la función BasicTable para generar la tabla
        $pdf->BasicTable(array('', ''), $data);




        $headers = ['Content-Type' => 'application/pdf'];
        return Response::make($pdf->Output(), 200, $headers);
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

    public function destroy($id)
    {
        $cruce = FodaCruceAmbiente::find($id)->delete();

        return back()->with('info', 'Estrategia eliminada correctamente.');
    }
}
