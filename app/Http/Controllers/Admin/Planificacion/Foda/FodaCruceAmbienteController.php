<?php

namespace App\Http\Controllers\Admin\Planificacion\Foda;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        
        $FOs= FodaCruceAmbiente::where('tipo','=', 'FO')->where('perfil_id','=', $idPerfil)->get();
        $DOs= FodaCruceAmbiente::where('tipo','=', 'DO')->where('perfil_id','=', $idPerfil)->get();
        $FAs= FodaCruceAmbiente::where('tipo','=', 'FA')->where('perfil_id','=', $idPerfil)->get();
        $DAs= FodaCruceAmbiente::where('tipo','=', 'DA')->where('perfil_id','=', $idPerfil)->get();
        
        return view('admin.planificacion.fodas.analisis.cruce-ambientes', get_defined_vars());
    }

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
        // //Insertar un array en un campo
        // $cruces = $request->all();
        // $cruces['oportunidad_id'] = implode(',',$request->oportunidad_id);
        // FodaCruceAmbiente::create($cruces);
        
        //Cruzar Fortaleza - Oprtunidad
        $cruce = FodaCruceAmbiente::create($request->except(['fortaleza_id', 'debilidad_id', 'oportunidad_id', 'amenaza_id']));
        
        $cruce->fortalezas()->attach($request->fortaleza_id);
        $cruce->oportunidades()->attach($request->oportunidad_id);
        $cruce->debilidades()->attach($request->debilidad_id);
        $cruce->amenazas()->attach($request->amenaza_id);
        
        $idPerfil = $cruce->perfil_id; 

        return redirect()->route('foda-cruce-ambientes', $idPerfil)
            ->with('success', 'Estrategia Creada Satisfactoriamente');
        
    }

public function descargarCrucePdf(Request $request, $idPerfil){
        
        $idPerfil = $request->idPerfil;
        $matriz = 0.17;
        $cruce = FodaCruceAmbiente::where('perfil_id', '=', $idPerfil)->get();
        $perfilNombre = isset($cruce[0]->perfil->nombre) ? $cruce[0]->perfil->nombre : '';
        $contexto = isset($cruce[0]->perfil->contexto) ? $cruce[0]->perfil->contexto : '';
        $autor = isset($cruce[0]->user->name) ? $cruce[0]->user->name : '';
        $modelo = isset($cruce[0]->perfil->modelo->nombre) ? $cruce[0]->perfil->modelo->nombre : '';
        
        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',12);
        $pdf->SetTextColor(138,135,135);
        $pdf->Cell(50,0,utf8_decode('Perfiles: '));
        $pdf->Cell(0,0,utf8_decode($perfilNombre),0,1, 'L');
        $pdf->Ln(10);

        $pdf->Cell(50,0,utf8_decode('Contexto: '));
        $pdf->Cell(0,0,utf8_decode($contexto),0,1, 'L');
        $pdf->Ln(10);

        $pdf->Cell(50,0,utf8_decode('Creado por: '));
        $pdf->Cell(0,0,utf8_decode($autor),0,1, 'L');
        $pdf->Ln(10);
        
        $pdf->Cell(50,0,utf8_decode('Modelo de Analisis: '));
        $pdf->Cell(0,0,utf8_decode($modelo),0,1, 'L');
        $pdf->Ln(10);
        
        $pdf->SetTitle ($perfilNombre, true);
        $pdf->SetAuthor($autor, true);
        $pdf->SetCreator($autor, true);

        $column_width = ($pdf->GetPageWidth() - 30) / 2;

        $FO = "Estrategias Ofensivas (FO)";
        $FOs= FodaCruceAmbiente::where('tipo','=', 'FO')->where('perfil_id','=', $idPerfil)->get();
        $pdf->Cell(0,9,utf8_decode($FO),1,true);
        foreach ($FOs as $sample_text){
            $pdf->MultiCellBlt($column_width, 8, chr(149), utf8_decode($sample_text->estategia) . ' ' . utf8_decode($sample_text->estrategia));  
        }

        $DO = "Estrategias de Reorientación (DO)";
        $DOs= FodaCruceAmbiente::where('tipo','=', 'DO')->where('perfil_id','=', $idPerfil)->get();
        $pdf->Cell(0,9,utf8_decode($DO),1, true);
        foreach ($DOs as $sample_text){
            $pdf->MultiCellBlt($column_width, 8, chr(149), utf8_decode($sample_text->estategia) . ' ' . utf8_decode($sample_text->estrategia));  
        }

        $FA = "Estrategias Defensivas (FA)";
        $FAs= FodaCruceAmbiente::where('tipo','=', 'FA')->where('perfil_id','=', $idPerfil)->get();
        $pdf->Cell(0,9,utf8_decode($FA),1,true);
        foreach ($FAs as $sample_text){
            $pdf->MultiCellBlt($column_width, 8, chr(149), utf8_decode($sample_text->estategia). ' ' . utf8_decode($sample_text->estrategia));  
            
        }

        $DA = "Estrategias de Supervivencia (DA)" ;   
        
        $DAs= FodaCruceAmbiente::where('tipo','=', 'DA')->where('perfil_id','=', $idPerfil)->get();
        $pdf->Cell(0,9,utf8_decode($DA),1, true);
        
        foreach ($DAs as $sample_text){
            $pdf->MultiCellBlt($column_width, 8, chr(149), utf8_decode($sample_text->estategia) . ' ' . utf8_decode($sample_text->estrategia));  
        }

        $headers = ['Content-Type' => 'application/pdf'];
        return Response::make($pdf->Output(),200, $headers);

    }
    public function edit($id)
    {
        $cruce = FodaCruceAmbiente::find($id);
        
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cruce = FodaCruceAmbiente::find($id);
        $idPerfil = $cruce->perfil_id;
        $cruce->fortalezas()->sync($request->fortaleza_id);
        $cruce->oportunidades()->sync($request->oportunidad_id);
        $cruce->debilidades()->sync($request->debilidad_id);
        $cruce->amenazas()->sync($request->amenaza_id);
        $cruce->fill($request->except(['fortaleza_id', 'oportunidad_id', 'debilidad_id','amenaza_id']))->save();

        return redirect()->route('foda-cruce-ambientes', $idPerfil)
            ->with('success', 'Estrategia Creada Satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cruce = FodaCruceAmbiente::find($id)->delete();
        
        return back()->with('info', 'Estrategia eliminada correctamente.');

    }
}
