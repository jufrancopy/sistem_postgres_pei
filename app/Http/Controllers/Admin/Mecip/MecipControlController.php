<?php

namespace App\Http\Controllers\Admin\Mecip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Mecip\MecipCaso;
use App\Models\Mecip\MecipCasoActividad;
use App\Models\Mecip\MecipCasoTarea;
use App\Models\Mecip\MecipCasoComponente;
use App\Models\Mecip\MecipCasoComentario;
use App\Models\Mecip\MecipCasoCambio;
use App\Models\User;
use App\Events\MecipNotificacionEvent;

class MecipControlController extends Controller
{
    public function __construct()
    {
        // Sin restricción de middleware auth rígido para permitir operaciones fluidas desde el Replicador IPS
    }

    /**
     * Bandeja principal de monitoreo de cambios MECIP IPS con DataTables y Select2
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isLider = $user ? $user->hasRole('Líder MECIP') : false;
        $isAdmin = $user ? ($user->hasRole('Administrador') || $user->hasRole('Super Admin')) : true;

        $estado = $request->get('estado', 'TODOS');
        $subprocesoQuery = $request->get('subproceso', '');

        $query = MecipCaso::with(['liderMecip', 'creator', 'actividades', 'cambios'])->latest();

        if ($estado !== 'TODOS') {
            $query->where('estado_flujo', $estado);
        }

        if (!empty($subprocesoQuery)) {
            $query->where(function($q) use ($subprocesoQuery) {
                $q->where('codigo_subproceso', 'LIKE', "%{$subprocesoQuery}%")
                  ->orWhere('subproceso', 'LIKE', "%{$subprocesoQuery}%")
                  ->orWhere('numero_caso', 'LIKE', "%{$subprocesoQuery}%");
            });
        }

        // Si es Líder MECIP, priorizar sus casos asignados
        if ($isLider && !$isAdmin) {
            $query->where(function($q) use ($user) {
                $q->where('lider_mecip_id', $user->id)
                  ->orWhere('estado_flujo', 'remitido_lider');
            });
        }

        $casos = $query->get();

        // Obtener usuarios con rol Líder MECIP para los selectores Select2
        $lideresMecip = User::whereHas('roles', function($q) {
            $q->where('name', 'Líder MECIP');
        })->get();

        if ($lideresMecip->isEmpty()) {
            // Fallback si ningún usuario tiene el rol asignado aún
            $lideresMecip = User::limit(50)->get();
        }

        return view('admin.mecip.control.index', compact(
            'casos',
            'estado',
            'subprocesoQuery',
            'lideresMecip',
            'isLider',
            'isAdmin'
        ));
    }

    /**
     * Guarda la cabecera de un nuevo caso MECIP capturado
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_caso'        => 'required|string|max:100',
            'codigo_subproceso'  => 'required|string|max:100',
            'macroproceso'       => 'required|string|max:255',
            'proceso'            => 'required|string|max:255',
            'subproceso'         => 'required|string|max:255',
            'version'            => 'required|string|max:50',
            'lider_mecip_id'     => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $caso = MecipCaso::create([
                'numero_caso'          => trim($request->numero_caso),
                'codigo_subproceso'    => trim($request->codigo_subproceso),
                'macroproceso'         => trim($request->macroproceso),
                'proceso'              => trim($request->proceso),
                'subproceso'           => trim($request->subproceso),
                'version'              => trim($request->version),
                'fecha_elaboracion'    => $request->fecha_elaboracion ?: now()->toDateString(),
                'responsable_analisis' => $request->responsable_analisis ?: (Auth::user()?->name ?: 'Analista IPS'),
                'lider_mecip_id'       => $request->lider_mecip_id,
                'created_by'           => Auth::id() ?: 1,
                'estado_flujo'         => $request->lider_mecip_id ? 'remitido_lider' : 'borrador',
            ]);

            // Auditoría inicial
            MecipCasoCambio::create([
                'mecip_caso_id'   => $caso->id,
                'user_id'         => Auth::id() ?: 1,
                'estado_anterior' => 'nuevo',
                'estado_nuevo'    => $caso->estado_flujo,
                'observacion'     => 'Apertura de caso MECIP IPS y registro de metadatos.',
            ]);

            DB::commit();

            // Notificación Redis si se asignó a Líder MECIP
            if ($caso->lider_mecip_id) {
                event(new MecipNotificacionEvent(
                    $caso->id,
                    $caso->numero_caso,
                    $caso->codigo_subproceso,
                    'Se te ha asignado un nuevo caso MECIP para revisión y dictamen.',
                    'remitido',
                    $caso->lider_mecip_id
                ));
            }

            return redirect()->route('admin.mecip.control.show', $caso->id)
                ->with('success', "Caso MECIP Nº {$caso->numero_caso} registrado exitosamente.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error registrando caso MECIP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error registrando el caso: ' . $e->getMessage());
        }
    }

    /**
     * Importador rápido JSON/Texto Estructurado desde el sistema IPS
     */
    public function importarJson(Request $request)
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }

        $request->validate([
            'json_data' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $data = json_decode($request->json_data, true);
            if (!is_array($data)) {
                throw new \Exception('El formato JSON proporcionado no es válido.');
            }

            $numCaso = $data['numero_caso'] ?? ('CASO-' . rand(1000, 9999));

            $caso = MecipCaso::updateOrCreate(
                ['numero_caso' => $numCaso],
                [
                    'codigo_subproceso'    => $data['codigo_subproceso'] ?? ('GES_SUB_' . rand(10, 99)),
                    'macroproceso'         => $data['macroproceso'] ?? 'GESTIÓN INSTITUCIONAL IPS',
                    'proceso'              => $data['proceso'] ?? 'Gestión de Procesos',
                    'subproceso'           => $data['subproceso'] ?? 'Modelado de Procedimiento',
                    'version'              => $data['version'] ?? '1.0',
                    'fecha_elaboracion'    => $data['fecha_elaboracion'] ?? now()->toDateString(),
                    'responsable_analisis' => $data['responsable_analisis'] ?? (Auth::user()?->name ?: 'Analista IPS'),
                    'lider_mecip_id'       => $data['lider_mecip_id'] ?? null,
                    'created_by'           => Auth::id() ?: 1,
                    'estado_flujo'         => !empty($data['lider_mecip_id']) ? 'remitido_lider' : 'borrador',
                ]
            );

            // Limpiar componentes y actividades previas si se está actualizando un caso existente
            $caso->componentes()->delete();
            $caso->actividades()->delete();

            // Insumos
            if (!empty($data['insumos']) && is_array($data['insumos'])) {
                foreach ($data['insumos'] as $idx => $ins) {
                    MecipCasoComponente::create([
                        'mecip_caso_id'          => $caso->id,
                        'tipo'                   => 'insumo',
                        'nombre'                 => is_array($ins) ? ($ins['nombre'] ?? ($ins['insumo'] ?? 'Insumo')) : $ins,
                        'entidad_origen_destino' => is_array($ins) ? ($ins['proveedor'] ?? ($ins['entidad'] ?? 'Proveedor')) : 'Proveedor',
                        'descripcion'            => is_array($ins) ? ($ins['descripcion'] ?? ($ins['caracteristicas'] ?? null)) : null,
                        'orden'                  => $idx + 1,
                    ]);
                }
            }

            // Productos
            if (!empty($data['productos']) && is_array($data['productos'])) {
                foreach ($data['productos'] as $idx => $prod) {
                    MecipCasoComponente::create([
                        'mecip_caso_id'          => $caso->id,
                        'tipo'                   => 'producto',
                        'nombre'                 => is_array($prod) ? ($prod['nombre'] ?? ($prod['producto'] ?? 'Producto')) : $prod,
                        'entidad_origen_destino' => is_array($prod) ? ($prod['cliente'] ?? ($prod['entidad'] ?? 'Cliente/Grupo Interés')) : 'Cliente/Grupo Interés',
                        'descripcion'            => is_array($prod) ? ($prod['descripcion'] ?? ($prod['caracteristicas'] ?? null)) : null,
                        'orden'                  => $idx + 1,
                    ]);
                }
            }

            // Actividades y Tareas
            if (!empty($data['actividades']) && is_array($data['actividades'])) {
                foreach ($data['actividades'] as $idx => $actData) {
                    $actividad = MecipCasoActividad::create([
                        'mecip_caso_id'    => $caso->id,
                        'codigo_actividad' => $actData['codigo'] ?? ('ACT_' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT)),
                        'nombre'           => $actData['nombre'] ?? "Actividad #" . ($idx + 1),
                        'objetivo'         => $actData['objetivo'] ?? null,
                        'responsable'      => $actData['responsable'] ?? null,
                        'orden'            => $idx + 1,
                    ]);

                    if (!empty($actData['tareas']) && is_array($actData['tareas'])) {
                        foreach ($actData['tareas'] as $tIdx => $tData) {
                            MecipCasoTarea::create([
                                'actividad_id'            => $actividad->id,
                                'descripcion'             => is_array($tData) ? ($tData['descripcion'] ?? 'Tarea') : $tData,
                                'tiempo_estimado_minutos' => is_array($tData) ? ($tData['tiempo_minutos'] ?? 15) : 15,
                                'orden'                   => $tIdx + 1,
                            ]);
                        }
                    }
                }
            }

            // Auditoría (Asegurando campos válidos de la migración)
            $firstUser = \App\Models\User::first();
            $userId = Auth::id() ?: ($firstUser ? $firstUser->id : 1);

            MecipCasoCambio::create([
                'mecip_caso_id'   => $caso->id,
                'user_id'         => $userId,
                'estado_anterior' => 'borrador',
                'estado_nuevo'    => $caso->estado_flujo,
                'tipo_cambio'     => 'IMPORTACION_JSON',
                'resumen_cambio'  => 'Importación estructurada JSON/Bookmarklet de caso MECIP IPS.',
            ]);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Caso MECIP importado con éxito.',
                    'redirect' => route('admin.mecip.control.show', $caso->id),
                ]);
            }

            return redirect()->route('admin.mecip.control.show', $caso->id)
                ->with('success', "Caso MECIP Nº {$caso->numero_caso} replicado con éxito.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en importación JSON MECIP: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return redirect()->route('admin.mecip.control.index')->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la ficha completa con Diagrama de Nodos y Debate con Líder MECIP
     */
    public function show($id)
    {
        $caso = MecipCaso::with([
            'liderMecip',
            'creator',
            'insumos',
            'productos',
            'actividades.tareas',
            'actividades.comentarios.user',
            'comentarios.user',
            'cambios.user'
        ])->findOrFail($id);

        $user = Auth::user();
        $isLider = $user ? $user->hasRole('Líder MECIP') : false;
        $isAdmin = $user ? ($user->hasRole('Administrador') || $user->hasRole('Super Admin')) : true;

        $lideresMecip = User::whereHas('roles', function($q) {
            $q->where('name', 'Líder MECIP');
        })->get();

        if ($lideresMecip->isEmpty()) {
            $lideresMecip = User::limit(50)->get();
        }

        return view('admin.mecip.control.show', compact(
            'caso',
            'lideresMecip',
            'isLider',
            'isAdmin'
        ));
    }

    /**
     * Remite el expediente al Líder MECIP y notifica vía Redis
     */
    public function remitirALider(Request $request, $id)
    {
        $request->validate([
            'lider_mecip_id' => 'required|exists:users,id',
            'observacion'    => 'nullable|string',
        ]);

        $caso = MecipCaso::findOrFail($id);
        $estadoAnterior = $caso->estado_flujo;

        $caso->lider_mecip_id = $request->lider_mecip_id;
        $caso->estado_flujo   = 'remitido_lider';
        $caso->save();

        // Auditoría
        MecipCasoCambio::create([
            'mecip_caso_id'   => $caso->id,
            'user_id'         => Auth::id() ?: 1,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => 'remitido_lider',
            'observacion'     => $request->observacion ?: 'Expediente remitido a Líder MECIP para revisión y dictamen.',
        ]);

        // Notificación por Redis
        event(new MecipNotificacionEvent(
            $caso->id,
            $caso->numero_caso,
            $caso->codigo_subproceso,
            'Se te ha remitido el caso MECIP Nº ' . $caso->numero_caso . ' para revisión.',
            'remitido',
            $caso->lider_mecip_id
        ));

        return redirect()->back()->with('success', 'Expediente remitido a Líder MECIP exitosamente.');
    }

    /**
     * El Líder MECIP resuelve un elemento/caso ingresando la justificación del camino tomado
     */
    public function resolverElemento(Request $request, $id)
    {
        $request->validate([
            'comentario'           => 'required|string',
            'justificacion_camino' => 'required|string',
            'actividad_id'         => 'nullable|exists:mecip_caso_actividades,id',
        ]);

        $caso = MecipCaso::findOrFail($id);
        $estadoAnterior = $caso->estado_flujo;

        // Registrar Comentario de Resolución y Justificación
        MecipCasoComentario::create([
            'mecip_caso_id'        => $caso->id,
            'actividad_id'         => $request->actividad_id,
            'user_id'              => Auth::id() ?: 1,
            'rol_usuario'          => 'Líder MECIP',
            'comentario'           => trim($request->comentario),
            'justificacion_camino' => trim($request->justificacion_camino),
            'es_resolucion'        => true,
        ]);

        // Actualizar estado del caso
        $caso->estado_flujo = 'resuelto_lider';
        $caso->save();

        // Auditoría
        MecipCasoCambio::create([
            'mecip_caso_id'   => $caso->id,
            'user_id'         => Auth::id() ?: 1,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => 'resuelto_lider',
            'observacion'     => 'Resolución emitida por el Líder MECIP con justificación de decisión.',
        ]);

        // Notificar al Administrador creador vía Redis
        if ($caso->created_by) {
            event(new MecipNotificacionEvent(
                $caso->id,
                $caso->numero_caso,
                $caso->codigo_subproceso,
                'El Líder MECIP ha resuelto y justificado el caso Nº ' . $caso->numero_caso,
                'resuelto',
                $caso->created_by
            ));
        }

        return redirect()->back()->with('success', 'Resolución y justificación registradas con éxito. Expediente enviado a Administrador.');
    }

    /**
     * El Administrador aprueba las justificaciones y cierra el análisis
     */
    public function cerrarAnalisis(Request $request, $id)
    {
        $request->validate([
            'dictamen_final' => 'required|string',
        ]);

        $caso = MecipCaso::findOrFail($id);
        $estadoAnterior = $caso->estado_flujo;

        $caso->dictamen_final = trim($request->dictamen_final);
        $caso->estado_flujo   = 'cerrado_admin';
        $caso->save();

        // Auditoría
        MecipCasoCambio::create([
            'mecip_caso_id'   => $caso->id,
            'user_id'         => Auth::id(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo'    => 'cerrado_admin',
            'observacion'     => 'Dictamen final aprobado y análisis cerrado por Administrador: ' . $caso->dictamen_final,
        ]);

        // Evento Redis
        event(new MecipNotificacionEvent(
            $caso->id,
            $caso->numero_caso,
            $caso->codigo_subproceso,
            'El caso MECIP Nº ' . $caso->numero_caso . ' ha sido APROBADO Y CERRADO.',
            'cerrado',
            $caso->lider_mecip_id
        ));

        return redirect()->back()->with('success', 'El Análisis del caso MECIP ha sido aprobado y cerrado oficialmente.');
    }

    /**
     * Agrega una actividad manual al proceso
     */
    public function agregarActividad(Request $request, $id)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'responsable' => 'nullable|string|max:255',
            'objetivo'    => 'nullable|string',
        ]);

        $caso = MecipCaso::findOrFail($id);
        $ultimoOrden = $caso->actividades()->max('orden') ?? 0;

        MecipCasoActividad::create([
            'mecip_caso_id'    => $caso->id,
            'codigo_actividad' => 'ACT_' . str_pad($ultimoOrden + 1, 2, '0', STR_PAD_LEFT),
            'nombre'           => trim($request->nombre),
            'responsable'      => trim($request->responsable),
            'objetivo'         => trim($request->objetivo),
            'orden'            => $ultimoOrden + 1,
        ]);

        return redirect()->back()->with('success', 'Actividad agregada al proceso.');
    }

    /**
     * Agrega una tarea con tiempo estimado a una actividad
     */
    public function agregarTarea(Request $request, $actividadId)
    {
        $request->validate([
            'descripcion'             => 'required|string',
            'tiempo_estimado_minutos' => 'required|integer|min:1',
        ]);

        $actividad = MecipCasoActividad::findOrFail($actividadId);
        $ultimoOrden = $actividad->tareas()->max('orden') ?? 0;

        MecipCasoTarea::create([
            'actividad_id'            => $actividad->id,
            'descripcion'             => trim($request->descripcion),
            'tiempo_estimado_minutos' => $request->tiempo_estimado_minutos,
            'orden'                   => $ultimoOrden + 1,
        ]);

        return redirect()->back()->with('success', 'Tarea agregada a la actividad.');
    }

    /**
     * Sincroniza un caso llamando al cliente autenticado de scraping BPM
     */
    public function syncFromBpm(Request $request)
    {
        $request->validate([
            'process_id'  => 'required|string',
            'numero_caso' => 'nullable|string',
            'url'         => 'nullable|string',
            'user'        => 'nullable|string',
            'password'    => 'nullable|string',
        ]);

        try {
            $scraper = new \App\Services\MecipBpmScraperService(
                $request->get('url'),
                $request->get('user'),
                $request->get('password')
            );

            $caso = $scraper->scrapeProcess($request->process_id, $request->numero_caso);

            if ($caso) {
                return response()->json([
                    'success'  => true,
                    'message'  => "Caso {$caso->numero_caso} sincronizado exitosamente desde BPM IPS.",
                    'caso'     => $caso,
                    'redirect' => route('admin.mecip.control.show', $caso->id),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo completar la extracción del proceso BPM remoto.',
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error durante el scraping BPM: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Parsea un fragmento HTML pegado o subido directamente por el usuario
     */
    public function parseHtmlPayload(Request $request)
    {
        $request->validate([
            'html_content' => 'required|string',
            'numero_caso'  => 'required|string',
        ]);

        try {
            $scraper = new \App\Services\MecipBpmScraperService();
            $caso = $scraper->parseAndSyncHtml($request->html_content, $request->numero_caso);

            return response()->json([
                'success'  => true,
                'message'  => "Servicios, Productos y Tareas parseadas y guardadas para {$caso->numero_caso}.",
                'caso'     => $caso,
                'redirect' => route('admin.mecip.control.show', $caso->id),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el HTML pegado: ' . $e->getMessage(),
            ], 500);
        }
    }
}
