<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\AreaGestion;
use App\Models\RiissEspecialidad;
use App\Models\RiissMedicamento;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\RiissSesionFarmaceutica;
use App\Models\Riiss\RiissValidacionFarmaceuticaEspecialidad;
use App\Models\Riiss\RiissValidacionFarmaceuticaMedicamento;
use App\Models\Riiss\SesionValidador;
use App\Models\Riiss\ValidacionEspecialidadRegistro;
use App\Models\Riiss\ValidacionEstablecimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ValidacionEspecialidadesController extends Controller
{
    /**
     * Bandeja Administrativa de Validaciones de Especialidades (Áreas de Gestión Territoriales)
     */
    public function index(Request $request)
    {
        $querySesiones = SesionValidador::with('creadoPor')->withCount('registros')->latest();

        if ($request->filled('buscar')) {
            $b = trim($request->buscar);
            $querySesiones->where(function ($q) use ($b) {
                $q->where('analista_nombre', 'ilike', "%{$b}%")
                  ->orWhere('codigo_acceso', 'ilike', "%{$b}%")
                  ->orWhere('analista_cargo', 'ilike', "%{$b}%");
            });
        }

        if ($request->filled('area')) {
            $querySesiones->where('area_gestion', $request->area);
        }

        $sesiones = $querySesiones->get();

        // ── Sincronización Automática con Bioestadística (Áreas de Gestión) ──
        try {
            $bioEsts = \App\Models\Bioestadistica\Establecimiento::with('areaGestion')
                ->whereNotNull('area_gestion_id')
                ->get(['codigo', 'area_gestion_id']);

            foreach ($bioEsts as $bioEst) {
                if ($bioEst->codigo && $bioEst->areaGestion) {
                    Establecimiento::where('id_establecimiento', $bioEst->codigo)
                        ->where(function ($q) use ($bioEst) {
                            $q->whereNull('area_gestion')
                              ->orWhere('area_gestion', '!=', $bioEst->areaGestion->nombre);
                        })
                        ->update(['area_gestion' => $bioEst->areaGestion->nombre]);
                }
            }
        } catch (\Throwable $th) {
            // Ignorar si no existe tabla en algún entorno
        }

        // ── Catálogo de Áreas de Gestión de Bioestadística + Establecimientos ──
        $areasGestionBio = AreaGestion::where('activo', true)->orderBy('nombre')->get();
        $nombresAreasBio = $areasGestionBio->pluck('nombre')->toArray();
        $areasDb = Establecimiento::whereNotNull('area_gestion')->distinct()->pluck('area_gestion')->toArray();
        $todasAreasGestion = collect(array_unique(array_merge($nombresAreasBio, $areasDb)))->filter()->sort()->values();

        // Conteo dinámico por Área de Gestión
        $rawConteo = Establecimiento::select('area_gestion', DB::raw('count(*) as total'))
            ->groupBy('area_gestion')
            ->pluck('total', 'area_gestion')
            ->toArray();

        $areasConteo = [];
        foreach ($todasAreasGestion as $ag) {
            $areasConteo[$ag] = $rawConteo[$ag] ?? 0;
            if ($areasConteo[$ag] === 0) {
                foreach ($rawConteo as $rKey => $rVal) {
                    if (strcasecmp(trim($rKey), trim($ag)) === 0) {
                        $areasConteo[$ag] = $rVal;
                        break;
                    }
                }
            }
        }

        // Departamentos agrupados por Área de Gestión
        $deptosPorArea = Establecimiento::whereNotNull('area_gestion')
            ->whereNotNull('departamento')
            ->select('area_gestion', 'departamento')
            ->distinct()
            ->get()
            ->groupBy('area_gestion')
            ->map(function ($items) {
                return $items->pluck('departamento')->sort()->values();
            });

        // Departamentos específicos para compatibilidad
        $deptosInterior = $deptosPorArea->get('AREA INTERIOR', collect())->values();
        $deptosCentral  = $deptosPorArea->get('AREA CENTRAL', collect())->values();

        // Catálogos globales para filtros del modal
        $todosDepartamentos = Establecimiento::whereNotNull('departamento')
            ->distinct()->pluck('departamento')->sort()->values();
        $todasTipologias = Establecimiento::whereNotNull('tipologia_clasificacion')
            ->distinct()->pluck('tipologia_clasificacion')->sort()->values();

        $totalEstablecimientos = Establecimiento::count();
        $totalInterior = $areasConteo['AREA INTERIOR'] ?? 0;
        $totalCentral  = $areasConteo['AREA CENTRAL'] ?? 0;

        $totalRegistrosValidados = ValidacionEspecialidadRegistro::where('estado', 'activa')->count();
        $totalRegistrosInactivos = ValidacionEspecialidadRegistro::where('estado', 'inactiva')->count();
        $totalConRevision = ValidacionEspecialidadRegistro::distinct('establecimiento_id')->count('establecimiento_id');

        // Nómina de establecimientos para el gestor de clasificación
        $todosEstablecimientos = Establecimiento::select('id_establecimiento', 'nombre_oficial', 'departamento', 'tipologia_clasificacion', 'area_gestion')
            ->orderBy('departamento')->orderBy('nombre_oficial')->get();

        // Especialidades con conteo de medicamentos Vademécum e Históricos
        $especialidades = RiissEspecialidad::withCount([
            'medicamentosVademecum as total_vademecum',
            'medicamentosHistoricos as total_historicos',
            'establecimientos as total_establecimientos'
        ])->orderBy('nombre')->get();

        // Catálogo de Medicamentos Oficiales del Vademécum IPS
        $medicamentosVademecum = RiissMedicamento::where('es_vademecum', true)
            ->withCount('especialidadesVademecum as total_especialidades')
            ->orderBy('nombre')
            ->get();

        $totalEspecialidades = $especialidades->count();
        $totalMedicamentosVademecum = $medicamentosVademecum->count();
        $totalVinculosVademecum = DB::table('riiss_especialidad_vademecum')->count();

        // ── Datos de la Unidad de Regulación Farmacéutica ──
        $sesionesFarmaceuticas = RiissSesionFarmaceutica::with('creadoPor')
            ->withCount('validacionesEspecialidades')
            ->orderBy('id', 'desc')
            ->get();

        $totalEspecialidadesFarmValidadas = RiissValidacionFarmaceuticaEspecialidad::where('estado', 'validada')->count();
        $totalMedicamentosFarmValidados = RiissValidacionFarmaceuticaMedicamento::where('estado_validacion', 'validado')->count();
        $totalMedicamentosFarmInvalidados = RiissValidacionFarmaceuticaMedicamento::where('estado_validacion', 'invalidado')->count();

        return view('admin.riiss.especialidades_validacion.index', compact(
            'sesiones',
            'sesionesFarmaceuticas',
            'todasAreasGestion',
            'areasGestionBio',
            'areasConteo',
            'deptosPorArea',
            'todosDepartamentos',
            'todasTipologias',
            'deptosInterior',
            'deptosCentral',
            'totalEstablecimientos',
            'totalInterior',
            'totalCentral',
            'totalRegistrosValidados',
            'totalRegistrosInactivos',
            'totalConRevision',
            'todosEstablecimientos',
            'especialidades',
            'medicamentosVademecum',
            'totalEspecialidades',
            'totalMedicamentosVademecum',
            'totalVinculosVademecum',
            'totalEspecialidadesFarmValidadas',
            'totalMedicamentosFarmValidados',
            'totalMedicamentosFarmInvalidados'
        ));
    }

    /**
     * Genera un nuevo enlace con código único para un Validador / Analista
     */
    public function generarEnlace(Request $request)
    {
        $request->validate([
            'analista_nombre'     => 'required|string|max:200',
            'analista_cargo'      => 'nullable|string|max:150',
            'analista_documento'  => 'nullable|string|max:50',
            'analista_telefono'   => 'nullable|string|max:50',
            'analista_email'      => 'nullable|email|max:150',
            'area_gestion'        => 'required|string|max:150',
            'departamento_filtro' => 'nullable|string|max:100',
            'notas'               => 'nullable|string|max:65000',
        ]);

        $areaGestion = $request->area_gestion ?: 'AREA INTERIOR';
        $deptoFiltro = ($request->departamento_filtro && !str_starts_with($request->departamento_filtro, 'TODOS') && $request->departamento_filtro !== '') 
            ? $request->departamento_filtro 
            : null;

        $sesion = SesionValidador::create([
            'token'               => Str::random(40),
            'codigo_acceso'       => 'VAL-' . strtoupper(Str::random(6)),
            'analista_nombre'     => trim($request->analista_nombre),
            'analista_cargo'      => $request->analista_cargo ? trim($request->analista_cargo) : "Analista Técnico {$areaGestion}",
            'analista_documento'  => $request->analista_documento ? trim($request->analista_documento) : null,
            'analista_telefono'   => $request->analista_telefono ? trim($request->analista_telefono) : null,
            'analista_email'      => $request->analista_email ? trim($request->analista_email) : null,
            'area_gestion'        => $areaGestion,
            'departamento_filtro' => $deptoFiltro,
            'notas'               => $request->notas ? trim($request->notas) : null,
            'estado'              => 'activo',
            'created_by_user_id'  => Auth::id(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Enlace generado con éxito para {$sesion->analista_nombre}.",
                'data'    => [
                    'id'            => $sesion->id,
                    'token'         => $sesion->token,
                    'codigo_acceso' => $sesion->codigo_acceso,
                    'url_portal'    => url('/riiss/portal-validador/' . $sesion->token),
                    'analista'      => $sesion->analista_nombre,
                    'area_gestion'  => $sesion->area_gestion,
                    'departamento'  => $sesion->departamento_filtro ?? 'Todos',
                ]
            ]);
        }

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Enlace generado con éxito para {$sesion->analista_nombre}. Código de Acceso: {$sesion->codigo_acceso}")
            ->with('nuevo_acceso', [
                'id'            => $sesion->id,
                'token'         => $sesion->token,
                'codigo_acceso' => $sesion->codigo_acceso,
                'url_portal'    => url('/riiss/portal-validador/' . $sesion->token),
                'analista'      => $sesion->analista_nombre,
                'cargo'         => $sesion->analista_cargo,
                'telefono'      => $sesion->analista_telefono,
                'area_gestion'  => $sesion->area_gestion,
                'departamento'  => $sesion->departamento_filtro ?? 'Todos los Departamentos',
            ]);
    }

    /**
     * Sincronizar o Cambiar manualmente el Área de Gestión de un Establecimiento (AJAX)
     */
    public function actualizarAreaGestion(Request $request)
    {
        $request->validate([
            'establecimiento_id' => 'required|string|exists:establecimientos,id_establecimiento',
            'area_gestion'       => 'required|string|max:150',
        ]);

        $est = Establecimiento::where('id_establecimiento', $request->establecimiento_id)->firstOrFail();
        $est->area_gestion = trim($request->area_gestion);
        $est->save();

        // Sincronizar de vuelta con el módulo de Bioestadística si el establecimiento existe
        try {
            $bioArea = AreaGestion::where('nombre', 'ilike', trim($request->area_gestion))->first();
            if ($bioArea) {
                \App\Models\Bioestadistica\Establecimiento::where('codigo', $est->id_establecimiento)
                    ->update(['area_gestion_id' => $bioArea->id]);
            }
        } catch (\Throwable $th) {
            // Ignorar si no existe enlace
        }

        return response()->json([
            'success'      => true,
            'message'      => "Establecimiento {$est->nombre_oficial} actualizado a {$est->area_gestion}",
            'area_gestion' => $est->area_gestion,
        ]);
    }

    /**
     * Eliminar / Desactivar enlace de validador y sus registros asociados
     */
    public function eliminarEnlace($id)
    {
        $sesion = SesionValidador::findOrFail($id);
        ValidacionEspecialidadRegistro::where('sesion_validador_id', $sesion->id)->delete();
        ValidacionEstablecimiento::where('sesion_validador_id', $sesion->id)->delete();
        $sesion->delete();

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Enlace y sesión de validador eliminados correctamente.");
    }

    /**
     * Actualiza los datos de una sesión de validador de especialidades médicas
     */
    public function actualizarEnlace(Request $request, $id)
    {
        $request->validate([
            'analista_nombre'     => 'required|string|max:200',
            'analista_cargo'      => 'nullable|string|max:150',
            'analista_documento'  => 'nullable|string|max:50',
            'analista_telefono'   => 'nullable|string|max:50',
            'analista_email'      => 'nullable|email|max:150',
            'area_gestion'        => 'required|string|max:150',
            'departamento_filtro' => 'nullable|string|max:100',
            'estado'              => 'required|string|in:activo,finalizado,inactivo',
            'codigo_acceso'       => 'nullable|string|max:30',
            'notas'               => 'nullable|string|max:65000',
        ]);

        $sesion = SesionValidador::findOrFail($id);

        $deptoFiltro = ($request->departamento_filtro && !str_starts_with($request->departamento_filtro, 'TODOS') && $request->departamento_filtro !== '')
            ? trim($request->departamento_filtro)
            : null;

        $sesion->analista_nombre     = trim($request->analista_nombre);
        $sesion->analista_cargo      = $request->analista_cargo ? trim($request->analista_cargo) : $sesion->analista_cargo;
        $sesion->analista_documento  = $request->analista_documento ? trim($request->analista_documento) : null;
        $sesion->analista_telefono   = $request->analista_telefono ? trim($request->analista_telefono) : null;
        $sesion->analista_email      = $request->analista_email ? trim($request->analista_email) : null;
        $sesion->area_gestion        = trim($request->area_gestion);
        $sesion->departamento_filtro = $deptoFiltro;
        $sesion->estado              = $request->estado;
        $sesion->notas               = $request->notas ? trim($request->notas) : null;

        if ($request->filled('codigo_acceso')) {
            $sesion->codigo_acceso = strtoupper(trim($request->codigo_acceso));
        }

        $sesion->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Sesión de validación de '{$sesion->analista_nombre}' actualizada correctamente.",
                'data'    => $sesion,
            ]);
        }

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Sesión de validación de '{$sesion->analista_nombre}' actualizada exitosamente.");
    }

    /**
     * Reiniciar a 0 las validaciones y firmas asociadas a un Enlace específico
     */
    public function reiniciarEnlace($id)
    {
        $sesion = SesionValidador::findOrFail($id);

        // 1. Eliminar registros de especialidades validadas por este enlace
        ValidacionEspecialidadRegistro::where('sesion_validador_id', $sesion->id)->delete();

        // 2. Eliminar validaciones/firmas por establecimiento asociadas a esta sesión
        ValidacionEstablecimiento::where('sesion_validador_id', $sesion->id)->delete();

        // 3. Restablecer el estado del enlace/sesión a activo y sin firma
        $sesion->update([
            'estado'        => 'activo',
            'firma_digital' => null,
            'firmado_at'    => null,
        ]);

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Se han reiniciado a 0 todas las validaciones y firmas del enlace de {$sesion->analista_nombre} ({$sesion->codigo_acceso}).");
    }

    /**
     * Reiniciar todos los registros de validación de prueba para dejar todo en cero
     */
    public function reiniciarRegistros(Request $request)
    {
        // 1. Eliminar todos los registros de validación de especialidades
        ValidacionEspecialidadRegistro::query()->delete();

        // 2. Eliminar todas las firmas individuales de establecimientos
        ValidacionEstablecimiento::query()->delete();

        // 3. Restablecer las sesiones a estado activo y sin firma
        SesionValidador::query()->update([
            'estado'        => 'activo',
            'firma_digital' => null,
            'firmado_at'    => null,
        ]);

        // Si se solicitó eliminar también las sesiones de validador de prueba
        if ($request->boolean('incluir_sesiones')) {
            SesionValidador::query()->delete();
            $mensaje = "Se han reiniciado a 0 todos los registros de validación y se han limpiado los enlaces de prueba.";
        } else {
            $mensaje = "Se han reiniciado a 0 todos los registros de especialidades y firmas de validación.";
        }

        return redirect()->route('riiss.validaciones.index')
            ->with('success', $mensaje);
    }

    /**
     * Portal Multi-Establecimiento para el Validador (Acceso por Token Único)
     */
    public function portalValidador($token)
    {
        $sesion = SesionValidador::where('token', $token)->first();

        if (!$sesion) {
            // Permitir también buscar por codigo_acceso
            $sesion = SesionValidador::where('codigo_acceso', $token)->first();
        }

        if (!$sesion) {
            abort(404, 'Enlace de validación inválido o expirado.');
        }

        // Verificar si la sesión ya autenticó con el Código de Acceso
        $sessionKey = 'riiss_validador_auth_' . $sesion->token;
        if (!session()->get($sessionKey, false) && !session()->get('riiss_validador_auth_' . $token, false)) {
            $ctx = $this->getContextoInstitucional($sesion);
            return view('admin.riiss.especialidades_validacion.desafio_codigo', array_merge(
                compact('sesion', 'token'),
                $ctx
            ));
        }

        // Consultar Establecimientos filtrados por el Área de Gestión del Token
        $estQuery = Establecimiento::query()->orderBy('departamento')->orderBy('nombre_oficial');

        if ($sesion->area_gestion) {
            $areaNormalizada = trim($sesion->area_gestion);
            $estQuery->where(function($q) use ($areaNormalizada) {
                $q->where('area_gestion', $areaNormalizada)
                  ->orWhere('area_gestion', 'ilike', '%' . str_replace(['Ú', 'U', 'ú', 'u'], '_', $areaNormalizada) . '%');
                
                // Si contiene Quirúrgicas o Quirurjicas
                if (stripos($areaNormalizada, 'QUIR') !== false) {
                    $q->orWhere('area_gestion', 'ilike', '%QUIR%');
                }
                // Si es Área Interior
                if (stripos($areaNormalizada, 'INTERIOR') !== false) {
                    $q->orWhere('area_gestion', 'ilike', '%INTERIOR%');
                }
                // Si es Gestión Médica
                if (stripos($areaNormalizada, 'GESTION') !== false || stripos($areaNormalizada, 'GESTIÓN') !== false) {
                    $q->orWhere('area_gestion', 'ilike', '%GESTI%');
                }
            });
        } else {
            $estQuery->where('area_gestion', 'AREA INTERIOR');
        }

        if ($sesion->departamento_filtro && !str_starts_with(strtoupper($sesion->departamento_filtro), 'TODOS')) {
            $estQuery->where('departamento', $sesion->departamento_filtro);
        }

        $establecimientos = $estQuery->get();

        // Departamentos disponibles para el filtro en la UI
        $departamentos = $establecimientos->pluck('departamento')->unique()->filter()->values();

        // Conteo total de especialidades registradas en Base de Datos por cada establecimiento
        $conteosEspecialidadesDb = DB::table('riiss_establecimiento_especialidades')
            ->select('establecimiento_id', DB::raw("COUNT(*) as total_db"))
            ->groupBy('establecimiento_id')
            ->get()
            ->keyBy('establecimiento_id');

        // Obtener resumen de conteos de especialidades validadas por establecimiento
        $resumenValidaciones = ValidacionEspecialidadRegistro::select(
            'establecimiento_id',
            DB::raw("COUNT(*) as total_registros"),
            DB::raw("COUNT(CASE WHEN estado = 'activa' THEN 1 END) as total_activas"),
            DB::raw("COUNT(CASE WHEN estado = 'inactiva' THEN 1 END) as total_inactivas")
        )->groupBy('establecimiento_id')->get()->keyBy('establecimiento_id');

        // Obtener registros de validación individual y firmas por establecimiento (incluye cualquier firma registrada)
        $validacionesEstablecimientos = ValidacionEstablecimiento::whereIn('establecimiento_id', $establecimientos->pluck('id_establecimiento'))
            ->get()
            ->keyBy('establecimiento_id');

        // Contexto institucional (Logo oficial del plan / entidad, membrete y datos del sistema)
        $ctx = $this->getContextoInstitucional($sesion);

        return view('admin.riiss.especialidades_validacion.portal_validador', array_merge(
            compact('sesion', 'establecimientos', 'departamentos', 'resumenValidaciones', 'conteosEspecialidadesDb', 'validacionesEstablecimientos'),
            $ctx
        ));
    }

    /**
     * POST /riiss/portal-validador/{token}/verificar-codigo
     * Autenticación del Validador mediante Código de Acceso (PIN / Código alfanumérico)
     */
    public function verificarCodigo(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->first();

        if (!$sesion) {
            $sesion = SesionValidador::where('codigo_acceso', $token)->first();
        }

        if (!$sesion) {
            return response()->json([
                'ok'      => false,
                'message' => 'Este enlace de validación no existe o ha expirado.'
            ], 404);
        }

        $request->validate([
            'codigo' => 'required|string',
        ]);

        $inputCodigo  = strtoupper(trim($request->codigo));
        $sesionCodigo = strtoupper(trim($sesion->codigo_acceso));

        // Normalización flexible para comparación (permitir con o sin 'VAL-', guiones o espacios)
        $normInput  = str_replace(['VAL-', 'VAL', '-', ' '], '', $inputCodigo);
        $normSesion = str_replace(['VAL-', 'VAL', '-', ' '], '', $sesionCodigo);

        if ($inputCodigo !== $sesionCodigo && $normInput !== $normSesion) {
            return response()->json([
                'ok'      => false,
                'message' => 'El Código de Acceso ingresado es incorrecto. Por favor, verifique el código recibido por WhatsApp o correo.'
            ], 422);
        }

        // Marcar sesión como autenticada
        session([
            'riiss_validador_auth_' . $sesion->token => true,
            'riiss_validador_auth_' . $token         => true,
        ]);

        return response()->json([
            'ok'       => true,
            'redirect' => route('riiss.portal-validador.show', ['token' => $sesion->token]),
        ]);
    }

    /**
     * POST /riiss/portal-validador/{token}/salir
     * Cierra la sesión activa del validador.
     */
    public function salirPortal($token)
    {
        $sesion = SesionValidador::where('token', $token)->first();
        if ($sesion) {
            session()->forget('riiss_validador_auth_' . $sesion->token);
        }
        session()->forget('riiss_validador_auth_' . $token);

        return redirect()->route('riiss.portal-validador.show', ['token' => $token]);
    }

    /**
     * Obtener lista de especialidades de un establecimiento (API para la UI del Portal)
     */
    public function getEstablecimientoEspecialidades($token, $establecimiento_id)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();
        $est = Establecimiento::where('id_establecimiento', $establecimiento_id)->firstOrFail();

        // 1. Especialidades asignadas obtenidas de bioestadistica.especialidades_medicas
        $especialidadesAsignadas = DB::table('riiss_establecimiento_especialidades')
            ->join('bioestadistica.especialidades_medicas', 'riiss_establecimiento_especialidades.especialidad_id', '=', 'bioestadistica.especialidades_medicas.id')
            ->where('riiss_establecimiento_especialidades.establecimiento_id', $est->id_establecimiento)
            ->select(
                'bioestadistica.especialidades_medicas.id as especialidad_id',
                'bioestadistica.especialidades_medicas.nombre as especialidad_nombre',
                'bioestadistica.especialidades_medicas.codigo as especialidad_codigo'
            )
            ->orderBy('bioestadistica.especialidades_medicas.nombre')
            ->get();

        // 2. Registros de validación existentes
        $validacionesExistentes = ValidacionEspecialidadRegistro::where('establecimiento_id', $est->id_establecimiento)
            ->get()
            ->keyBy('especialidad_id');

        $lista = [];

        // Combinar especialidades del centro con sus estados de validación
        foreach ($especialidadesAsignadas as $esp) {
            $reg = $validacionesExistentes->get($esp->especialidad_id);
            $lista[] = [
                'especialidad_id'     => $esp->especialidad_id,
                'nombre'              => $esp->especialidad_nombre,
                'codigo'              => $esp->especialidad_codigo,
                'estado'              => $reg ? $reg->estado : 'pendiente', // 'pendiente', 'activa', 'inactiva'
                'justificacion'       => $reg ? ($reg->justificacion ?? '') : '',
                'es_agregada'         => $reg ? (bool)$reg->es_agregada : false,
                'validado_por'        => $reg ? $reg->validado_por : null,
                'validado_at'         => $reg && $reg->validado_at ? $reg->validado_at->format('d/m/Y H:i') : null,
            ];
        }

        // Agregar especialidades agregadas en terreno
        foreach ($validacionesExistentes as $valReg) {
            if ($valReg->es_agregada && !collect($lista)->contains('especialidad_id', $valReg->especialidad_id)) {
                $espModel = RiissEspecialidad::find($valReg->especialidad_id);
                if ($espModel) {
                    $lista[] = [
                        'especialidad_id'     => $espModel->id,
                        'nombre'              => $espModel->nombre,
                        'codigo'              => $espModel->codigo,
                        'estado'              => $valReg->estado,
                        'justificacion'       => $valReg->justificacion ?? '',
                        'es_agregada'         => true,
                        'validado_por'        => $valReg->validado_por,
                        'validado_at'         => $valReg->validado_at ? $valReg->validado_at->format('d/m/Y H:i') : null,
                    ];
                }
            }
        }

        // Registro de validación y firma del establecimiento
        $valEst = ValidacionEstablecimiento::where('establecimiento_id', $est->id_establecimiento)
            ->latest('firmado_at')
            ->first();

        return response()->json([
            'success'          => true,
            'establecimiento'  => [
                'id'           => $est->id_establecimiento,
                'nombre'       => $est->nombre_oficial,
                'departamento' => $est->departamento,
                'tipologia'    => $est->tipologia_clasificacion,
                'complejidad'  => $est->complejidad_label ?? $est->complejidad,
                'area_gestion' => $est->area_gestion,
            ],
            'especialidades'   => $lista,
            'validacion'       => $valEst ? [
                'estado'          => $valEst->estado,
                'validador_nombre'=> $valEst->validador_nombre,
                'firmado_at'      => $valEst->firmado_at ? $valEst->firmado_at->format('d/m/Y H:i') : null,
                'notas'           => $valEst->notas,
                'firma_digital'   => $valEst->firma_digital,
                'total_db'        => $valEst->total_db,
                'total_activas'   => $valEst->total_activas,
                'total_inactivas' => $valEst->total_inactivas,
            ] : null,
        ]);
    }

    /**
     * Guardar/Actualizar estado de una especialidad (Validar Activa / Inactiva / Pendiente y justificación opcional)
     */
    public function actualizarEspecialidad(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'establecimiento_id' => 'required|string|exists:establecimientos,id_establecimiento',
            'especialidad_id'    => 'required|integer',
            'estado'             => 'required|in:activa,inactiva,pendiente',
            'justificacion'      => 'nullable|string|max:1000', // NO obligatorio
        ]);

        if ($request->estado === 'pendiente') {
            ValidacionEspecialidadRegistro::where('establecimiento_id', $request->establecimiento_id)
                ->where('especialidad_id', $request->especialidad_id)
                ->delete();
            $registro = null;
        } else {
            $registro = ValidacionEspecialidadRegistro::updateOrCreate(
                [
                    'establecimiento_id' => $request->establecimiento_id,
                    'especialidad_id'    => $request->especialidad_id,
                ],
                [
                    'estado'              => $request->estado,
                    'justificacion'       => $request->justificacion ? trim($request->justificacion) : null,
                    'sesion_validador_id' => $sesion->id,
                    'validado_por'        => $sesion->analista_nombre,
                    'validado_at'         => now(),
                ]
            );
        }

        // Obtener conteos actualizados del establecimiento
        $conteoTotal = DB::table('riiss_establecimiento_especialidades')->where('establecimiento_id', $request->establecimiento_id)->count();
        $conteoActivas = ValidacionEspecialidadRegistro::where('establecimiento_id', $request->establecimiento_id)->where('estado', 'activa')->count();
        $conteoInactivas = ValidacionEspecialidadRegistro::where('establecimiento_id', $request->establecimiento_id)->where('estado', 'inactiva')->count();

        return response()->json([
            'success'         => true,
            'message'         => 'Guardado correctamente',
            'registro'        => $registro,
            'total_db'        => $conteoTotal,
            'total_activas'   => $conteoActivas,
            'total_inactivas' => $conteoInactivas,
        ]);
    }

    /**
     * Validar masivamente todas las especialidades pendientes de un establecimiento como activas
     */
    public function validarTodas(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'establecimiento_id' => 'required|string|exists:establecimientos,id_establecimiento',
        ]);

        $estId = $request->establecimiento_id;

        // Obtener todas las especialidades asignadas al establecimiento en la base de datos
        $especialidadesAsignadas = DB::table('riiss_establecimiento_especialidades')
            ->where('establecimiento_id', $estId)
            ->pluck('especialidad_id');

        $now = now();
        $analista = $sesion->analista_nombre;
        $sesionId = $sesion->id;

        foreach ($especialidadesAsignadas as $espId) {
            $existe = ValidacionEspecialidadRegistro::where('establecimiento_id', $estId)
                ->where('especialidad_id', $espId)
                ->first();

            if (!$existe) {
                ValidacionEspecialidadRegistro::create([
                    'establecimiento_id'  => $estId,
                    'especialidad_id'     => $espId,
                    'estado'              => 'activa',
                    'justificacion'       => null,
                    'es_agregada'         => false,
                    'sesion_validador_id' => $sesionId,
                    'validado_por'        => $analista,
                    'validado_at'         => $now,
                ]);
            }
        }

        // Conteos actualizados
        $conteoTotal = DB::table('riiss_establecimiento_especialidades')->where('establecimiento_id', $estId)->count();
        $conteoActivas = ValidacionEspecialidadRegistro::where('establecimiento_id', $estId)->where('estado', 'activa')->count();
        $conteoInactivas = ValidacionEspecialidadRegistro::where('establecimiento_id', $estId)->where('estado', 'inactiva')->count();

        return response()->json([
            'success'         => true,
            'message'         => 'Todas las especialidades asignadas han sido validadas como activas.',
            'total_db'        => $conteoTotal,
            'total_activas'   => $conteoActivas,
            'total_inactivas' => $conteoInactivas,
        ]);
    }

    /**
     * Buscar especialidades en el catálogo canónico de Bioestadística (para agregar faltantes)
     */
    public function buscarEnBioestadistica(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();
        $q = trim($request->get('q', ''));

        $query = RiissEspecialidad::query(); // Consulta bioestadistica.especialidades_medicas

        if (!empty($q)) {
            $query->where('nombre', 'ilike', "%{$q}%");
        }

        $items = $query->orderBy('nombre')->limit(30)->get(['id', 'nombre', 'codigo']);

        return response()->json([
            'success' => true,
            'items'   => $items,
        ]);
    }

    /**
     * Agregar una especialidad faltante detectada en terreno
     */
    public function agregarEspecialidadPortal(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'establecimiento_id' => 'required|string|exists:establecimientos,id_establecimiento',
            'especialidad_id'    => ['required', 'integer', Rule::exists(RiissEspecialidad::class, 'id')],
            'justificacion'      => 'nullable|string|max:1000',
        ]);

        $esp = RiissEspecialidad::findOrFail($request->especialidad_id);

        // 1. Asegurar en riiss_establecimiento_especialidades
        DB::table('riiss_establecimiento_especialidades')->updateOrInsert(
            [
                'establecimiento_id' => $request->establecimiento_id,
                'especialidad_id'    => $esp->id,
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Registrar en la validación
        $registro = ValidacionEspecialidadRegistro::updateOrCreate(
            [
                'establecimiento_id' => $request->establecimiento_id,
                'especialidad_id'    => $request->especialidad_id,
            ],
            [
                'estado'              => 'activa',
                'justificacion'       => $request->justificacion ? trim($request->justificacion) : 'Agregada durante el relevamiento',
                'es_agregada'         => true,
                'sesion_validador_id' => $sesion->id,
                'validado_por'        => $sesion->analista_nombre,
                'validado_at'         => now(),
            ]
        );

        $esp = RiissEspecialidad::find($request->especialidad_id);

        return response()->json([
            'success'      => true,
            'message'      => 'Especialidad agregada con éxito',
            'especialidad' => [
                'especialidad_id' => $esp->id,
                'nombre'          => $esp->nombre,
                'codigo'          => $esp->codigo,
                'estado'          => 'activa',
                'justificacion'   => $registro->justificacion,
                'es_agregada'     => true,
            ],
        ]);
    }

    /**
     * Finalizar y firmar sesión de relevamiento (Opcional)
     */
    public function finalizarSesion(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'firma_base64' => 'nullable|string',
            'notas'        => 'nullable|string|max:65000',
        ]);

        $sesion->update([
            'estado'        => 'finalizado',
            'firma_digital' => $request->firma_base64 ?: $sesion->firma_digital,
            'firmado_at'    => now(),
            'notas'         => $request->notas ? trim($request->notas) : $sesion->notas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sesión de relevamiento finalizada con éxito.',
        ]);
    }

    /**
     * Acta General / Consolidada de Validación (PDF)
     */
    public function actaValidadorPdf($token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $registros = ValidacionEspecialidadRegistro::with(['establecimiento', 'especialidad'])
            ->where('sesion_validador_id', $sesion->id)
            ->get();

        $totalValidadas = $registros->where('estado', 'activa')->count();
        $totalInactivadas = $registros->where('estado', 'inactiva')->count();
        $totalEstablecimientos = $registros->pluck('establecimiento_id')->unique()->count();

        $ctx = $this->getContextoInstitucional($sesion);
        $institucion = $ctx['institucion'];
        $dependencia = $ctx['dependencia'];
        $logoInstitucional = $this->prepareLogoForPdf($ctx['logoInstitucional']);

        $pdf = Pdf::loadView('admin.riiss.especialidades_validacion.acta_sesion_pdf', compact(
            'sesion',
            'registros',
            'totalValidadas',
            'totalInactivadas',
            'totalEstablecimientos',
            'institucion',
            'dependencia',
            'logoInstitucional'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream("Acta_Validacion_{$sesion->codigo_acceso}.pdf");
    }

    /**
     * Acta General / Consolidada de Validación (Imprimir en Navegador)
     */
    public function actaValidadorImprimir($token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $registros = ValidacionEspecialidadRegistro::with(['establecimiento', 'especialidad'])
            ->where('sesion_validador_id', $sesion->id)
            ->get();

        $totalValidadas = $registros->where('estado', 'activa')->count();
        $totalInactivadas = $registros->where('estado', 'inactiva')->count();
        $totalEstablecimientos = $registros->pluck('establecimiento_id')->unique()->count();

        $ctx = $this->getContextoInstitucional($sesion);
        $institucion = $ctx['institucion'];
        $dependencia = $ctx['dependencia'];
        $logoInstitucional = $ctx['logoInstitucional'];

        return view('admin.riiss.especialidades_validacion.acta_sesion_imprimir', compact(
            'sesion',
            'registros',
            'totalValidadas',
            'totalInactivadas',
            'totalEstablecimientos',
            'institucion',
            'dependencia',
            'logoInstitucional'
        ));
    }

    /**
     * Firmar y Finalizar la Validación de un Establecimiento Individual
     */
    public function firmarEstablecimiento(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'establecimiento_id' => 'required|string|exists:establecimientos,id_establecimiento',
            'firma_base64'       => 'nullable|string',
            'notas'              => 'nullable|string|max:65000',
        ]);

        $estId = $request->establecimiento_id;
        $est = Establecimiento::where('id_establecimiento', $estId)->firstOrFail();

        $totalDb = DB::table('riiss_establecimiento_especialidades')->where('establecimiento_id', $estId)->count();
        $totalActivas = ValidacionEspecialidadRegistro::where('establecimiento_id', $estId)->where('estado', 'activa')->count();
        $totalInactivas = ValidacionEspecialidadRegistro::where('establecimiento_id', $estId)->where('estado', 'inactiva')->count();
        $totalAgregadas = ValidacionEspecialidadRegistro::where('establecimiento_id', $estId)->where('es_agregada', true)->count();

        $valEst = ValidacionEstablecimiento::updateOrCreate(
            [
                'establecimiento_id' => $estId,
                'sesion_validador_id' => $sesion->id,
            ],
            [
                'validador_nombre'    => $sesion->analista_nombre,
                'validador_cargo'     => $sesion->analista_cargo,
                'validador_documento' => $sesion->analista_documento,
                'estado'              => 'validado',
                'total_db'            => $totalDb,
                'total_activas'       => $totalActivas,
                'total_inactivas'     => $totalInactivas,
                'total_agregadas'     => $totalAgregadas,
                'notas'               => $request->notas ? trim($request->notas) : null,
                'firma_digital'       => $request->firma_base64 ?: null,
                'firmado_at'          => now(),
            ]
        );

        return response()->json([
            'success'    => true,
            'message'    => "¡Establecimiento {$est->nombre_oficial} validado y firmado con éxito!",
            'validacion' => $valEst,
        ]);
    }

    /**
     * Reabrir o Rectificar la Validación de un Establecimiento
     */
    public function reabrirEstablecimiento(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'establecimiento_id' => 'required|string|exists:establecimientos,id_establecimiento',
        ]);

        $valEst = ValidacionEstablecimiento::where('establecimiento_id', $request->establecimiento_id)
            ->latest('firmado_at')
            ->first();

        if ($valEst) {
            $valEst->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Establecimiento reabierto para edición y rectificación.',
        ]);
    }

    /**
     * Acta Individual de Validación por Establecimiento (Impresión en Navegador)
     */
    public function actaEstablecimientoImprimir($token, $establecimiento_id)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();
        $est = Establecimiento::where('id_establecimiento', $establecimiento_id)->firstOrFail();

        $valEst = ValidacionEstablecimiento::where('establecimiento_id', $est->id_establecimiento)
            ->latest('firmado_at')
            ->first();

        $registros = ValidacionEspecialidadRegistro::with('especialidad')
            ->where('establecimiento_id', $est->id_establecimiento)
            ->get();

        $activas = $registros->where('estado', 'activa')->sortBy(fn($r) => $r->especialidad?->nombre ?? '');
        $inactivas = $registros->where('estado', 'inactiva')->sortBy(fn($r) => $r->especialidad?->nombre ?? '');
        $totalDb = DB::table('riiss_establecimiento_especialidades')->where('establecimiento_id', $est->id_establecimiento)->count();

        $ctx = $this->getContextoInstitucional($sesion);

        return view('admin.riiss.especialidades_validacion.acta_establecimiento_imprimir', array_merge(
            compact('sesion', 'est', 'valEst', 'registros', 'activas', 'inactivas', 'totalDb'),
            $ctx
        ));
    }

    /**
     * Acta Individual de Validación por Establecimiento (Descarga PDF)
     */
    public function actaEstablecimientoPdf($token, $establecimiento_id)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();
        $est = Establecimiento::where('id_establecimiento', $establecimiento_id)->firstOrFail();

        $valEst = ValidacionEstablecimiento::where('establecimiento_id', $est->id_establecimiento)
            ->latest('firmado_at')
            ->first();

        $registros = ValidacionEspecialidadRegistro::with('especialidad')
            ->where('establecimiento_id', $est->id_establecimiento)
            ->get();

        $activas = $registros->where('estado', 'activa')->sortBy(fn($r) => $r->especialidad?->nombre ?? '');
        $inactivas = $registros->where('estado', 'inactiva')->sortBy(fn($r) => $r->especialidad?->nombre ?? '');
        $totalDb = DB::table('riiss_establecimiento_especialidades')->where('establecimiento_id', $est->id_establecimiento)->count();

        $ctx = $this->getContextoInstitucional($sesion);
        $ctx['logoInstitucional'] = $this->prepareLogoForPdf($ctx['logoInstitucional']);

        $pdf = Pdf::loadView('admin.riiss.especialidades_validacion.acta_establecimiento_pdf', array_merge(
            compact('sesion', 'est', 'valEst', 'registros', 'activas', 'inactivas', 'totalDb'),
            $ctx
        ))->setPaper('a4', 'portrait');

        $slug = Str::slug($est->nombre_oficial);
        return $pdf->stream("Acta_Validacion_{$slug}_{$sesion->codigo_acceso}.pdf");
    }

    /**
     * Obtener el contexto institucional (Logo, institución, dependencia, datos de contacto del sistema)
     */
    private function getContextoInstitucional($sesion = null): array
    {
        $profile = \App\Admin\Planificacion\Pei\PeiProfile::find('ce99f883-fdd0-4723-8f75-cf689aa8f0fa')
            ?? \App\Admin\Planificacion\Pei\PeiProfile::where('level', 'master')->first()
            ?? \App\Admin\Planificacion\Pei\PeiProfile::first();

        $master = $profile && $profile->parent_id ? ($profile->getRoot() ?? $profile) : $profile;
        $params = [];
        if ($master) {
            $params = is_string($master->parameters) ? (json_decode($master->parameters, true) ?? []) : ($master->parameters ?? []);
        }

        $logoInstitucional = $params['logo_institucional'] ?? $params['acta_logo_url'] ?? ($master?->logo_institucional ?? null);
        $institucion       = $params['acta_institucion'] ?? 'INSTITUTO DE PREVISIÓN SOCIAL (IPS)';

        $area = $sesion ? $sesion->area_gestion : 'AREA INTERIOR';
        $areaUpper = strtoupper($area ?? '');
        if (str_contains($areaUpper, 'CENTRAL') && !str_contains($areaUpper, 'INTERIOR')) {
            $dependenciaDefault = 'DIRECCIÓN DE HOSPITALES DEL ÁREA CENTRAL';
        } elseif (str_contains($areaUpper, 'QUIRUR')) {
            $dependenciaDefault = 'HOSPITALES DE ESPECIALIDADES QUIRÚRGICAS';
        } elseif (str_contains($areaUpper, 'PREVENTIVA')) {
            $dependenciaDefault = 'DIRECCIÓN DE MEDICINA PREVENTIVA';
        } elseif (str_contains($areaUpper, 'GESTION') || str_contains($areaUpper, 'GESTIÓN')) {
            $dependenciaDefault = 'DIRECCIÓN DE GESTIÓN MÉDICA';
        } elseif (!empty($area)) {
            $dependenciaDefault = mb_strtoupper($area, 'UTF-8');
        } else {
            $dependenciaDefault = 'DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR';
        }
        $dependencia       = $params['acta_dependencia'] ?? $dependenciaDefault;

        $sysLogoRaw  = \App\Models\HomeConfiguration::getSetting('logo_url') ?? \App\Models\HomeConfiguration::getSetting('logo');
        $sysLogoUrl  = !empty($sysLogoRaw) ? ((str_starts_with($sysLogoRaw, 'http://') || str_starts_with($sysLogoRaw, 'https://')) ? $sysLogoRaw : url($sysLogoRaw)) : asset('material/img/new_logo.png');

        $sysSiteName = \App\Models\HomeConfiguration::getSetting('site_name', 'SIPLAN GO');
        $sysEmail    = \App\Models\HomeConfiguration::getSetting('contact_email', 'planificacion@ips.gov.py');
        $sysPhone    = \App\Models\HomeConfiguration::getSetting('contact_phone', '+595 21 219 7000');
        $sysHours    = \App\Models\HomeConfiguration::getSetting('business_hours', 'Lun – Vie: 07:00 – 15:00');
        $sysAddress  = \App\Models\HomeConfiguration::getSetting('address', 'Santo Domingo c/ Avda. Santísimo Sacramento, Asunción');
        $sysFooter   = \App\Models\HomeConfiguration::getSetting('footer_text', '© ' . date('Y') . ' Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.');

        return [
            'logoInstitucional' => $logoInstitucional,
            'institucion'       => $institucion,
            'dependencia'       => $dependencia,
            'sysLogoUrl'        => $sysLogoUrl,
            'sysSiteName'       => $sysSiteName,
            'sysEmail'          => $sysEmail,
            'sysPhone'          => $sysPhone,
            'sysHours'          => $sysHours,
            'sysAddress'        => $sysAddress,
            'sysFooter'         => $sysFooter,
        ];
    }

    /**
     * Prepara el logo para DomPDF convirtiendo recursos locales a data URI base64.
     */
    private function prepareLogoForPdf(?string $logoUrl): ?string
    {
        if (empty($logoUrl)) return null;

        if (str_starts_with($logoUrl, 'data:image')) {
            return $logoUrl;
        }

        $localFile = null;
        $storagePrefix = asset('storage/');
        if (str_starts_with($logoUrl, $storagePrefix)) {
            $relative = str_replace($storagePrefix, '', $logoUrl);
            $storageCandidate = storage_path('app/public/' . ltrim($relative, '/'));
            if (file_exists($storageCandidate)) {
                $localFile = $storageCandidate;
            }
        } elseif (str_starts_with($logoUrl, '/storage/')) {
            $publicCandidate = public_path(ltrim($logoUrl, '/'));
            if (file_exists($publicCandidate)) {
                $localFile = $publicCandidate;
            }
        } elseif (file_exists(public_path(ltrim($logoUrl, '/')))) {
            $localFile = public_path(ltrim($logoUrl, '/'));
        }

        if ($localFile && file_exists($localFile)) {
            $mime = mime_content_type($localFile) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($localFile));
        }

        return $logoUrl;
    }

    /**
     * Retorna los medicamentos asociados a una especialidad (Vademécum Oficial e Históricos).
     */
    public function getEspecialidadMedicamentos($id)
    {
        $especialidad = RiissEspecialidad::findOrFail($id);

        // Medicamentos autorizados según Vademécum
        $vademecum = $especialidad->medicamentosVademecum()
            ->select('riiss_medicamentos.id', 'riiss_medicamentos.codigo', 'riiss_medicamentos.nombre', 'riiss_medicamentos.concentracion', 'riiss_medicamentos.forma_farmaceutica', 'riiss_medicamentos.via_administracion', 'riiss_medicamentos.uso_vademecum', 'riiss_medicamentos.es_vademecum')
            ->orderBy('riiss_medicamentos.nombre')
            ->get();

        $vademecumIds = $vademecum->pluck('id')->toArray();

        // Medicamentos históricos por dispensación (excluyendo los que ya están en vademécum)
        $historicos = $especialidad->medicamentosHistoricos()
            ->whereNotIn('riiss_medicamentos.id', !empty($vademecumIds) ? $vademecumIds : [0])
            ->select('riiss_medicamentos.id', 'riiss_medicamentos.codigo', 'riiss_medicamentos.nombre', 'riiss_medicamentos.concentracion', 'riiss_medicamentos.forma_farmaceutica', 'riiss_medicamentos.via_administracion', 'riiss_medicamentos.uso_vademecum', 'riiss_medicamentos.es_vademecum')
            ->orderBy('riiss_medicamentos.nombre')
            ->get();

        return response()->json([
            'success' => true,
            'especialidad' => [
                'id' => $especialidad->id,
                'nombre' => $especialidad->nombre,
                'codigo' => $especialidad->codigo,
                'activo' => $especialidad->activo,
                'total_vademecum' => $vademecum->count(),
                'total_historicos' => $historicos->count(),
            ],
            'vademecum' => $vademecum,
            'historicos' => $historicos,
        ]);
    }

    /**
     * Vincula un medicamento al Vademécum Oficial de una especialidad.
     */
    public function vincularMedicamento(Request $request)
    {
        $request->validate([
            'especialidad_id' => 'required|integer|exists:bioestadistica.especialidades_medicas,id',
            'medicamento_id'   => 'required|integer|exists:riiss_medicamentos,id',
        ]);

        $especialidad = RiissEspecialidad::findOrFail($request->especialidad_id);
        $medicamento   = RiissMedicamento::findOrFail($request->medicamento_id);

        $especialidad->medicamentosVademecum()->syncWithoutDetaching([$medicamento->id]);

        $totalVademecum = $especialidad->medicamentosVademecum()->count();

        return response()->json([
            'success' => true,
            'message' => "Medicamento '{$medicamento->nombre}' vinculado exitosamente al Vademécum de {$especialidad->nombre}.",
            'total_vademecum' => $totalVademecum,
        ]);
    }

    /**
     * Desvincula un medicamento del Vademécum Oficial de una especialidad.
     */
    public function desvincularMedicamento(Request $request)
    {
        $request->validate([
            'especialidad_id' => 'required|integer|exists:bioestadistica.especialidades_medicas,id',
            'medicamento_id'   => 'required|integer|exists:riiss_medicamentos,id',
        ]);

        $especialidad = RiissEspecialidad::findOrFail($request->especialidad_id);
        $especialidad->medicamentosVademecum()->detach($request->medicamento_id);

        $totalVademecum = $especialidad->medicamentosVademecum()->count();

        return response()->json([
            'success' => true,
            'message' => 'Medicamento desvinculado del Vademécum de la especialidad.',
            'total_vademecum' => $totalVademecum,
        ]);
    }

    /**
     * Búsqueda dinámica con Select2 de medicamentos del catálogo.
     */
    public function buscarMedicamentosSelect2(Request $request)
    {
        $term = trim($request->get('q', ''));
        $query = RiissMedicamento::query();

        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('nombre', 'ilike', "%{$term}%")
                  ->orWhere('codigo', 'ilike', "%{$term}%")
                  ->orWhere('concentracion', 'ilike', "%{$term}%")
                  ->orWhere('forma_farmaceutica', 'ilike', "%{$term}%");
            });
        }

        $medicamentos = $query->orderBy('es_vademecum', 'desc')
            ->orderBy('nombre')
            ->limit(35)
            ->get();

        $results = $medicamentos->map(function ($med) {
            $sub = array_filter([$med->concentracion, $med->forma_farmaceutica, $med->via_administracion]);
            $subText = !empty($sub) ? implode(' | ', $sub) : '';
            return [
                'id' => $med->id,
                'text' => ($med->codigo ? "[{$med->codigo}] " : '') . $med->nombre . ($subText ? " — ({$subText})" : '') . ($med->es_vademecum ? ' ⭐ [VADEMÉCUM IPS]' : ''),
                'es_vademecum' => $med->es_vademecum,
            ];
        });

        return response()->json(['results' => $results]);
    }

    /**
     * Genera un nuevo enlace de acceso para la Unidad de Regulación Farmacéutica
     */
    public function generarEnlaceFarmaceutico(Request $request)
    {
        $request->validate([
            'analista_nombre'       => 'required|string|max:200',
            'analista_cargo'        => 'nullable|string|max:150',
            'matricula_profesional' => 'nullable|string|max:50',
            'analista_documento'    => 'nullable|string|max:50',
            'analista_telefono'     => 'nullable|string|max:50',
            'analista_email'        => 'nullable|email|max:150',
            'notas'                 => 'nullable|string|max:65000',
        ]);

        $sesion = RiissSesionFarmaceutica::create([
            'token'                 => Str::random(40),
            'codigo_acceso'         => 'VAL-FARM-' . strtoupper(Str::random(6)),
            'analista_nombre'       => trim($request->analista_nombre),
            'analista_cargo'        => $request->analista_cargo ? trim($request->analista_cargo) : 'Analista de la Unidad de Regulación Farmacéutica',
            'matricula_profesional' => $request->matricula_profesional ? trim($request->matricula_profesional) : null,
            'analista_documento'    => $request->analista_documento ? trim($request->analista_documento) : null,
            'analista_telefono'     => $request->analista_telefono ? trim($request->analista_telefono) : null,
            'analista_email'        => $request->analista_email ? trim($request->analista_email) : null,
            'notas'                 => $request->notas ? trim($request->notas) : null,
            'estado'                => 'activo',
            'created_by_user_id'    => Auth::id(),
        ]);

        $payload = [
            'url_portal'    => $sesion->url_acceso,
            'codigo_acceso' => $sesion->codigo_acceso,
            'analista'      => $sesion->analista_nombre,
            'cargo'         => $sesion->analista_cargo,
            'matricula'     => $sesion->matricula_profesional,
            'telefono'      => $sesion->analista_telefono,
            'tipo'          => 'farmaceutico'
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Enlace generado exitosamente para {$sesion->analista_nombre} (Regulación Farmacéutica).",
                'data'    => array_merge(['id' => $sesion->id, 'token' => $sesion->token], $payload),
            ]);
        }

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Enlace emitido exitosamente para {$sesion->analista_nombre}.")
            ->with('nuevo_acceso_farmaceutico', $payload);
    }

    /**
     * Elimina un enlace de la Unidad de Regulación Farmacéutica
     */
    public function eliminarEnlaceFarmaceutico($id)
    {
        $sesion = RiissSesionFarmaceutica::findOrFail($id);
        $nombre = $sesion->analista_nombre;
        $sesion->delete();

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Enlace de regulación farmacéutica de '{$nombre}' eliminado.");
    }

    /**
     * Actualiza los datos de un enlace de la Unidad de Regulación Farmacéutica
     */
    public function actualizarEnlaceFarmaceutico(Request $request, $id)
    {
        $request->validate([
            'analista_nombre'       => 'required|string|max:200',
            'analista_cargo'        => 'nullable|string|max:150',
            'matricula_profesional' => 'nullable|string|max:50',
            'analista_documento'    => 'nullable|string|max:50',
            'analista_telefono'     => 'nullable|string|max:50',
            'analista_email'        => 'nullable|email|max:150',
            'estado'                => 'required|string|in:activo,finalizado,inactivo',
            'codigo_acceso'         => 'nullable|string|max:30',
            'notas'                 => 'nullable|string|max:65000',
        ]);

        $sesion = RiissSesionFarmaceutica::findOrFail($id);

        $sesion->analista_nombre       = trim($request->analista_nombre);
        $sesion->analista_cargo        = $request->analista_cargo ? trim($request->analista_cargo) : $sesion->analista_cargo;
        $sesion->matricula_profesional = $request->matricula_profesional ? trim($request->matricula_profesional) : null;
        $sesion->analista_documento    = $request->analista_documento ? trim($request->analista_documento) : null;
        $sesion->analista_telefono     = $request->analista_telefono ? trim($request->analista_telefono) : null;
        $sesion->analista_email        = $request->analista_email ? trim($request->analista_email) : null;
        $sesion->estado                = $request->estado;
        $sesion->notas                 = $request->notas ? trim($request->notas) : null;

        if ($request->filled('codigo_acceso')) {
            $sesion->codigo_acceso = strtoupper(trim($request->codigo_acceso));
        }

        $sesion->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Enlace de regulación farmacéutica de '{$sesion->analista_nombre}' actualizado correctamente.",
                'data'    => $sesion,
            ]);
        }

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Enlace de regulación farmacéutica de '{$sesion->analista_nombre}' actualizado exitosamente.");
    }

    /**
     * Exporta la Matriz Consolidada de Control Cruzado RIISS (Establecimiento -> Especialidades Validadas -> Medicamentos Aprobados Vademécum)
     */
    public function exportarMatrizExcel()
    {
        $establecimientos = Establecimiento::select('id_establecimiento', 'nombre_oficial', 'departamento', 'area_gestion', 'tipologia_clasificacion')
            ->orderBy('area_gestion')
            ->orderBy('departamento')
            ->orderBy('nombre_oficial')
            ->get();

        $registrosValidados = ValidacionEspecialidadRegistro::where('estado', 'activa')
            ->get()
            ->groupBy('establecimiento_id');

        $dictamenesInvalidados = RiissValidacionFarmaceuticaMedicamento::where('estado_validacion', 'invalidado')
            ->get()
            ->keyBy(function($item) {
                return $item->especialidad_id . '_' . $item->medicamento_id;
            });

        $especialidadesConMedicamentos = RiissEspecialidad::with(['medicamentosVademecum' => function($q) {
            $q->orderBy('nombre');
        }])->get()->keyBy('id');

        $filename = 'MATRIZ_CONSOLIDADA_RIISS_IPS_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function() use ($establecimientos, $registrosValidados, $especialidadesConMedicamentos, $dictamenesInvalidados) {
            $output = fopen('php://output', 'w');
            // UTF-8 BOM para apertura correcta en Microsoft Excel
            fputs($output, "\xEF\xBB\xBF");

            // Encabezados oficiales de la Matriz Consolidada RIISS IPS
            fputcsv($output, [
                'ID_ESTABLECIMIENTO',
                'ESTABLECIMIENTO_SALUD',
                'AREA_GESTION',
                'DEPARTAMENTO',
                'TIPOLOGIA',
                'ESPECIALIDAD_MEDICA',
                'ESTADO_ESPECIALIDAD_TERRENO',
                'CODIGO_MEDICAMENTO',
                'MEDICAMENTO_VADEMECUM_OFICIAL',
                'CONCENTRACION',
                'FORMA_FARMACEUTICA',
                'VIA_ADMINISTRACION',
                'USO_VADEMECUM',
                'ESTADO_REGULACION_FARMACEUTICA',
                'JUSTIFICACION_DICTAMEN'
            ], ';');

            foreach ($establecimientos as $est) {
                $regs = $registrosValidados->get($est->id_establecimiento, collect());

                if ($regs->isEmpty()) {
                    fputcsv($output, [
                        $est->id_establecimiento,
                        $est->nombre_oficial,
                        $est->area_gestion,
                        $est->departamento,
                        $est->tipologia_clasificacion,
                        'SIN ESPECIALIDADES VALIDADAS ACTIVAS',
                        'PENDIENTE DE RELEVAMIENTO',
                        '—',
                        '—',
                        '—',
                        '—',
                        '—',
                        '—',
                        '—',
                        ''
                    ], ';');
                    continue;
                }

                foreach ($regs as $reg) {
                    $esp = $especialidadesConMedicamentos->get($reg->especialidad_id);
                    if (!$esp) continue;

                    if ($esp->medicamentosVademecum->isEmpty()) {
                        fputcsv($output, [
                            $est->id_establecimiento,
                            $est->nombre_oficial,
                            $est->area_gestion,
                            $est->departamento,
                            $est->tipologia_clasificacion,
                            $esp->nombre,
                            'VALIDADA ACTIVA',
                            '—',
                            'SIN MEDICAMENTOS VADEMÉCUM ASIGNADOS',
                            '',
                            '',
                            '',
                            '',
                            'PENDIENTE DE HOMOLOGACION',
                            ''
                        ], ';');
                        continue;
                    }

                    foreach ($esp->medicamentosVademecum as $med) {
                        $key = $esp->id . '_' . $med->id;
                        $isInvalid = $dictamenesInvalidados->has($key);
                        $dictamen = $dictamenesInvalidados->get($key);

                        fputcsv($output, [
                            $est->id_establecimiento,
                            $est->nombre_oficial,
                            $est->area_gestion,
                            $est->departamento,
                            $est->tipologia_clasificacion,
                            $esp->nombre,
                            'VALIDADA ACTIVA',
                            $med->codigo ?: '—',
                            $med->nombre,
                            $med->concentracion ?: '',
                            $med->forma_farmaceutica ?: '',
                            $med->via_administracion ?: '',
                            $med->uso_vademecum ?: 'AMBULATORIO / INTERNACION',
                            $isInvalid ? 'INVALIDADO / RETIRADO' : 'APROBADO VADEMECUM',
                            $isInvalid && $dictamen ? $dictamen->justificacion : 'Conforme a norma'
                        ], ';');
                    }
                }
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Muestra la vista de documentación oficial y flujograma metodológico RIISS IPS (2026)
     */
    public function flujogramaDocumentacion()
    {
        $logoUrl = \App\Models\HomeConfiguration::getSetting('logo_url') ?: asset('material/img/new_logo.png');
        return view('admin.riiss.especialidades_validacion.flujograma_documentacion', compact('logoUrl'));
    }
}
