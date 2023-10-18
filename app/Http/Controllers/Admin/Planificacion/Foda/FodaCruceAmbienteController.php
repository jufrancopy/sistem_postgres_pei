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
use Codedge\Fpdf\Fpdf\Fpdf;
use App\ClasesPersonalizadas\Pdf;

class FodaCruceAmbienteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Administrador']);
        $this->middleware(['auth', 'role:Analista']);
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

    public function store(Request $request)
    {
        $cruce = FodaCruceAmbiente::create($request->except(['fortaleza_id', 'debilidad_id', 'oportunidad_id', 'amenaza_id']));

        $cruce->fortalezas()->attach($request->fortaleza_id);
        $cruce->oportunidades()->attach($request->oportunidad_id);
        $cruce->debilidades()->attach($request->debilidad_id);
        $cruce->amenazas()->attach($request->amenaza_id);

        $idPerfil = $cruce->perfil_id;

        return redirect()->route('foda-cruce-ambientes', $idPerfil)
            ->with('success', 'Estrategia Creada Satisfactoriamente');
    }

    public function descargarCrucePdf(Request $request, $idPerfil)
    {
        $idPerfil = $request->idPerfil;
        $cruce = FodaCruceAmbiente::where('perfil_id', $idPerfil)->first();
        $perfilNombre = $cruce->perfil->nombre ?? '';
        $contexto = $cruce->perfil->contexto ?? '';
        $autor = $cruce->user->name ?? '';
        $modelo = $cruce->perfil->modelo->nombre ?? '';

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

    public function edit($id)
    {
        $cruce = FodaCruceAmbiente::with('fortalezas')->findOrFail($id);
        $idPerfil = $cruce->perfil_id;
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

    public function update(Request $request, $id)
    {
        $cruce = FodaCruceAmbiente::find($id);

        $idPerfil = $cruce->perfil_id;
        $cruce->fill($request->except(['fortaleza_id', 'oportunidad_id', 'debilidad_id', 'amenaza_id']))->save();

        $cruce->fortalezas()->sync($request->fortaleza_id);
        $cruce->oportunidades()->sync($request->oportunidad_id);
        $cruce->debilidades()->sync($request->debilidad_id);
        $cruce->amenazas()->sync($request->amenaza_id);


        return redirect()->route('foda-cruce-ambientes', $idPerfil)
            ->with('success', 'Estrategia Creada Satisfactoriamente');
    }

    public function destroy($id)
    {
        $cruce = FodaCruceAmbiente::find($id)->delete();

        return back()->with('info', 'Estrategia eliminada correctamente.');
    }
}
