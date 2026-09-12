<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\HomeConfiguration;
use App\Models\Riiss\RiissSesionFarmaceutica;
use App\Models\Riiss\RiissValidacionFarmaceuticaEspecialidad;
use App\Models\Riiss\RiissValidacionFarmaceuticaMedicamento;
use App\Models\RiissEspecialidad;
use App\Models\RiissMedicamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ValidacionFarmaceuticaController extends Controller
{
    /**
     * Acceso al Portal Remoto de la Unidad de Regulación Farmacéutica
     */
    public function portalShow($token)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)
            ->orWhere('codigo_acceso', $token)
            ->first();

        if (!$sesion) {
            abort(404, 'El enlace de validación farmacéutica no existe o ha expirado.');
        }

        $isAuth = session('riiss_farm_auth_' . $sesion->token) || session('riiss_farm_auth_' . $token);

        if (!$isAuth) {
            return view('admin.riiss.especialidades_validacion.portal_farmaceutico_auth', compact('sesion'));
        }

        // Estadísticas generales para el portal
        $totalEspecialidades = RiissEspecialidad::count();
        
        $validaciones = RiissValidacionFarmaceuticaEspecialidad::where('sesion_farmaceutica_id', $sesion->id)
            ->get()
            ->keyBy('especialidad_id');

        $totalValidadas = $validaciones->where('estado', 'validada')->count();
        $totalPendientes = $totalEspecialidades - $totalValidadas;

        $dictamenes = RiissValidacionFarmaceuticaMedicamento::where('sesion_farmaceutica_id', $sesion->id)->get();
        $totalMedicamentosAprobados = $dictamenes->where('estado_validacion', 'validado')->count();
        $totalMedicamentosInvalidados = $dictamenes->where('estado_validacion', 'invalidado')->count();
        $totalMedicamentosIncorporados = $dictamenes->where('estado_validacion', 'incorporado')->count();

        // Especialidades con conteo de medicamentos según Vademécum
        $especialidades = RiissEspecialidad::withCount([
            'medicamentosVademecum as total_vademecum',
            'medicamentosHistoricos as total_historicos',
        ])->orderBy('nombre')->get();

        return view('admin.riiss.especialidades_validacion.portal_farmaceutico', compact(
            'sesion',
            'totalEspecialidades',
            'totalValidadas',
            'totalPendientes',
            'totalMedicamentosAprobados',
            'totalMedicamentosInvalidados',
            'totalMedicamentosIncorporados',
            'especialidades',
            'validaciones'
        ));
    }

    /**
     * Autenticación con Código de Acceso / PIN
     */
    public function verificarCodigo(Request $request, $token)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)
            ->orWhere('codigo_acceso', $token)
            ->first();

        if (!$sesion) {
            return response()->json(['ok' => false, 'message' => 'Enlace no válido.'], 404);
        }

        $request->validate(['codigo' => 'required|string']);

        $inputCodigo = strtoupper(trim(str_replace(['VAL-FARM-', 'VAL-', 'FARM-', '-', ' '], '', $request->codigo)));
        $sesionCodigo = strtoupper(trim(str_replace(['VAL-FARM-', 'VAL-', 'FARM-', '-', ' '], '', $sesion->codigo_acceso)));

        if ($inputCodigo !== $sesionCodigo && strtoupper(trim($request->codigo)) !== strtoupper(trim($sesion->codigo_acceso))) {
            return response()->json([
                'ok' => false,
                'message' => 'El Código PIN ingresado es incorrecto. Por favor verifique el código recibido.'
            ], 422);
        }

        session([
            'riiss_farm_auth_' . $sesion->token => true,
            'riiss_farm_auth_' . $token => true,
        ]);

        return response()->json([
            'ok' => true,
            'redirect' => route('riiss.portal-farmaceutico.show', ['token' => $sesion->token]),
        ]);
    }

    /**
     * Cierre de sesión del portal
     */
    public function salirPortal($token)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)->first();
        if ($sesion) {
            session()->forget('riiss_farm_auth_' . $sesion->token);
        }
        session()->forget('riiss_farm_auth_' . $token);

        return redirect()->route('riiss.portal-farmaceutico.show', ['token' => $token]);
    }

    /**
     * Retorna los medicamentos de una especialidad con su estado de dictamen farmacéutico
     */
    public function getEspecialidadMedicamentos($token, $especialidad_id)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();
        $especialidad = RiissEspecialidad::findOrFail($especialidad_id);

        // Medicamentos del Vademécum Oficial asignados a esta especialidad
        $medicamentos = $especialidad->medicamentosVademecum()
            ->select('riiss_medicamentos.id', 'riiss_medicamentos.codigo', 'riiss_medicamentos.nombre', 'riiss_medicamentos.concentracion', 'riiss_medicamentos.forma_farmaceutica', 'riiss_medicamentos.via_administracion', 'riiss_medicamentos.uso_vademecum')
            ->orderBy('riiss_medicamentos.nombre')
            ->get();

        // Dictámenes previos guardados
        $dictamenes = RiissValidacionFarmaceuticaMedicamento::where('especialidad_id', $especialidad->id)
            ->get()
            ->keyBy('medicamento_id');

        // Registro de validación general de la especialidad
        $validacionEspecialidad = RiissValidacionFarmaceuticaEspecialidad::where('especialidad_id', $especialidad->id)
            ->where('sesion_farmaceutica_id', $sesion->id)
            ->first();

        $lista = $medicamentos->map(function ($m) use ($dictamenes) {
            $dictamen = $dictamenes->get($m->id);
            return [
                'id'                   => $m->id,
                'codigo'               => $m->codigo ?: '—',
                'nombre'               => $m->nombre,
                'concentracion'        => $m->concentracion ?: '',
                'forma_farmaceutica'   => $m->forma_farmaceutica ?: '',
                'via_administracion'   => $m->via_administracion ?: '',
                'uso_vademecum'        => $m->uso_vademecum ?: 'AMBULATORIO / INTERNACION',
                'estado_dictamen'      => $dictamen ? $dictamen->estado_validacion : 'pendiente', // 'validado', 'invalidado', 'incorporado', 'pendiente'
                'justificacion'        => $dictamen ? ($dictamen->justificacion ?? '') : '',
                'validado_por'         => $dictamen ? $dictamen->validado_por : null,
                'validado_at'          => $dictamen && $dictamen->validado_at ? $dictamen->validado_at->format('d/m/Y H:i') : null,
            ];
        });

        // Contadores
        $totalItems = $lista->count();
        $totalValidados = $lista->where('estado_dictamen', 'validado')->count();
        $totalInvalidados = $lista->where('estado_dictamen', 'invalidado')->count();
        $totalIncorporados = $lista->where('estado_dictamen', 'incorporado')->count();
        $totalPendientes = $totalItems - ($totalValidados + $totalInvalidados + $totalIncorporados);

        return response()->json([
            'success'                => true,
            'especialidad'           => [
                'id'                 => $especialidad->id,
                'nombre'             => $especialidad->nombre,
                'codigo'             => $especialidad->codigo,
                'total_items'        => $totalItems,
                'total_validados'    => $totalValidados,
                'total_invalidados'  => $totalInvalidados,
                'total_incorporados' => $totalIncorporados,
                'total_pendientes'   => $totalPendientes,
                'estado_general'     => $validacionEspecialidad ? $validacionEspecialidad->estado : 'pendiente',
                'observaciones'      => $validacionEspecialidad ? $validacionEspecialidad->observaciones_tecnicas : '',
                'firmado_por'        => $validacionEspecialidad ? $validacionEspecialidad->firmado_por : null,
                'firmado_at'         => $validacionEspecialidad && $validacionEspecialidad->firmado_at ? $validacionEspecialidad->firmado_at->format('d/m/Y H:i') : null,
            ],
            'medicamentos'           => $lista->values(),
        ]);
    }

    /**
     * Dictamina un medicamento individual (Validar o Invalidar con Justificación)
     */
    public function dictaminarMedicamento(Request $request, $token)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'especialidad_id'   => 'required|integer|exists:bioestadistica.especialidades_medicas,id',
            'medicamento_id'     => 'required|integer|exists:riiss_medicamentos,id',
            'estado_validacion' => 'required|string|in:validado,invalidado',
            'justificacion'     => 'nullable|string|max:1000',
        ]);

        if ($request->estado_validacion === 'invalidado' && empty(trim($request->justificacion))) {
            return response()->json([
                'success' => false,
                'message' => 'Para invalidar o retirar un medicamento del Vademécum de esta especialidad es obligatorio registrar la justificación técnica farmacológica.',
            ], 422);
        }

        // Buscar o crear la cabecera de validación de la especialidad
        $valEsp = RiissValidacionFarmaceuticaEspecialidad::firstOrCreate([
            'sesion_farmaceutica_id' => $sesion->id,
            'especialidad_id'        => $request->especialidad_id,
        ], [
            'estado' => 'pendiente',
        ]);

        // Guardar dictamen del medicamento
        $dictamen = RiissValidacionFarmaceuticaMedicamento::updateOrCreate([
            'especialidad_id' => $request->especialidad_id,
            'medicamento_id'   => $request->medicamento_id,
        ], [
            'validacion_farmaceutica_especialidad_id' => $valEsp->id,
            'sesion_farmaceutica_id'                  => $sesion->id,
            'estado_validacion'                       => $request->estado_validacion,
            'justificacion'                           => $request->justificacion ? trim($request->justificacion) : null,
            'validado_por'                            => $sesion->analista_nombre,
            'validado_at'                             => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $request->estado_validacion === 'validado' ? 'Medicamento validado exitosamente.' : 'Medicamento invalidado con justificación técnica.',
            'dictamen' => [
                'id'                => $dictamen->id,
                'estado_validacion' => $dictamen->estado_validacion,
                'justificacion'     => $dictamen->justificacion,
                'validado_por'      => $dictamen->validado_por,
                'validado_at'       => $dictamen->validado_at->format('d/m/Y H:i'),
            ]
        ]);
    }

    /**
     * Incorpora un medicamento faltante del Vademécum Aprobado a la especialidad
     */
    public function incorporarMedicamento(Request $request, $token)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'especialidad_id' => 'required|integer|exists:bioestadistica.especialidades_medicas,id',
            'medicamento_id'   => 'required|integer|exists:riiss_medicamentos,id',
            'justificacion'   => 'required|string|max:1000',
        ]);

        $especialidad = RiissEspecialidad::findOrFail($request->especialidad_id);
        $medicamento   = RiissMedicamento::findOrFail($request->medicamento_id);

        // Vincular en la tabla pivote oficial
        $especialidad->medicamentosVademecum()->syncWithoutDetaching([$medicamento->id]);

        $valEsp = RiissValidacionFarmaceuticaEspecialidad::firstOrCreate([
            'sesion_farmaceutica_id' => $sesion->id,
            'especialidad_id'        => $especialidad->id,
        ], [
            'estado' => 'pendiente',
        ]);

        $dictamen = RiissValidacionFarmaceuticaMedicamento::updateOrCreate([
            'especialidad_id' => $especialidad->id,
            'medicamento_id'   => $medicamento->id,
        ], [
            'validacion_farmaceutica_especialidad_id' => $valEsp->id,
            'sesion_farmaceutica_id'                  => $sesion->id,
            'estado_validacion'                       => 'incorporado',
            'justificacion'                           => trim($request->justificacion),
            'validado_por'                            => $sesion->analista_nombre,
            'validado_at'                             => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Medicamento '{$medicamento->nombre}' incorporado exitosamente al Vademécum de {$especialidad->nombre}.",
        ]);
    }

    /**
     * Firma digital y cierre del Dictamen Farmacéutico de la especialidad
     */
    public function firmarDictamenEspecialidad(Request $request, $token)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();

        $request->validate([
            'especialidad_id'         => 'required|integer|exists:bioestadistica.especialidades_medicas,id',
            'observaciones_tecnicas'  => 'nullable|string|max:2000',
            'firma_digital'           => 'required|string',
            'firmado_por'             => 'required|string|max:200',
            'firmado_documento'       => 'nullable|string|max:50',
            'firmado_matricula'       => 'nullable|string|max:50',
        ]);

        $valEsp = RiissValidacionFarmaceuticaEspecialidad::updateOrCreate([
            'sesion_farmaceutica_id' => $sesion->id,
            'especialidad_id'        => $request->especialidad_id,
        ], [
            'estado'                 => 'validada',
            'observaciones_tecnicas' => $request->observaciones_tecnicas ? trim($request->observaciones_tecnicas) : null,
            'firma_digital'          => $request->firma_digital,
            'firmado_por'            => trim($request->firmado_por),
            'firmado_documento'      => $request->firmado_documento ? trim($request->firmado_documento) : $sesion->analista_documento,
            'firmado_matricula'      => $request->firmado_matricula ? trim($request->firmado_matricula) : $sesion->matricula_profesional,
            'firmado_at'             => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dictamen Técnico Farmacéutico firmado y consolidado exitosamente.',
            'firmado_at' => $valEsp->firmado_at->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Vista imprimible / PDF del Dictamen Técnico Farmacéutico
     */
    public function imprimirDictamen($token, $especialidad_id)
    {
        $sesion = RiissSesionFarmaceutica::where('token', $token)->orWhere('codigo_acceso', $token)->firstOrFail();
        $especialidad = RiissEspecialidad::findOrFail($especialidad_id);

        $validacion = RiissValidacionFarmaceuticaEspecialidad::where('sesion_farmaceutica_id', $sesion->id)
            ->where('especialidad_id', $especialidad->id)
            ->first();

        $medicamentos = $especialidad->medicamentosVademecum()
            ->select('riiss_medicamentos.*')
            ->orderBy('riiss_medicamentos.nombre')
            ->get();

        $dictamenes = RiissValidacionFarmaceuticaMedicamento::where('especialidad_id', $especialidad->id)
            ->get()
            ->keyBy('medicamento_id');

        return view('admin.riiss.especialidades_validacion.dictamen_farmaceutico_imprimir', compact(
            'sesion',
            'especialidad',
            'validacion',
            'medicamentos',
            'dictamenes'
        ));
    }
}
