<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planificacion\Junta;
use App\Models\Planificacion\JuntaIntervencion;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\PeiAccionReporte;
use App\Services\GroqService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JuntaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Módulos de gestión de Juntas (Consejo de Sabios)
     */
    public function index()
    {
        // Auto-semillar Juntas por defecto si está vacío
        if (Junta::count() === 0) {
            $this->semillarJuntasPorDefecto();
        }

        $juntas = Junta::withCount('intervenciones')->orderBy('nombre')->get();
        return view('admin.planificacion.juntas.index', compact('juntas'));
    }

    /**
     * Guardar o actualizar una Junta (con registro de firma digital/sello)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'programa'          => 'required|in:salud,jubilaciones,finanzas,institucional',
            'presidente_nombre' => 'required|string|max:255',
            'presidente_cargo'  => 'required|string|max:255',
        ]);

        $id = $request->input('id');
        $junta = $id ? Junta::findOrFail($id) : new Junta();

        $junta->nombre            = $request->input('nombre');
        $junta->programa          = $request->input('programa');
        $junta->descripcion       = $request->input('descripcion');
        $junta->presidente_nombre = $request->input('presidente_nombre');
        $junta->presidente_cargo  = $request->input('presidente_cargo');
        $junta->activo            = $request->has('activo') ? (bool)$request->input('activo') : true;

        if (!$junta->codigo) {
            $junta->codigo = Junta::generarCodigo($junta->programa);
        }

        // Firma Hológrafa Registrada (base64 canvas o upload)
        if ($request->filled('firma_digital_base64')) {
            $junta->firma_digital_url = $request->input('firma_digital_base64');
        } elseif ($request->hasFile('firma_digital_file')) {
            $path = $request->file('firma_digital_file')->store('juntas/firmas', 'public');
            $junta->firma_digital_url = '/storage/' . $path;
        }

        $junta->save();

        return redirect()->back()->with('success', 'Junta Consultiva guardada exitosamente con su Firma Registrada.');
    }

    /**
     * Bandeja de Intervención de Junta (Consejo de Sabios)
     */
    public function intervenciones(Request $request)
    {
        $estado = $request->get('estado', 'TODOS');
        $juntaId = $request->get('junta_id');

        $query = JuntaIntervencion::with(['junta', 'accion.indicador', 'reporte.usuario', 'solicitante'])
            ->orderBy('created_at', 'desc');

        if ($estado !== 'TODOS') {
            $query->where('estado', $estado);
        }
        if ($juntaId) {
            $query->where('junta_id', $juntaId);
        }

        $intervenciones = $query->paginate(15);
        $juntas = Junta::where('activo', true)->get();

        return view('admin.planificacion.juntas.intervenciones', compact('intervenciones', 'juntas', 'estado', 'juntaId'));
    }

    /**
     * Remitir un avance en Alerta Roja a la Junta de Intervención
     */
    public function remitirAlerta(Request $request)
    {
        $request->validate([
            'reporte_id'     => 'nullable|exists:planificacion.pei_accion_reportes,id',
            'pei_profile_id' => 'nullable|exists:planificacion.pei_profiles,id',
            'junta_id'       => 'nullable|exists:planificacion.juntas,id',
            'prioridad'      => 'nullable|in:MEDIA,ALTA,EMERGENCIA',
            'notas_remision' => 'nullable|string',
        ]);

        $reporteId = $request->input('reporte_id');
        $profileId = $request->input('pei_profile_id');
        $accion = null;
        $reporte = null;

        if ($reporteId) {
            $reporte = PeiAccionReporte::with('accion.junta')->find($reporteId);
            if ($reporte) {
                $accion = $reporte->accion;
            }
        } elseif ($profileId) {
            $accion = PeiProfile::with('junta')->find($profileId);
        }

        if (!$accion) {
            return response()->json(['success' => false, 'message' => 'No se encontró la Acción Estratégica asociada.'], 400);
        }

        // Determinar la Junta: especificada en modal o la asignada a la acción o la Junta de Salud por defecto
        $juntaId = $request->input('junta_id') ?: $accion->junta_id;
        if (!$juntaId) {
            $juntaPorDefecto = Junta::where('programa', 'salud')->first() ?: Junta::first();
            $juntaId = $juntaPorDefecto ? $juntaPorDefecto->id : null;
        }

        if (!$juntaId) {
            return response()->json(['success' => false, 'message' => 'No existe ninguna Junta configurada en el sistema. Por favor configure una Junta primero.'], 400);
        }

        $junta = Junta::findOrFail($juntaId);

        // Crear Expediente de Intervención
        $expediente = JuntaIntervencion::create([
            'codigo_expediente'   => JuntaIntervencion::generarCodigoExpediente($junta),
            'junta_id'             => $junta->id,
            'pei_profile_id'       => $accion->id,
            'reporte_avance_id'   => $reporte->id,
            'solicitante_user_id' => Auth::id(),
            'diagnostico'          => $request->input('notas_remision') ?: 'Reporte de Avance en Alerta Roja. Brecha detectada en la ejecución del indicador.',
            'estado'               => 'PENDIENTE',
            'prioridad'            => $request->input('prioridad', 'ALTA'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Expediente {$expediente->codigo_expediente} remitido exitosamente a la {$junta->nombre}.",
            'codigo'  => $expediente->codigo_expediente,
        ]);
    }

    /**
     * Emitir dictamen y estampar firma del Presidente + inyectar Acciones Operativas de Mitigación
     */
    public function emitirDictamen(Request $request, $id)
    {
        $request->validate([
            'diagnostico'                  => 'required|string',
            'recomendaciones_mitigacion'   => 'required|array|min:1',
            'recomendaciones_mitigacion.*' => 'required|string',
        ]);

        $intervencion = JuntaIntervencion::with(['junta', 'accion'])->findOrFail($id);
        $intervencion->diagnostico = $request->input('diagnostico');

        $mitigacionesText = $request->input('recomendaciones_mitigacion');
        $intervencion->recomendaciones_mitigacion = $mitigacionesText;
        $intervencion->estado = 'EMITIDO';
        $intervencion->firma_estampada_at = now();
        $intervencion->save();

        // Auto-inyectar como Acciones Operativas (Mejora Continua) bajo la Acción Estratégica en pei_profiles
        $accionPadre = $intervencion->accion;
        if ($accionPadre) {
            foreach ($mitigacionesText as $idx => $textoMitigacion) {
                if (trim($textoMitigacion) === '') continue;

                $orden = ($accionPadre->children()->max('order_item') ?? 0) + 1;
                PeiProfile::create([
                    'name'                => '<strong>[MITIGACIÓN JUNTA]</strong> ' . e($textoMitigacion),
                    'type'                => 'action_op',
                    'level'               => 'action_op',
                    'parent_id'           => $accionPadre->id,
                    'year_start'          => $accionPadre->year_start,
                    'year_end'            => $accionPadre->year_end,
                    'period'              => $accionPadre->period,
                    'user_id'             => Auth::id(),
                    'order_item'          => $orden,
                    'junta_id'            => $intervencion->junta_id,
                    'es_mitigacion_junta' => true,
                ]);
            }
        }

        return redirect()->back()->with('success', "Dictamen {$intervencion->codigo_expediente} emitido con la Firma Registrada de la Junta e inyectado a las Acciones Operativas.");
    }

    /**
     * Sugerir mitigación utilizando IA (Llama 3.3 70B / Groq)
     */
    public function sugerirMitigacionIa(Request $request, GroqService $groq)
    {
        $id = $request->input('intervencion_id');
        $intervencion = JuntaIntervencion::with(['accion.indicador', 'reporte'])->findOrFail($id);

        $prompt = "Actúa como un Comité Consultivo de Expertos en Gestión Pública e IPS (Instituto de Previsión Social). " .
                  "Se ha registrado una Alerta Roja en la Acción Estratégica: '{$intervencion->accion->name}'. " .
                  "Reporte de Avance: '{$intervencion->reporte->descripcion_avance}'. " .
                  "Sugerí 3 Acciones Operativas concretas de Mitigación/Contingencia redactadas técnicamente. " .
                  "Responde únicamente en formato JSON con la siguiente estructura: " .
                  "{\"diagnostico\": \"Breve diagnóstico técnico del problema\", \"mitigaciones\": [\"Acción 1...\", \"Acción 2...\", \"Acción 3...\"]}";

        $aiResponse = $groq->completarTexto($prompt);

        if ($aiResponse) {
            $cleanJson = preg_replace('/```json\s*|\s*```/', '', trim($aiResponse));
            $parsed = json_decode($cleanJson, true);

            if (is_array($parsed) && isset($parsed['mitigaciones'])) {
                return response()->json([
                    'success'     => true,
                    'diagnostico' => $parsed['diagnostico'] ?? '',
                    'mitigaciones'=> $parsed['mitigaciones'],
                ]);
            }
        }

        return response()->json([
            'success'     => true,
            'diagnostico' => 'Desviación en el cumplimiento de metas presupuestarias y operativas.',
            'mitigaciones'=> [
                'Reasignar presupuesto de contingencia para acelerar contrataciones o adquisición de insumos.',
                'Establecer mesa técnica semanal de seguimiento operativo con el área ejecutora.',
                'Redefinir cronograma crítico y priorizar entregables de mayor impacto en la meta.'
            ],
        ]);
    }

    /**
     * Crear Juntas por defecto para IPS
     */
    private function semillarJuntasPorDefecto()
    {
        Junta::create([
            'nombre'            => 'Junta Consultiva de Salud y Servicios Médicos',
            'codigo'            => 'JUNTA-SALUD-01',
            'programa'          => 'salud',
            'descripcion'       => 'Comité de expertos médicos para el análisis de alertas en hospitales, abastecimiento e insumos.',
            'presidente_nombre' => 'Dr. Carlos Gustavo Benítez',
            'presidente_cargo'  => 'Presidente de la Junta Consultiva de Salud',
            'activo'            => true,
        ]);

        Junta::create([
            'nombre'            => 'Junta Consultiva de Jubilaciones y Pensiones',
            'codigo'            => 'JUNTA-JUB-01',
            'programa'          => 'jubilaciones',
            'descripcion'       => 'Comité técnico actuarial para dictámenes sobre sostenibilidad del fondo de jubilaciones.',
            'presidente_nombre' => 'Lic. María Elena Ramos',
            'presidente_cargo'  => 'Presidenta de la Junta de Jubilaciones',
            'activo'            => true,
        ]);

        Junta::create([
            'nombre'            => 'Junta Consultiva de Administración y Finanzas',
            'codigo'            => 'JUNTA-FIN-01',
            'programa'          => 'finanzas',
            'descripcion'       => 'Consejo para contingencias presupuestarias, obras e infraestructura de salud.',
            'presidente_nombre' => 'Ing. Roberto Silva',
            'presidente_cargo'  => 'Presidente de la Junta de Finanzas',
            'activo'            => true,
        ]);
    }
}
