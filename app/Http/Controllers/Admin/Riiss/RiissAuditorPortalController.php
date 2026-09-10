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
            'establecimiento_id' => 'nullable|string',
            'duracion_horas'     => 'nullable|integer|min:1|max:720',
            'destinatario'       => 'nullable|string|max:150',
        ]);

        $duracion = (int) ($validated['duracion_horas'] ?? 24);
        $estId = !empty($validated['establecimiento_id']) ? $validated['establecimiento_id'] : null;
        $destinatario = $validated['destinatario'] ?? null;
        $userId = auth()->id();

        $tokenRecord = RiissAuditoriaToken::generar($estId, $duracion, $destinatario, $userId);

        $esGlobal = empty($estId);
        $estNombre = '🌐 Red Nacional Completa (Todos los Establecimientos RIISS)';
        if (!$esGlobal) {
            $est = Establecimiento::find($estId);
            if ($est) {
                $estNombre = $est->nombre_oficial;
            }
        }

        $urlPortal = route('riiss.portal-auditor.show', ['token' => $tokenRecord->token]);
        $expiraTexto = $tokenRecord->expira_en->format('d/m/Y H:i');

        // Mensaje formateado para WhatsApp
        $msg = "🏥 *IPS - Portal de Auditoría y Verificación RIISS*\n";
        if ($esGlobal) {
            $msg .= "🌐 *Ámbito de Acceso:* *Toda la Red Nacional (Todos los Establecimientos)*\n\n";
        } else {
            $msg .= "🏛️ *Establecimiento:* " . $estNombre . "\n\n";
        }

        if ($destinatario) {
            $msg .= "Estimado/a *" . $destinatario . "*,\n";
        }
        $msg .= "Le compartimos el acceso exclusivo de solo lectura para la verificación y auditoría de la cartera de servicios, vademécum de medicamentos y datos técnicos de la red.\n\n";
        $msg .= "🔗 *Enlace de Acceso:*\n" . $urlPortal . "\n\n";
        $msg .= "🔑 *Código PIN de Seguridad:* *" . $tokenRecord->pin . "*\n";
        $msg .= "⏳ *Validez:* " . $duracion . " Horas (Vence el " . $expiraTexto . " hs)\n\n";
        $msg .= "ℹ️ _Desde este portal podrá navegar por todos los centros de la red, consultar medicamentos en vivo y descargar las planillas de verificación en PDF._";

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
            'es_global'        => $esGlobal,
        ]);
    }

    /**
     * GET /riiss/portal-auditor/{token}
     * Vista pública del Portal de Auditoría (o pantalla de ingreso de PIN si no está validado).
     */
    public function mostrarPortal(string $token, Request $request)
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

        // Caso 1: TOKEN GLOBAL (Sin establecimiento fijo) y sin parámetro ?est=
        if (empty($tokenRecord->establecimiento_id) && !$request->filled('est')) {
            $establecimientos = Establecimiento::activos()->asistenciales()
                ->with(['complejidadTipo'])
                ->withCount(['medicamentos', 'especialidades'])
                ->orderBy('nombre_oficial')
                ->get();

            $totalEstablecimientos = $establecimientos->count();
            $conMedicamentosCount  = $establecimientos->where('medicamentos_count', '>', 0)->count();
            $departamentos         = $establecimientos->pluck('departamento')->filter()->unique()->sort()->values();
            $complejidades         = $establecimientos->pluck('complejidad')->filter()->unique()->sort()->values();

            return view('admin.riiss.auditoria.portal_global', [
                'tokenRecord'           => $tokenRecord,
                'establecimientos'      => $establecimientos,
                'totalEstablecimientos' => $totalEstablecimientos,
                'conMedicamentosCount'  => $conMedicamentosCount,
                'departamentos'         => $departamentos,
                'complejidades'         => $complejidades,
            ]);
        }

        // Caso 2: Establecimiento Específico (o seleccionado desde el Portal Global)
        $targetEstId = $tokenRecord->establecimiento_id ?: $request->get('est');
        $esGlobal = empty($tokenRecord->establecimiento_id);

        $est = Establecimiento::where('id_establecimiento', $targetEstId)
            ->with(['especialidades', 'medicamentos', 'inmuebleContratos', 'complejidadTipo'])
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
                'codigo'                => $med->codigo,
                'nombre'                => $med->nombre,
                'es_cronico'            => (bool) $med->es_cronico,
                'categoria_terapeutica' => $med->categoria_terapeutica,
                'es_psicotropico'       => (bool) $med->es_psicotropico,
                'resolucion_respaldo'   => $med->resolucion_respaldo,
            ];
            $totalAsignaciones++;

            $medKey = $med->codigo ? $med->codigo : ('ID_' . $med->id);
            if (!isset($medicamentosConsolidados[$medKey])) {
                $medicamentosConsolidados[$medKey] = [
                    'id'                    => $med->id,
                    'codigo'                => $med->codigo ?: 'S/C',
                    'nombre'                => $med->nombre,
                    'es_cronico'            => (bool) $med->es_cronico,
                    'categoria_terapeutica' => $med->categoria_terapeutica,
                    'es_psicotropico'       => (bool) $med->es_psicotropico,
                    'resolucion_respaldo'   => $med->resolucion_respaldo,
                    'especialidades'        => []
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

        $totalCronicos = collect($medicamentosConsolidados)->where('es_cronico', true)->count();
        $totalPsicotropicos = collect($medicamentosConsolidados)->where('es_psicotropico', true)->count();
        $categoriasCronicos = collect($medicamentosConsolidados)->where('es_cronico', true)->pluck('categoria_terapeutica')->filter()->unique()->sort()->values();

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
            'totalCronicos'            => $totalCronicos,
            'totalPsicotropicos'       => $totalPsicotropicos,
            'categoriasCronicos'       => $categoriasCronicos,
            'totalEspecialidades'      => count($especialidadesMedicamentos),
            'totalAsignaciones'        => $totalAsignaciones,
            'es_global'                => $esGlobal,
            'url_volver_red'           => route('riiss.portal-auditor.show', ['token' => $token]),
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
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $tokenRecord = RiissAuditoriaToken::where('token', $token)->first();

        if (!$tokenRecord || $tokenRecord->isExpirado()) {
            abort(403, 'Enlace de auditoría expirado');
        }

        $sessionKey = 'riiss_auditor_auth_' . $token;
        if (!session()->get($sessionKey, false)) {
            abort(403, 'Acceso no autorizado. Debe ingresar el código PIN de seguridad.');
        }

        $targetEstId = $tokenRecord->establecimiento_id ?: $request->get('est_id');
        if (!$targetEstId) {
            abort(400, 'Establecimiento no especificado para la generación del reporte.');
        }

        $est = Establecimiento::where('id_establecimiento', $targetEstId)
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
                'codigo'                => $med->codigo,
                'nombre'                => $med->nombre,
                'es_cronico'            => (bool) $med->es_cronico,
                'categoria_terapeutica' => $med->categoria_terapeutica,
                'es_psicotropico'       => (bool) $med->es_psicotropico,
                'resolucion_respaldo'   => $med->resolucion_respaldo,
            ];
            $totalAsignaciones++;

            $medKey = $med->codigo ? $med->codigo : ('ID_' . $med->id);
            if (!isset($medicamentosConsolidados[$medKey])) {
                $medicamentosConsolidados[$medKey] = [
                    'id'                    => $med->id,
                    'codigo'                => $med->codigo ?: 'S/C',
                    'nombre'                => $med->nombre,
                    'es_cronico'            => (bool) $med->es_cronico,
                    'categoria_terapeutica' => $med->categoria_terapeutica,
                    'es_psicotropico'       => (bool) $med->es_psicotropico,
                    'resolucion_respaldo'   => $med->resolucion_respaldo,
                    'especialidades'        => []
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

        $totalCronicos = count(array_filter($medicamentosConsolidados, fn($m) => !empty($m['es_cronico'])));

        $tipo = $request->get('tipo', 'consolidado');
        $viewName = ($tipo === 'especialidad')
            ? 'admin.riiss.establecimientos.pdf_medicamentos_especialidad'
            : 'admin.riiss.establecimientos.pdf_medicamentos_consolidado';

        $pdf = Pdf::loadView($viewName, [
            'est'                        => $est,
            'medicamentosConsolidados'   => $medicamentosConsolidados,
            'especialidadesMedicamentos' => $especialidadesMedicamentos,
            'totalMedicamentosUnicos'    => count($medicamentosConsolidados),
            'totalMedicamentosCronicos'  => $totalCronicos,
            'totalCronicos'              => $totalCronicos,
            'totalAsignaciones'          => $totalAsignaciones,
            'totalEspecialidades'        => count($especialidadesMedicamentos),
            'fecha'                      => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('a4', 'portrait');

        $suffix = ($tipo === 'especialidad') ? '_Por_Especialidad' : '_Auditoria_Farmacia';
        $filename = 'RIISS_' . Str::slug($est->nombre_oficial) . $suffix . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * POST /riiss/portal-auditor/{token}/ping
     * Heartbeat para detectar auditores en línea en tiempo real.
     */
    public function ping(Request $request, string $token): JsonResponse
    {
        $tokenRecord = RiissAuditoriaToken::where('token', $token)->first();

        if (!$tokenRecord || $tokenRecord->isExpirado()) {
            return response()->json(['ok' => false, 'online' => false], 410);
        }

        $tokenRecord->update([
            'ultimo_acceso_at' => Carbon::now(),
            'ip_ultimo_acceso' => $request->ip(),
        ]);

        return response()->json([
            'ok'     => true,
            'online' => true,
        ]);
    }

    /**
     * GET /admin/riiss/auditoria/tokens
     * Lista de accesos temporales y estado de conexión en vivo.
     */
    public function listarTokens(Request $request): JsonResponse
    {
        $tokens = RiissAuditoriaToken::with(['establecimiento', 'creador'])
            ->orderBy('created_at', 'desc')
            ->get();

        $onlineCount = 0;
        $activosCount = 0;
        $expiradosCount = 0;

        $items = $tokens->map(function ($t) use (&$onlineCount, &$activosCount, &$expiradosCount) {
            $isOnline = $t->isOnline();
            $isExpirado = $t->isExpirado();
            $estadoAuditor = $t->estado_auditor;

            if ($isOnline) {
                $onlineCount++;
            }
            if (!$isExpirado && $t->estado === 'activo') {
                $activosCount++;
            } else {
                $expiradosCount++;
            }

            $esGlobal = empty($t->establecimiento_id);
            $estNombre = $esGlobal
                ? '🌐 Toda la Red Nacional (RIISS)'
                : ($t->establecimiento ? $t->establecimiento->nombre_oficial : 'Establecimiento no encontrado');

            $urlPortal = route('riiss.portal-auditor.show', ['token' => $t->token]);
            $expiraTexto = $t->expira_en->format('d/m/Y H:i');

            // Mensaje para reenvío WhatsApp
            $msg = "🏥 *IPS - Portal de Auditoría y Verificación RIISS*\n";
            if ($esGlobal) {
                $msg .= "🌐 *Ámbito de Acceso:* *Toda la Red Nacional (Todos los Establecimientos)*\n\n";
            } else {
                $msg .= "🏛️ *Establecimiento:* " . $estNombre . "\n\n";
            }
            if ($t->destinatario) {
                $msg .= "Estimado/a *" . $t->destinatario . "*,\n";
            }
            $msg .= "Le compartimos el acceso exclusivo de solo lectura para la verificación y auditoría de la cartera de servicios, vademécum de medicamentos y datos técnicos de la red.\n\n";
            $msg .= "🔗 *Enlace de Acceso:*\n" . $urlPortal . "\n\n";
            $msg .= "🔑 *Código PIN de Seguridad:* *" . $t->pin . "*\n";
            $msg .= "⏳ *Validez:* " . $t->duracion_horas . " Horas (Vence el " . $expiraTexto . " hs)\n";

            $urlWhatsApp = "https://api.whatsapp.com/send?text=" . urlencode($msg);

            return [
                'id'                    => $t->id,
                'token'                 => $t->token,
                'pin'                   => $t->pin,
                'destinatario'          => $t->destinatario ?: 'Auditor Externo',
                'establecimiento_id'    => $t->establecimiento_id,
                'establecimiento_nombre'=> $estNombre,
                'es_global'             => $esGlobal,
                'duracion_horas'        => $t->duracion_horas,
                'expira_en_texto'       => $expiraTexto,
                'tiempo_restante_texto' => $t->tiempo_restante_texto,
                'is_expirado'           => $isExpirado,
                'is_online'             => $isOnline,
                'estado_auditor'        => $estadoAuditor,
                'visitas_count'         => $t->visitas_count,
                'ultimo_acceso_humano'  => $t->ultimo_acceso_humano,
                'ultimo_acceso_fecha'   => $t->ultimo_acceso_at ? $t->ultimo_acceso_at->format('d/m/Y H:i:s') : null,
                'ip_ultimo_acceso'      => $t->ip_ultimo_acceso ?: 'N/A',
                'creado_por_nombre'     => $t->creador ? $t->creador->name : 'Sistema',
                'created_at_texto'      => $t->created_at ? $t->created_at->format('d/m/Y H:i') : '',
                'url_portal'            => $urlPortal,
                'url_whatsapp'          => $urlWhatsApp,
                'mensaje_whatsapp'      => $msg,
            ];
        });

        return response()->json([
            'ok'              => true,
            'data'            => $items,
            'total_tokens'    => $tokens->count(),
            'online_count'    => $onlineCount,
            'activos_count'   => $activosCount,
            'expirados_count' => $expiradosCount,
        ]);
    }

    /**
     * POST /admin/riiss/auditoria/tokens/{id}/revocar
     */
    public function revocarToken(int $id): JsonResponse
    {
        $tokenRecord = RiissAuditoriaToken::findOrFail($id);
        $tokenRecord->update([
            'estado' => 'revocado',
        ]);

        return response()->json([
            'ok'      => true,
            'message' => 'Acceso revocado de inmediato.',
        ]);
    }

    /**
     * POST /admin/riiss/auditoria/tokens/{id}/extender
     */
    public function extenderToken(int $id, Request $request): JsonResponse
    {
        $tokenRecord = RiissAuditoriaToken::findOrFail($id);
        $horas = (int) $request->input('horas', 24);

        if ($tokenRecord->isExpirado() || $tokenRecord->estado === 'revocado') {
            $nuevaExpira = Carbon::now()->addHours($horas);
        } else {
            $nuevaExpira = $tokenRecord->expira_en->addHours($horas);
        }

        $tokenRecord->update([
            'expira_en'      => $nuevaExpira,
            'duracion_horas' => $tokenRecord->duracion_horas + $horas,
            'estado'         => 'activo',
        ]);

        return response()->json([
            'ok'               => true,
            'message'          => 'Vigencia extendida +' . $horas . ' horas.',
            'nueva_expiracion' => $nuevaExpira->format('d/m/Y H:i'),
            'tiempo_restante'  => $tokenRecord->tiempo_restante_texto,
        ]);
    }
}
