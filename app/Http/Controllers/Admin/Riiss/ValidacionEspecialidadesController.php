<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\RiissEspecialidad;
use App\Models\RiissMedicamento;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\SesionValidador;
use App\Models\Riiss\ValidacionEspecialidadRegistro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ValidacionEspecialidadesController extends Controller
{
    /**
     * Bandeja Administrativa de Validaciones de Especialidades (Área Interior / Central)
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

        $sesiones = $querySesiones->paginate(15);

        // Departamentos agrupados por Área de Gestión
        $deptosInterior = Establecimiento::where('area_gestion', 'AREA INTERIOR')
            ->distinct()->whereNotNull('departamento')->pluck('departamento')->sort()->values();

        $deptosCentral = Establecimiento::where('area_gestion', 'AREA CENTRAL')
            ->distinct()->whereNotNull('departamento')->pluck('departamento')->sort()->values();

        $totalEstablecimientos = Establecimiento::count();
        $totalInterior = Establecimiento::where('area_gestion', 'AREA INTERIOR')->count();
        $totalCentral = Establecimiento::where('area_gestion', 'AREA CENTRAL')->count();

        $totalRegistrosValidados = ValidacionEspecialidadRegistro::where('estado', 'activa')->count();
        $totalRegistrosInactivos = ValidacionEspecialidadRegistro::where('estado', 'inactiva')->count();
        $totalConRevision = ValidacionEspecialidadRegistro::distinct('establecimiento_id')->count('establecimiento_id');

        // Nómina de establecimientos para el gestor de clasificación
        $todosEstablecimientos = Establecimiento::select('id_establecimiento', 'nombre_oficial', 'departamento', 'tipologia_clasificacion', 'area_gestion')
            ->orderBy('departamento')->orderBy('nombre_oficial')->get();

        return view('admin.riiss.especialidades_validacion.index', compact(
            'sesiones',
            'deptosInterior',
            'deptosCentral',
            'totalEstablecimientos',
            'totalInterior',
            'totalCentral',
            'totalRegistrosValidados',
            'totalRegistrosInactivos',
            'totalConRevision',
            'todosEstablecimientos'
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
            'area_gestion'        => 'required|string|in:AREA INTERIOR,AREA CENTRAL',
            'departamento_filtro' => 'nullable|string|max:100',
            'notas'               => 'nullable|string|max:500',
        ]);

        $areaGestion = $request->area_gestion ?: 'AREA INTERIOR';
        $deptoFiltro = ($request->departamento_filtro && !in_array($request->departamento_filtro, ['TODOS', 'TODOS_INTERIOR', 'TODOS_CENTRAL'])) 
            ? $request->departamento_filtro 
            : null;

        $sesion = SesionValidador::create([
            'token'               => Str::random(40),
            'codigo_acceso'       => 'VAL-' . strtoupper(Str::random(6)),
            'analista_nombre'     => trim($request->analista_nombre),
            'analista_cargo'      => $request->analista_cargo ? trim($request->analista_cargo) : ($areaGestion === 'AREA CENTRAL' ? 'Analista Técnico Área Central' : 'Analista Técnico Área Interior'),
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
            'area_gestion'       => 'required|in:AREA INTERIOR,AREA CENTRAL',
        ]);

        $est = Establecimiento::where('id_establecimiento', $request->establecimiento_id)->firstOrFail();
        $est->area_gestion = $request->area_gestion;
        $est->save();

        return response()->json([
            'success'      => true,
            'message'      => "Establecimiento {$est->nombre_oficial} actualizado a {$est->area_gestion}",
            'area_gestion' => $est->area_gestion,
        ]);
    }

    /**
     * Eliminar / Desactivar enlace de validador
     */
    public function eliminarEnlace($id)
    {
        $sesion = SesionValidador::findOrFail($id);
        $sesion->delete();

        return redirect()->route('riiss.validaciones.index')
            ->with('success', "Enlace y sesión de validador eliminados correctamente.");
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

        // Consultar Establecimientos filtrados estrictamente por el Área de Gestión del Token
        $estQuery = Establecimiento::query()->orderBy('departamento')->orderBy('nombre_oficial');

        if ($sesion->area_gestion) {
            $estQuery->where('area_gestion', $sesion->area_gestion);
        } else {
            $estQuery->where('area_gestion', 'AREA INTERIOR');
        }

        if ($sesion->departamento_filtro) {
            $estQuery->where('departamento', $sesion->departamento_filtro);
        }

        $establecimientos = $estQuery->get();

        // Departamentos disponibles para el filtro en la UI
        $departamentos = $establecimientos->pluck('departamento')->unique()->filter()->values();

        // Obtener resumen de conteos de especialidades validadas por establecimiento
        $resumenValidaciones = ValidacionEspecialidadRegistro::select(
            'establecimiento_id',
            DB::raw("COUNT(*) as total_registros"),
            DB::raw("COUNT(CASE WHEN estado = 'activa' THEN 1 END) as total_activas"),
            DB::raw("COUNT(CASE WHEN estado = 'inactiva' THEN 1 END) as total_inactivas")
        )->groupBy('establecimiento_id')->get()->keyBy('establecimiento_id');

        return view('admin.riiss.especialidades_validacion.portal_validador', compact(
            'sesion',
            'establecimientos',
            'departamentos',
            'resumenValidaciones'
        ));
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
                'estado'              => $reg ? $reg->estado : 'activa', // por defecto activa
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
        ]);
    }

    /**
     * Guardar/Actualizar estado de una especialidad (Check Activa / Inactiva y justificación opcional)
     */
    public function actualizarEspecialidad(Request $request, $token)
    {
        $sesion = SesionValidador::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'establecimiento_id' => 'required|string|exists:establecimientos,id_establecimiento',
            'especialidad_id'    => 'required|integer',
            'estado'             => 'required|in:activa,inactiva',
            'justificacion'      => 'nullable|string|max:1000', // NO obligatorio
        ]);

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

        return response()->json([
            'success'  => true,
            'message'  => 'Guardado correctamente',
            'registro' => $registro,
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
            'notas'        => 'nullable|string|max:1000',
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

        $institucion = 'INSTITUTO DE PREVISIÓN SOCIAL';
        $dependencia = ($sesion->area_gestion === 'AREA CENTRAL')
            ? 'DIRECCIÓN DE HOSPITALES DEL ÁREA CENTRAL'
            : 'DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR';

        $logoPath = public_path('assets/img/ips_logo.png');
        $logoInstitucional = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

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

        $institucion = 'INSTITUTO DE PREVISIÓN SOCIAL';
        $dependencia = ($sesion->area_gestion === 'AREA CENTRAL')
            ? 'DIRECCIÓN DE HOSPITALES DEL ÁREA CENTRAL'
            : 'DIRECCIÓN DE HOSPITALES DEL ÁREA INTERIOR';

        $logoPath = public_path('assets/img/ips_logo.png');
        $logoInstitucional = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

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
}
