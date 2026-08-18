<?php

namespace App\Http\Controllers\Admin\Soporte;

use App\Http\Controllers\Controller;
use App\Models\Soporte\SoporteTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoporteTicketController extends Controller
{
    /**
     * Guardar ticket de falla/incidencia desde cualquier vista (AJAX).
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'url_origen'  => 'nullable|string',
            'prioridad'   => 'nullable|in:baja,media,alta,urgente',
        ]);

        $url = $request->input('url_origen') ?? url()->previous();
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '/';

        $ticket = SoporteTicket::create([
            'codigo'         => SoporteTicket::generarCodigo(),
            'user_id'        => Auth::id(),
            'pei_profile_id' => $request->input('pei_profile_id'),
            'titulo'         => trim($request->input('titulo')),
            'descripcion'    => trim($request->input('descripcion')),
            'url_origen'     => $url,
            'ruta_origen'    => $path,
            'nodo_contexto'  => $request->input('nodo_contexto'),
            'prioridad'      => $request->input('prioridad', 'media'),
            'estado'         => 'pendiente',
        ]);

        // Notificación en tiempo real a Redis
        try {
            \Illuminate\Support\Facades\Redis::publish('soporte:ticket:creado', json_encode([
                'id'         => $ticket->id,
                'codigo'     => $ticket->codigo,
                'titulo'     => $ticket->titulo,
                'prioridad'  => $ticket->prioridad,
                'user_name'  => Auth::user()->name ?? 'Usuario',
                'url_origen' => $ticket->url_origen,
                'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
            ]));
        } catch (\Exception $e) {
            // Manejo silencioso en caso de Redis sin servicio
        }

        return response()->json([
            'success' => true,
            'message' => '¡Ticket ' . $ticket->codigo . ' registrado exitosamente! El administrador lo atenderá a la brevedad.',
            'ticket'  => $ticket,
        ]);
    }

    /**
     * Bandeja de Tickets para Administradores.
     */
    public function index(Request $request)
    {
        $query = SoporteTicket::with(['user', 'resolver', 'peiProfile'])->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function($sub) use ($q) {
                $sub->where('codigo', 'ILIKE', "%{$q}%")
                    ->orWhere('titulo', 'ILIKE', "%{$q}%")
                    ->orWhere('descripcion', 'ILIKE', "%{$q}%")
                    ->orWhereHas('user', function($u) use ($q) {
                        $u->where('name', 'ILIKE', "%{$q}%")->orWhere('email', 'ILIKE', "%{$q}%");
                    });
            });
        }

        $tickets = $query->paginate(20);

        $counts = [
            'total'      => SoporteTicket::count(),
            'pendiente'  => SoporteTicket::where('estado', 'pendiente')->count(),
            'en_proceso' => SoporteTicket::where('estado', 'en_proceso')->count(),
            'resuelto'   => SoporteTicket::where('estado', 'resuelto')->count(),
            'rechazado'  => SoporteTicket::where('estado', 'rechazado')->count(),
        ];

        return view('admin.soporte.tickets.index', compact('tickets', 'counts'));
    }

    /**
     * Actualizar estado o responder a un ticket.
     */
    public function updateStatus(Request $request, SoporteTicket $ticket)
    {
        $request->validate([
            'estado'          => 'required|in:pendiente,en_proceso,resuelto,rechazado',
            'respuesta_admin' => 'nullable|string',
        ]);

        $ticket->estado = $request->estado;
        if ($request->has('respuesta_admin')) {
            $ticket->respuesta_admin = trim($request->respuesta_admin);
        }

        if (in_array($request->estado, ['resuelto', 'rechazado'])) {
            $ticket->resolved_at = now();
            $ticket->resolved_by = Auth::id();
        } else {
            $ticket->resolved_at = null;
            $ticket->resolved_by = null;
        }

        $ticket->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado del ticket ' . $ticket->codigo . ' actualizado a ' . strtoupper($ticket->estado),
                'ticket'  => $ticket,
            ]);
        }

        return redirect()->back()->with('info', 'Ticket ' . $ticket->codigo . ' actualizado correctamente.');
    }

    /**
     * Eliminar un ticket.
     */
    public function destroy(SoporteTicket $ticket)
    {
        $ticket->delete();
        return response()->json(['success' => true, 'message' => 'Ticket eliminado.']);
    }

    /**
     * Conteo de tickets pendientes (para badges en header/navbar).
     */
    public function countPending()
    {
        return response()->json([
            'pending' => SoporteTicket::where('estado', 'pendiente')->count(),
        ]);
    }
}
