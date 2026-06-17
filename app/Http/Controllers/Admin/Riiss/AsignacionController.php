<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\Asignacion;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\Evaluacion;
use App\Models\User;
use App\Notifications\AsignacionEvaluacionNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsignacionController extends Controller
{
    /**
     * GET /riiss/asignaciones
     * Vista de gestión de asignaciones (admin).
     */
    public function index()
    {
        return view('admin.riiss.asignaciones.index');
    }

    /**
     * GET /riiss/asignaciones/datos
     * JSON para el listado con filtros.
     */
    public function datos(Request $request): JsonResponse
    {
        $query = Asignacion::with(['establecimiento', 'evaluador', 'asignadoPor'])
            ->orderByDesc('created_at');

        if ($request->filled('estado'))     $query->where('estado', $request->estado);
        if ($request->filled('evaluador'))  $query->where('evaluador_id', $request->evaluador);
        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->whereHas('establecimiento', fn($q) =>
                $q->where('nombre_oficial', 'ILIKE', "%{$b}%")
            );
        }

        $items = $query->paginate(20);
        $items->getCollection()->transform(fn($a) => $this->formatear($a));

        return response()->json(['ok' => true, 'data' => $items]);
    }

    /**
     * POST /riiss/asignaciones
     * Crear una nueva asignación y notificar al evaluador.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_establecimiento' => 'required|string|exists:establecimientos,id_establecimiento',
            'evaluador_id'       => 'required|integer|exists:users,id',
            'fecha_limite'       => 'nullable|date|after:today',
            'instrucciones'      => 'nullable|string|max:1000',
        ]);

        // Verificar que no exista ya una asignación activa para ese evaluador+establecimiento
        $existe = Asignacion::where('id_establecimiento', $validated['id_establecimiento'])
            ->where('evaluador_id', $validated['evaluador_id'])
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->exists();

        if ($existe) {
            return response()->json([
                'ok'      => false,
                'message' => 'Ya existe una asignación activa para ese evaluador en ese establecimiento.',
            ], 422);
        }

        $asignacion = Asignacion::create([
            'id_establecimiento' => $validated['id_establecimiento'],
            'evaluador_id'       => $validated['evaluador_id'],
            'asignado_por'       => Auth::id(),
            'fecha_limite'       => $validated['fecha_limite'] ?? null,
            'instrucciones'      => $validated['instrucciones'] ?? null,
            'estado'             => 'pendiente',
        ]);

        $asignacion->load(['establecimiento', 'evaluador', 'asignadoPor']);

        // Enviar notificación por email
        try {
            $evaluador = User::find($validated['evaluador_id']);
            $evaluador->notify(new AsignacionEvaluacionNotification($asignacion));
            $asignacion->update(['notificado_at' => now()]);
        } catch (\Exception $e) {
            // No fallar si el email no se envía
            \Log::warning('No se pudo enviar notificación de asignación: ' . $e->getMessage());
        }

        return response()->json([
            'ok'      => true,
            'data'    => $this->formatear($asignacion),
            'message' => 'Asignación creada y evaluador notificado.',
        ], 201);
    }

    /**
     * PATCH /riiss/asignaciones/{id}/estado
     * Cambiar estado de una asignación.
     */
    public function actualizarEstado(Request $request, Asignacion $asignacion): JsonResponse
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_progreso,completada,vencida,cancelada',
        ]);

        $asignacion->update(['estado' => $request->estado]);

        return response()->json(['ok' => true, 'message' => 'Estado actualizado.']);
    }

    /**
     * POST /riiss/asignaciones/{id}/renotificar
     * Reenviar el email de notificación.
     */
    public function renotificar(Asignacion $asignacion): JsonResponse
    {
        try {
            $asignacion->load(['establecimiento', 'evaluador', 'asignadoPor']);
            $asignacion->evaluador->notify(new AsignacionEvaluacionNotification($asignacion));
            $asignacion->update(['notificado_at' => now()]);
            return response()->json(['ok' => true, 'message' => 'Notificación reenviada.']);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Error al enviar: ' . $e->getMessage()], 500);
        }
    }

    /**
     * DELETE /riiss/asignaciones/{id}
     * Cancelar (soft delete) una asignación.
     */
    public function destroy(Asignacion $asignacion): JsonResponse
    {
        $asignacion->update(['estado' => 'cancelada']);
        $asignacion->delete();
        return response()->json(['ok' => true, 'message' => 'Asignación cancelada.']);
    }

    /**
     * GET /riiss/mis-asignaciones
     * Vista del evaluador — solo sus asignaciones.
     */
    public function misAsignaciones()
    {
        $asignaciones = Asignacion::with(['establecimiento', 'evaluacion', 'asignadoPor'])
            ->delEvaluador(Auth::id())
            ->pendientes()
            ->orderBy('fecha_limite')
            ->get()
            ->map(fn($a) => $this->formatear($a));

        return view('admin.riiss.asignaciones.mis_asignaciones', compact('asignaciones'));
    }

    private function formatear(Asignacion $a): array
    {
        // Buscar la evaluación más reciente del establecimiento
        $evaluacion = Evaluacion::where('id_establecimiento', $a->id_establecimiento)
            ->whereIn('estado', ['en_progreso', 'completada', 'borrador'])
            ->latest()
            ->first();

        return [
            'id'                 => $a->id,
            'establecimiento'    => $a->establecimiento->nombre_oficial ?? '—',
            'id_establecimiento' => $a->id_establecimiento,
            'tipologia'          => $a->establecimiento->tipologia_clasificacion ?? '—',
            'complejidad'        => $a->establecimiento->complejidad ?? '—',
            'complejidad_color'  => $a->establecimiento->complejidad_color ?? '#6b7280',
            'evaluador'          => $a->evaluador->name ?? '—',
            'evaluador_email'    => $a->evaluador->email ?? '—',
            'asignado_por'       => $a->asignadoPor->name ?? '—',
            'fecha_limite'       => $a->fecha_limite?->format('d/m/Y'),
            'fecha_limite_raw'   => $a->fecha_limite?->toDateString(),
            'vencida'            => $a->estaVencida(),
            'estado'             => $a->estado,
            'estado_color'       => $a->estado_color,
            'instrucciones'      => $a->instrucciones,
            'notificado_at'      => $a->notificado_at?->format('d/m/Y H:i'),
            'evaluacion_id'      => $evaluacion?->id,
            'evaluacion_progreso'=> $evaluacion?->porcentaje_cumplimiento,
            'evaluacion_estado'  => $evaluacion?->estado,
            'created_at'         => $a->created_at?->format('d/m/Y'),
        ];
    }
}
