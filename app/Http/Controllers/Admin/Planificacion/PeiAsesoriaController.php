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

                $htmlEmail = "
                    <div style='font-family: Arial, sans-serif; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>
                        <div style='background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #ffffff; padding: 24px; text-align: center;'>
                            <h2 style='margin: 0; font-size: 20px; font-weight: bold;'>Notificación de Integración de Aporte</h2>
                            <p style='margin: 6px 0 0 0; font-size: 13px; opacity: 0.9;'>Sistema de Planificación Estratégica Institucional (PEI)</p>
                        </div>
                        <div style='padding: 24px; background-color: #ffffff;'>
                            <p style='font-size: 15px; margin-top: 0;'>Estimado/a <strong>{$nombreAsesor}</strong>,</p>
                            <p style='font-size: 14px; line-height: 1.6; color: #334155;'>
                                Nos complace informarle que su aporte y recomendación técnica sobre el Plan Estratégico Institucional (PEI) ha sido <strong>INTEGRADO exitosamente</strong> en el sistema por el equipo de planificación.
                            </p>
                            
                            <div style='background: #f8fafc; border-left: 4px solid #6366f1; padding: 16px; margin: 20px 0; border-radius: 6px;'>
                                <strong style='display: block; font-size: 12px; color: #64748b; text-transform: uppercase; margin-bottom: 6px;'>Su Aporte Registrado:</strong>
                                <em style='font-size: 14px; color: #1e293b; line-height: 1.5;'>\"" . e($textoComentario) . "\"</em>
                            </div>

                            <p style='font-size: 14px; line-height: 1.6; color: #334155;'>
                                La <strong>Dirección de Planificación y su Equipo Técnico</strong> procederán a analizar su sugerencia para incorporarla formalmente en el diseño de las metas y acciones estratégicas de la institución.
                            </p>
                            <p style='font-size: 14px; color: #334155;'>Agradecemos valiosamente su compromiso y valiosa contribución técnica.</p>
                        </div>
                        <div style='background: #f1f5f9; padding: 16px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b;'>
                            <strong>Dirección de Planificación y Equipo Técnico Institucional</strong><br>
                            Sistema de Gestión PEI / Paraguay
                        </div>
                    </div>
                ";

                try {
                    \Illuminate\Support\Facades\Mail::html($htmlEmail, function ($message) use ($destinatario) {
                        $message->to($destinatario)
                                ->subject("Su aporte al PEI ha sido integrado para análisis técnico — Dirección de Planificación");
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
}
