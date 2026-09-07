<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\RiissAuditoriaToken;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RiissAuditorPortalController extends Controller
{
    /**
     * POST /riiss/auditoria/generar-token
     * Generar enlace temporal con PIN para auditores/asesores externos.
     */
    public function generarToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'establecimiento_id' => 'nullable|string|exists:establecimientos,id_establecimiento',
            'duracion_horas'     => 'nullable|integer|min:1|max:720',
            'destinatario'       => 'nullable|string|max:150',
        ]);

        $duracion = (int) ($validated['duracion_horas'] ?? 24);
        $estId = $validated['establecimiento_id'] ?? null;
        $destinatario = $validated['destinatario'] ?? null;
        $userId = auth()->id();

        $tokenRecord = RiissAuditoriaToken::generar($estId, $duracion, $destinatario, $userId);

        $estNombre = 'Redes Integradas de Servicios de Salud (RIISS)';
        if ($estId) {
            $est = Establecimiento::find($estId);
            if ($est) {
                $estNombre = $est->nombre_oficial;
            }
        }

        $urlPortal = route('riiss.portal-auditor.show', ['token' => $tokenRecord->token]);
        $expiraTexto = $tokenRecord->expira_en->format('d/m/Y H:i');

        // Mensaje formateado para WhatsApp
        $msg = "🏥 *IPS - Portal de Auditoría y Verificación RIISS*\n";
        $msg .= "🏛️ *Establecimiento:* " . $estNombre . "\n\n";
        if ($destinatario) {
            $msg .= "Estimado/a *" . $destinatario . "*,\n";
        }
        $msg .= "Le compartimos el acceso exclusivo de solo lectura para la verificación y auditoría de la cartera de servicios y vademécum de medicamentos.\n\n";
        $msg .= "🔗 *Enlace de Acceso:*\n" . $urlPortal . "\n\n";
        $msg .= "🔑 *Código PIN de Seguridad:* *" . $tokenRecord->pin . "*\n";
        $msg .= "⏳ *Validez:* " . $duracion . " Horas (Vence el " . $expiraTexto . " hs)\n\n";
        $msg .= "ℹ️ _Desde este portal podrá revisar los servicios, consultar medicamentos en vivo y descargar las planillas de verificación en PDF._";

        $urlWhatsApp = "https://api.whatsapp.com/send?text=" . urlencode($msg);

        return response()->json([
            'ok'               => true,
            'token'            => $tokenRecord->token,
            'pin'              => $tokenRecord->pin,
            'duracion_horas'   => $duracion,
            'expira_en'        => $expiraTexto,
            'url_portal'       => $urlPortal,
            'url_whatsapp'     => $urlWhatsApp,
            'mensaje_whatsapp' => $msg,
            'establecimiento'  => $estNombre,
        ]);
    }

    /**
     * GET /riiss/portal-auditor/{token}
     * Vista pública del Portal de Auditoría (o pantalla de ingreso de PIN si no está validado).
     */
    public function mostrarPortal(string $token)
    {
        $tokenRecord = RiissAuditoriaToken::where('token', $token)->first();

        if (!$tokenRecord || $tokenRecord->isExpirado()) {
            return view('admin.riiss.auditoria.expirado', [
                'tokenRecord' => $tokenRecord,
            ]);
        }

        // Verificar si la sesión ya validó el PIN
        $sessionKey = 'riiss_auditor_auth_' . $token;
        if (!session()->get($sessionKey, false)) {
            $est = $tokenRecord->establecimiento_id ? Establecimiento::find($tokenRecord->establecimiento_id) : null;
            return view('admin.riiss.auditoria.desafio_pin', compact('tokenRecord', 'token', 'est'));
        }

        // Cargar Establecimiento con sus relaciones
        $est = Establecimiento::where('id_establecimiento', $tokenRecord->establecimiento_id)
            ->with(['especialidades.medicamentos', 'medicamentos', 'inmuebleContratos', 'complejidadTipo'])
            ->firstOrFail();

        $especialidadesMap = $est->especialidades->keyBy('id');

        // Procesar medicamentos consolidados y cartera
        $medicamentosConsolidados = [];
        $especialidadesMedicamentos = [];
        $totalAsignaciones = 0;

        foreach ($est->medicamentos as $med) {
            $espId = $med->pivot->especialidad_id;
            $espNombre = $especialidadesMap->has($espId) ? $especialidadesMap->get($espId)->nombre : 'Pacientes Crónicos / Otras Áreas';

            if (!isset($especialidadesMedicamentos[$espNombre])) {
                $especialidadesMedicamentos[$espNombre] = [];
            }
            $especialidadesMedicamentos[$espNombre][] = [
                'codigo' => $med->codigo,
                'nombre' => $med->nombre,
            ];
            $totalAsignaciones++;

            $medKey = $med->codigo ? $med->codigo : ('ID_' . $med->id);
            if (!isset($medicamentosConsolidados[$medKey])) {
                $medicamentosConsolidados[$medKey] = [
                    'id'             => $med->id,
                    'codigo'         => $med->codigo ?: 'S/C',
                    'nombre'         => $med->nombre,
                    'especialidades' => []
                ];
            }
            if (!in_array($espNombre, $medicamentosConsolidados[$medKey]['especialidades'])) {
                $medicamentosConsolidados[$medKey]['especialidades'][] = $espNombre;
            }
        }

        foreach ($est->especialidades as $esp) {
            if (!isset($especialidadesMedicamentos[$esp->nombre])) {
                $especialidadesMedicamentos[$esp->nombre] = [];
            }
        }

        ksort($especialidadesMedicamentos);
        uasort($medicamentosConsolidados, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));

        // Cartera para bloques
        $carteraServicios = [];
        foreach ($especialidadesMedicamentos as $nombreEsp => $meds) {
            $espObj = $est->especialidades->firstWhere('nombre', $nombreEsp);
            $carteraServicios[] = [
                'id'           => $espObj ? $espObj->id : 0,
                'nombre'       => $nombreEsp,
                'total_meds'   => count($meds),
                'medicamentos' => $meds,
            ];
        }

        return view('admin.riiss.auditoria.portal', [
            'tokenRecord'              => $tokenRecord,
            'est'                      => $est,
            'medicamentosConsolidados' => array_values($medicamentosConsolidados),
            'carteraServicios'         => $carteraServicios,
            'totalMedicamentosUnicos'  => count($medicamentosConsolidados),
            'totalEspecialidades'      => count($especialidadesMedicamentos),
            'totalAsignaciones'        => $totalAsignaciones,
        ]);
    }

    /**
     * POST /riiss/portal-auditor/{token}/verificar-pin
     */
    public function verificarPin(Request $request, string $token): JsonResponse
    {
        $tokenRecord = RiissAuditoriaToken::where('token', $token)->first();

        if (!$tokenRecord || $tokenRecord->isExpirado()) {
            return response()->json([
                'ok'      => false,
                'message' => 'Este enlace de auditoría ha expirado o ya no está disponible.'
            ], 410);
        }

        $request->validate([
            'pin' => 'required|string|size:6',
        ]);

        if (trim($request->pin) !== trim($tokenRecord->pin)) {
            return response()->json([
                'ok'      => false,
                'message' => 'El código PIN de seguridad es incorrecto. Por favor, revise el código enviado.'
            ], 422);
        }

        // Marcar sesión como autenticada
        session(['riiss_auditor_auth_' . $token => true]);

        // Registrar estadísticas de acceso
        $tokenRecord->increment('visitas_count');
        $tokenRecord->update([
            'ultimo_acceso_at' => Carbon::now(),
            'ip_ultimo_acceso' => $request->ip(),
        ]);

        return response()->json([
            'ok'       => true,
            'redirect' => route('riiss.portal-auditor.show', ['token' => $token]),
        ]);
    }

    /**
     * GET /riiss/portal-auditor/{token}/pdf
     * Descarga de PDFs oficiales autorizada por el token.
     */
    public function descargarPdf(string $token, Request $request)
    {
        $tokenRecord = RiissAuditoriaToken::where('token', $token)->first();

        if (!$tokenRecord || $tokenRecord->isExpirado()) {
            abort(403, 'Enlace de auditoría expirado');
        }

        $sessionKey = 'riiss_auditor_auth_' . $token;
        if (!session()->get($sessionKey, false)) {
            abort(403, 'Acceso no autorizado. Debe ingresar el código PIN de seguridad.');
        }

        $est = Establecimiento::where('id_establecimiento', $tokenRecord->establecimiento_id)
            ->with(['especialidades', 'medicamentos'])
            ->firstOrFail();

        $especialidadesMap = $est->especialidades->keyBy('id');

        $medicamentosConsolidados = [];
        $especialidadesMedicamentos = [];
        $totalAsignaciones = 0;

        foreach ($est->medicamentos as $med) {
            $espId = $med->pivot->especialidad_id;
            $espNombre = $especialidadesMap->has($espId) ? $especialidadesMap->get($espId)->nombre : 'Pacientes Crónicos / Otras Áreas';

            if (!isset($especialidadesMedicamentos[$espNombre])) {
                $especialidadesMedicamentos[$espNombre] = [];
            }
            $especialidadesMedicamentos[$espNombre][] = [
                'codigo' => $med->codigo,
                'nombre' => $med->nombre,
            ];
            $totalAsignaciones++;

            $medKey = $med->codigo ? $med->codigo : ('ID_' . $med->id);
            if (!isset($medicamentosConsolidados[$medKey])) {
                $medicamentosConsolidados[$medKey] = [
                    'id'             => $med->id,
                    'codigo'         => $med->codigo ?: 'S/C',
                    'nombre'         => $med->nombre,
                    'especialidades' => []
                ];
            }
            if (!in_array($espNombre, $medicamentosConsolidados[$medKey]['especialidades'])) {
                $medicamentosConsolidados[$medKey]['especialidades'][] = $espNombre;
            }
        }

        foreach ($est->especialidades as $esp) {
            if (!isset($especialidadesMedicamentos[$esp->nombre])) {
                $especialidadesMedicamentos[$esp->nombre] = [];
            }
        }

        ksort($especialidadesMedicamentos);
        uasort($medicamentosConsolidados, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));

        $tipo = $request->get('tipo', 'consolidado');
        $viewName = ($tipo === 'especialidad')
            ? 'admin.riiss.establecimientos.pdf_medicamentos_especialidad'
            : 'admin.riiss.establecimientos.pdf_medicamentos_consolidado';

        $pdf = Pdf::loadView($viewName, [
            'est'                        => $est,
            'medicamentosConsolidados'   => $medicamentosConsolidados,
            'especialidadesMedicamentos' => $especialidadesMedicamentos,
            'totalMedicamentosUnicos'    => count($medicamentosConsolidados),
            'totalAsignaciones'          => $totalAsignaciones,
            'totalEspecialidades'        => count($especialidadesMedicamentos),
            'fecha'                      => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $suffix = ($tipo === 'especialidad') ? '_Por_Especialidad' : '_Auditoria_Farmacia';
        $filename = 'RIISS_' . Str::slug($est->nombre_oficial) . $suffix . '.pdf';

        return $pdf->stream($filename);
    }
}
