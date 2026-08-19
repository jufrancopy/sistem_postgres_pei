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
use Illuminate\Support\Facades\Validator;
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
        $juntas = Junta::with(['presidente', 'integrantes'])->withCount('intervenciones')->orderBy('nombre')->get();
        return view('admin.planificacion.juntas.index', compact('juntas'));
    }

    /**
     * Guardar o actualizar una Junta Consultiva (con integrantes y firma digital/sello)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'programa'           => 'required|in:salud,jubilaciones,finanzas,institucional',
            'presidente_user_id' => 'nullable|exists:users,id',
            'presidente_nombre'  => 'nullable|string|max:255',
            'presidente_cargo'   => 'required|string|max:255',
            'fines'              => 'nullable|string',
            'atribuciones'       => 'nullable|string',
            'ambito_competencia' => 'nullable|string|max:255',
            'integrantes'        => 'nullable|array',
            'integrantes.*'      => 'exists:users,id',
        ]);

        $id = $request->input('id');
        $junta = $id ? Junta::findOrFail($id) : new Junta();

        $junta->nombre             = $request->input('nombre');
        $junta->programa           = $request->input('programa');
        $junta->descripcion        = $request->input('descripcion');
        $junta->fines              = $request->input('fines');
        $junta->atribuciones       = $request->input('atribuciones');
        $junta->ambito_competencia = $request->input('ambito_competencia');
        
        $presiUser = $request->input('presidente_user_id') ? \App\Models\User::find($request->input('presidente_user_id')) : null;
        $junta->presidente_user_id = $presiUser ? $presiUser->id : null;
        $junta->presidente_nombre  = $presiUser ? $presiUser->name : ($request->input('presidente_nombre') ?: 'Presidente de la Junta');
        $junta->presidente_cargo   = $request->input('presidente_cargo');
        $junta->activo             = $request->has('activo') ? (bool)$request->input('activo') : true;

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

        // Sincronizar integrantes de la Junta (Usuarios de SIPLAN)
        $integrantesIds = (array) $request->input('integrantes', []);
        $junta->integrantes()->sync($integrantesIds);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Junta Consultiva guardada exitosamente.',
                'junta'   => $junta->load(['presidente', 'integrantes']),
            ]);
        }

        return redirect()->back()->with('success', 'Junta Consultiva guardada exitosamente con sus integrantes.');
    }

    /**
     * Eliminar una Junta Consultiva
     */
    public function destroy($id)
    {
        $junta = Junta::findOrFail($id);
        $junta->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Junta Consultiva eliminada correctamente.'
            ]);
        }

        return redirect()->back()->with('success', 'Junta Consultiva eliminada correctamente.');
    }

    /**
     * Crear nuevo usuario rápido in-situ desde el modal de Junta Consultiva
     */
    public function crearUsuarioRapido(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => bcrypt($request->input('password', '12345678')),
        ]);

        // Asignar rol predeterminado Participantes si Spatie Roles está activo
        if (class_exists('\Spatie\Permission\Models\Role') && \Spatie\Permission\Models\Role::where('name', 'Participantes')->exists()) {
            $user->assignRole('Participantes');
        }

        return response()->json([
            'success' => true,
            'message' => "Usuario {$user->name} creado exitosamente en SIPLAN.",
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
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
     * Remitir un avance o una/varias Acciones en Alerta Roja a la Junta de Intervención
     */
    public function remitirAlerta(Request $request)
    {
        $request->validate([
            'reporte_id'     => 'nullable',
            'pei_profile_id' => 'nullable',
            'accion_ids'     => 'nullable|array',
            'junta_id'       => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value && !Junta::find($value)) {
                        $fail('La Junta Consultiva seleccionada no existe.');
                    }
                },
            ],
            'prioridad'      => 'nullable|in:MEDIA,ALTA,EMERGENCIA',
            'notas_remision' => 'nullable|string',
        ]);

        $accionIds = (array) $request->input('accion_ids', []);
        if ($request->filled('pei_profile_id')) {
            $accionIds[] = $request->input('pei_profile_id');
        }

        $reporteId = $request->input('reporte_id');
        $reporte = null;
        if ($reporteId) {
            $reporte = PeiAccionReporte::with('accion.junta')->find($reporteId);
            if ($reporte && $reporte->accion) {
                $accionIds[] = $reporte->accion->id;
            }
        }

        $accionIds = array_unique(array_filter($accionIds));

        if (empty($accionIds)) {
            return response()->json(['success' => false, 'message' => 'No se seleccionó ninguna Acción Estratégica para remitir.'], 400);
        }

        // Resolver la Junta: 1) explícita del request, 2) de la acción, 3) subiendo por jerarquía, 4) primera del sistema
        $juntaId = $request->input('junta_id_preconfig') ?: $request->input('junta_id');

        if (!$juntaId && !empty($accionIds)) {
            // Buscar en la propia acción o subiendo por su jerarquía (goal → axi)
            foreach ($accionIds as $accId) {
                $nodo = PeiProfile::find($accId);
                while ($nodo) {
                    if ($nodo->junta_id) {
                        $juntaId = $nodo->junta_id;
                        break 2;
                    }
                    $nodo = $nodo->parent_id ? PeiProfile::find($nodo->parent_id) : null;
                }
            }
        }

        if (!$juntaId) {
            $juntaId = Junta::first()?->id;
        }

        if (!$juntaId) {
            return response()->json(['success' => false, 'message' => 'No existe ninguna Junta Consultiva configurada en el sistema. Por favor cree una Junta primero.'], 400);
        }

        $junta = Junta::findOrFail($juntaId);
        $expedientesCreados = [];

        foreach ($accionIds as $accId) {
            $accion = PeiProfile::find($accId);
            if (!$accion) continue;

            // Asociar la Junta a la Acción en la tabla pivot pei_profile_juntas
            $accion->juntas()->syncWithoutDetaching([$junta->id]);
            if (!$accion->junta_id) {
                $accion->junta_id = $junta->id;
                $accion->save();
            }

            // Buscar el último reporte en alerta si no se paso un reporte específico
            $repId = ($reporte && $reporte->pei_profile_id == $accion->id) ? $reporte->id : null;
            if (!$repId) {
                $ultReporte = PeiAccionReporte::where('pei_profile_id', $accion->id)->orderByDesc('fecha_reporte')->first();
                $repId = $ultReporte ? $ultReporte->id : null;
            }

            $expediente = JuntaIntervencion::create([
                'codigo_expediente'   => JuntaIntervencion::generarCodigoExpediente($junta),
                'junta_id'             => $junta->id,
                'pei_profile_id'       => $accion->id,
                'reporte_avance_id'   => $repId,
                'solicitante_user_id' => Auth::id(),
                'diagnostico'          => $request->input('notas_remision') ?: 'Remisión de Acción Estratégica en Alerta Roja para dictamen técnico de la Junta Consultiva.',
                'estado'               => 'PENDIENTE',
                'prioridad'            => $request->input('prioridad', 'ALTA'),
            ]);

            $expedientesCreados[] = $expediente->codigo_expediente;
        }

        $codigosStr = implode(', ', $expedientesCreados);
        return response()->json([
            'success' => true,
            'message' => count($expedientesCreados) === 1
                ? "Expediente {$codigosStr} remitido exitosamente a la {$junta->nombre}."
                : "Se generaron los Expedientes ({$codigosStr}) remitidos a la {$junta->nombre}.",
            'codigos' => $expedientesCreados,
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

}
