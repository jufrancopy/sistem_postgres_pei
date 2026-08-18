<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\Planificacion\PeiAsesoria;
use App\Models\Planificacion\PeiAsesoriaComentario;

class PeiAsesoriaController extends Controller
{
    // ── Convocatoria por el Administrador ────────────────────────────────────
    public function convocarStore(Request $request, $profileId)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'institucion' => 'nullable|string|max:255',
        ]);

        $profile = PeiProfile::findOrFail($profileId);

        $codigo = PeiAsesoria::generarCodigo();

        $asesoria = PeiAsesoria::create([
            'pei_profile_id' => $profile->id,
            'nombre'         => trim($request->input('nombre')),
            'email'          => strtolower(trim($request->input('email'))),
            'institucion'    => trim($request->input('institucion') ?? ''),
            'codigo_acceso'  => $codigo,
            'estado'         => 'PENDIENTE',
        ]);

        return response()->json([
            'ok'      => true,
            'message' => 'Asesor Externo convocado exitosamente.',
            'data'    => [
                'id'            => $asesoria->id,
                'nombre'        => $asesoria->nombre,
                'email'         => $asesoria->email,
                'codigo_acceso' => $asesoria->codigo_acceso,
                'login_url'     => route('asesoria.public.login'),
            ]
        ]);
    }

    public function listarAsesorias($profileId)
    {
        $asesorias = PeiAsesoria::where('pei_profile_id', $profileId)
            ->withCount('comentarios')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['ok' => true, 'asesorias' => $asesorias]);
    }

    // ── Login Público del Asesor (Sin Auth) ──────────────────────────────────
    public function loginForm()
    {
        return view('public.asesoria.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string',
        ]);

        $email = strtolower(trim($request->input('email')));
        $code  = strtoupper(trim($request->input('code')));

        $asesoria = PeiAsesoria::where('email', $email)
            ->where('codigo_acceso', $code)
            ->first();

        if (!$asesoria) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['credentials' => 'El correo o el código único de asesoría son incorrectos. Verificá los datos e intentá de nuevo.']);
        }

        if ($asesoria->estado === 'PENDIENTE') {
            $asesoria->update(['estado' => 'EN_REVISION']);
        }

        session(['asesor_session_id' => $asesoria->id]);

        return redirect()->route('asesoria.public.portal', $asesoria->id);
    }

    public function logout()
    {
        session()->forget('asesor_session_id');
        return redirect()->route('asesoria.public.login')->with('info', 'Sesión de Asesoría finalizada.');
    }

    // ── Portal Público del Asesor (Sin Sidebar) ──────────────────────────────
    public function showPortal($id)
    {
        $asesoria = PeiAsesoria::with(['comentarios', 'profile'])->findOrFail($id);

        // Verificar sesión activa
        if (session('asesor_session_id') != $asesoria->id && !auth()->check()) {
            return redirect()->route('asesoria.public.login')->with('warning', 'Ingresá tu correo y código único para acceder al portal.');
        }

        $profile = $asesoria->profile;
        if (!$profile) {
            abort(404, 'Perfil PEI no encontrado.');
        }

        $descendants = $profile->descendants()->get();
        $treeNodes   = $descendants->toTree();

        // Mapear Acciones Operativas de Mejora Continua
        $nodeIds = $descendants->pluck('id')->push($profile->id)->map(fn($v) => (string)$v)->toArray();
        $stringIds = array_values(array_filter($nodeIds, fn($v) => !is_numeric($v)));
        $numericIds = array_values(array_filter($nodeIds, 'is_numeric'));

        $query = \App\Models\PlanMaestro\PlanAccion::query();
        if (!empty($stringIds)) {
            $query->whereIn('pei_profile_id', $stringIds);
        }
        if (!empty($numericIds)) {
            $query->orWhereIn('plan_id', $numericIds)->orWhereIn('eje_id', $numericIds);
        }
        $iniciativas = $query->orderBy('orden')->get();

        // Comentarios existentes por nodo
        $comentariosMap = $asesoria->comentarios->pluck('comentario', 'node_id')->toArray();

        return view('public.asesoria.portal', compact('asesoria', 'profile', 'treeNodes', 'descendants', 'iniciativas', 'comentariosMap'));
    }

    public function guardarComentario(Request $request, $id)
    {
        $asesoria = PeiAsesoria::findOrFail($id);

        $request->validate([
            'node_id'    => 'required',
            'comentario' => 'nullable|string',
            'node_type'  => 'nullable|string',
        ]);

        $nodeId = $request->input('node_id');
        $comentario = trim($request->input('comentario') ?? '');
        $nodeType = $request->input('node_type', 'node');

        if (empty($comentario)) {
            PeiAsesoriaComentario::where('pei_asesoria_id', $asesoria->id)
                ->where('node_id', (string)$nodeId)
                ->delete();

            return response()->json(['ok' => true, 'message' => 'Comentario eliminado.', 'has_comment' => false]);
        }

        $com = PeiAsesoriaComentario::updateOrCreate(
            [
                'pei_asesoria_id' => $asesoria->id,
                'node_id'         => (string)$nodeId,
            ],
            [
                'node_type'  => $nodeType,
                'comentario' => $comentario,
            ]
        );

        if ($asesoria->estado === 'PENDIENTE') {
            $asesoria->update(['estado' => 'EN_REVISION']);
        }

        $this->notificarSistemas($asesoria, 'sugerencia.guardada', [
            'node_id'    => $nodeId,
            'node_type'  => $nodeType,
            'comentario' => $comentario
        ]);

        return response()->json(['ok' => true, 'message' => 'Sugerencia guardada correctamente.', 'has_comment' => true]);
    }

    public function finalizarDictamen(Request $request, $id)
    {
        $asesoria = PeiAsesoria::findOrFail($id);

        $request->validate([
            'dictamen_general' => 'nullable|string',
        ]);

        $asesoria->update([
            'dictamen_general' => trim($request->input('dictamen_general') ?? ''),
            'estado'           => 'COMPLETADO',
        ]);

        $this->notificarSistemas($asesoria, 'dictamen.finalizado', [
            'dictamen_general' => $asesoria->dictamen_general
        ]);

        return response()->json(['ok' => true, 'message' => 'Dictamen de Validación completado con éxito. ¡Gracias por sus sugerencias!']);
    }

    /**
     * Publica notificación en tiempo real a Redis y crea registros en SystemNotification para la campanita del nav.
     */
    private function notificarSistemas(PeiAsesoria $asesoria, string $tipoEvento, array $extra = [])
    {
        // 1. Redis Publish
        try {
            \Illuminate\Support\Facades\Redis::publish('canal-asesoria-pei', json_encode(array_merge([
                'event'          => $tipoEvento,
                'pei_profile_id' => (string)$asesoria->pei_profile_id,
                'asesor_id'      => $asesoria->id,
                'asesor_nombre'  => $asesoria->nombre,
                'asesor_email'   => $asesoria->email,
                'institucion'    => $asesoria->institucion,
                'estado'         => $asesoria->estado,
                'dictamen'       => $asesoria->dictamen_general,
                'timestamp'      => now()->toIso8601String(),
                'mensaje'        => "Actualización de Asesoría por {$asesoria->nombre}: " . ($tipoEvento === 'dictamen.finalizado' ? 'Dictamen Macro Finalizado' : 'Sugerencia Guardada')
            ], $extra)));
        } catch (\Throwable $e) {
            // Ignorar limpiamente si Redis no está activo
        }

        // 2. SystemNotification para la campanita de Administradores y Coordinadores
        try {
            $profile = PeiProfile::find($asesoria->pei_profile_id);
            $peiNombre = $profile ? strip_tags($profile->name) : 'PEI';

            $titulo = ($tipoEvento === 'dictamen.finalizado')
                ? "📋 Dictamen Finalizado de Asesoría Técnica"
                : "💬 Nueva Sugerencia de Asesor Externo";

            $instStr = $asesoria->institucion ? " ({$asesoria->institucion})" : "";
            $mensaje = ($tipoEvento === 'dictamen.finalizado')
                ? "El Asesor <b>{$asesoria->nombre}</b>{$instStr} completó el Dictamen General para <i>{$peiNombre}</i>."
                : "El Asesor <b>{$asesoria->nombre}</b>{$instStr} registró observaciones técnicas en el plan <i>{$peiNombre}</i>.";

            $url = route('pei-profiles.show', $asesoria->pei_profile_id) . '?reporte_aportes=1';

            $targetRoles = ['Administrador', 'Coordinador de Planificación', 'Coordinación de Planificación'];
            $userIds = \App\Models\User::whereHas('roles', function($q) use ($targetRoles) {
                $q->whereIn('name', $targetRoles);
            })->pluck('id')->unique();

            foreach ($userIds as $uid) {
                \App\Models\SystemNotification::create([
                    'user_id' => $uid,
                    'tipo'    => 'asesoria_tecnica',
                    'titulo'  => $titulo,
                    'mensaje' => $mensaje,
                    'icono'   => 'fa-user-check text-warning',
                    'url'     => $url,
                    'leida'   => false,
                ]);
            }
        } catch (\Throwable $e) {
            // Ignorar errores no críticos
        }
    }

    /**
     * Genera la vista/HTML de lectura cómoda de aportes y dictámenes de asesorías.
     */
    public function reporteAportes($profileId)
    {
        if (!auth()->user() || !auth()->user()->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador de Planificación', 'Coordinación de Planificación', 'Analista de Planificación', 'Analista PEI'])) {
            abort(403, 'Acceso restringido.');
        }

        $profile = PeiProfile::findOrFail($profileId);

        $asesorias = PeiAsesoria::where('pei_profile_id', $profileId)
            ->with(['comentarios'])
            ->orderBy('created_at', 'desc')
            ->get();

        $descendants = $profile->descendants()->get();

        $nodeIds = $descendants->pluck('id')->push($profile->id)->map(fn($v) => (string)$v)->toArray();
        $stringIds = array_values(array_filter($nodeIds, fn($v) => !is_numeric($v)));
        $numericIds = array_values(array_filter($nodeIds, 'is_numeric'));

        $query = \App\Models\PlanMaestro\PlanAccion::query();
        if (!empty($stringIds)) {
            $query->whereIn('pei_profile_id', $stringIds);
        }
        if (!empty($numericIds)) {
            $query->orWhereIn('plan_id', $numericIds)->orWhereIn('eje_id', $numericIds);
        }
        $iniciativas = $query->orderBy('orden')->get();

        $nodosMap = $descendants->keyBy('id');
        $nodosMap[$profile->id] = $profile;
        $iniciativasMap = $iniciativas->keyBy('id');

        return view('admin.planificacion.peis.peis.reporte_aportes', compact(
            'profile',
            'asesorias',
            'descendants',
            'iniciativas',
            'nodosMap',
            'iniciativasMap'
        ));
    }

    /**
     * Marcar un aporte como INTEGRADO y notificar por correo al aportante.
     */
    public function integrarAporte(Request $request, $commentId)
    {
        try {
            if (!auth()->user() || !auth()->user()->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador de Planificación', 'Coordinación de Planificación', 'Analista de Planificación', 'Analista PEI'])) {
                return response()->json(['success' => false, 'message' => 'Acción restringida.'], 403);
            }

            $comentario = PeiAsesoriaComentario::with('asesoria')->find($commentId);
            if (!$comentario) {
                return response()->json(['success' => false, 'message' => 'Comentario ID ' . $commentId . ' no encontrado en el sistema.'], 404);
            }

            $comentario->estado = 'INTEGRADO';
            $comentario->integrated_at = now();
            $comentario->save();

            $asesoria = $comentario->asesoria;
            $emailEnviado = false;

            if ($asesoria && filter_var($asesoria->email, FILTER_VALIDATE_EMAIL)) {
                $destinatario = $asesoria->email;
                $nombreAsesor = $asesoria->nombre ?? 'Asesor Técnico';
                $textoComentario = $comentario->comentario;

                // Cargar variables globales institucionales desde HomeConfiguration
                $sysSiteName = \App\Models\HomeConfiguration::getSetting('site_name', 'Sistema de Planificación Estratégica Institucional (PEI)');
                $sysFooter   = \App\Models\HomeConfiguration::getSetting('footer_text', '© 2026 Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.');
                $sysEmail    = \App\Models\HomeConfiguration::getSetting('contact_email', 'planificacion@ips.gov.py');
                $sysLogoRaw  = \App\Models\HomeConfiguration::getSetting('logo_url');

                if (!empty($sysLogoRaw)) {
                    $sysLogoUrl = (str_starts_with($sysLogoRaw, 'http://') || str_starts_with($sysLogoRaw, 'https://')) ? $sysLogoRaw : url($sysLogoRaw);
                } else {
                    $sysLogoUrl = asset('material/img/new_logo.png');
                }

                $logoHtml = !empty($sysLogoUrl) 
                    ? "<div style='margin-bottom: 16px;'><img src='" . e($sysLogoUrl) . "' alt='" . e($sysSiteName) . "' style='max-height: 65px; max-width: 220px; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));'></div>"
                    : "";

                $contactoHtml = !empty($sysEmail)
                    ? "<div style='margin-top: 6px; font-size: 11.5px;'>Contacto: <a href='mailto:" . e($sysEmail) . "' style='color: #4f46e5; text-decoration: none; font-weight: 600;'>" . e($sysEmail) . "</a></div>"
                    : "";

                $htmlEmail = "
                    <div style='font-family: \"Segoe UI\", Helvetica, Arial, sans-serif; color: #1e293b; max-width: 620px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; background-color: #ffffff; box-shadow: 0 10px 25px rgba(0,0,0,0.08);'>
                        <div style='background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%); padding: 32px 24px; text-align: center; border-bottom: 3px solid #6366f1;'>
                            {$logoHtml}
                            <h2 style='margin: 0; font-size: 21px; font-weight: 700; color: #ffffff; letter-spacing: -0.3px; line-height: 1.3;'>
                                Notificación de Integración de Aporte Técnico
                            </h2>
                            <p style='margin: 8px 0 0 0; font-size: 13px; color: #cbd5e1; font-weight: 500;'>
                                " . e($sysSiteName) . "
                            </p>
                        </div>
                        <div style='padding: 32px 28px; background-color: #ffffff;'>
                            <p style='font-size: 16px; margin-top: 0; color: #0f172a;'>
                                Estimado/a <strong>" . e($nombreAsesor) . "</strong>,
                            </p>
                            <p style='font-size: 14.5px; line-height: 1.65; color: #334155; margin-bottom: 24px;'>
                                Nos complace informarle que su recomendación y aporte técnico sobre el <strong>Plan Estratégico Institucional (PEI)</strong> ha sido <span style='background-color: #dcfce7; color: #166534; font-weight: 700; padding: 2px 8px; border-radius: 6px; border: 1px solid #bbf7d0; font-size: 13px;'>✓ INTEGRADO EXITOSAMENTE</span> en la plataforma por la Dirección de Planificación.
                            </p>
                            
                            <div style='background: #f8fafc; border-left: 4px solid #6366f1; padding: 20px; margin: 24px 0; border-radius: 8px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);'>
                                <strong style='display: block; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px;'>
                                    💬 SU APORTE TÉCNICO REGISTRADO:
                                </strong>
                                <div style='font-size: 14.5px; color: #0f172a; line-height: 1.6; font-style: italic; white-space: pre-wrap;'>
                                    \"" . e($textoComentario) . "\"
                                </div>
                            </div>

                            <p style='font-size: 14px; line-height: 1.65; color: #334155;'>
                                La <strong>Dirección de Planificación y su Equipo Técnico</strong> procederán a analizar su propuesta en las mesas de trabajo institucionales para incorporarla formalmente en la matriz de objetivos, metas y acciones estratégicas de la institución.
                            </p>
                            <p style='font-size: 14px; line-height: 1.6; color: #334155; margin-bottom: 0;'>
                                Agradecemos sinceramente su valioso compromiso y contribución para el fortalecimiento institucional.
                            </p>
                        </div>
                        <div style='background-color: #f1f5f9; padding: 20px 24px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; line-height: 1.5;'>
                            <strong style='color: #334155; font-size: 13px; display: block; margin-bottom: 4px;'>" . e($sysSiteName) . "</strong>
                            <div>" . e($sysFooter) . "</div>
                            {$contactoHtml}
                        </div>
                    </div>
                ";

                try {
                    \Illuminate\Support\Facades\Mail::html($htmlEmail, function ($message) use ($destinatario, $sysSiteName) {
                        $message->to($destinatario)
                                ->subject("Su aporte al PEI ha sido integrado para análisis técnico — " . $sysSiteName);
                    });
                    $emailEnviado = true;
                } catch (\Throwable $me) {
                    \Illuminate\Support\Facades\Log::error("Fallo al enviar correo de integración a asesor ({$destinatario}): " . $me->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => $emailEnviado 
                    ? 'El aporte ha sido integrado y se notificó por correo al aportante.' 
                    : 'El aporte ha sido marcado como integrado exitosamente.',
                'estado' => 'INTEGRADO',
                'fecha' => $comentario->integrated_at ? $comentario->integrated_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i')
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error al integrar aporte: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un comentario / aporte de asesoría.
     */
    public function eliminarAporte(Request $request, $commentId)
    {
        try {
            if (!auth()->user() || !auth()->user()->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador de Planificación', 'Coordinación de Planificación', 'Analista de Planificación', 'Analista PEI'])) {
                return response()->json(['success' => false, 'message' => 'Acción restringida.'], 403);
            }

            $comentario = PeiAsesoriaComentario::find($commentId);
            if (!$comentario) {
                return response()->json(['success' => false, 'message' => 'El comentario no fue encontrado o ya fue eliminado.'], 404);
            }

            $comentario->delete();

            return response()->json([
                'success' => true,
                'message' => 'El aporte ha sido eliminado correctamente.'
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error al eliminar aporte: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar el aporte: ' . $e->getMessage()], 500);
        }
    }
}
