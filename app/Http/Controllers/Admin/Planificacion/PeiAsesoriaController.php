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

        return response()->json(['ok' => true, 'message' => 'Dictamen de Validación completado con éxito. ¡Gracias por sus sugerencias!']);
    }
}
