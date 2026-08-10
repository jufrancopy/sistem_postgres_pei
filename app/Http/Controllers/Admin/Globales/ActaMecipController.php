<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use App\Admin\Globales\ActivityTask;
use App\Admin\Globales\ActivityTaskActa;
use App\Admin\Globales\ActivityTaskActaParticipante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ActaMecipController extends Controller
{
    /**
     * Obtiene los datos del Acta MECIP para una tarea/reunión.
     * Si no existe, genera los valores por defecto iniciales.
     */
    public function getActa(int $taskId)
    {
        $task = ActivityTask::with(['activity', 'assignedTo', 'acta.participantes'])->findOrFail($taskId);

        $acta = $task->acta;

        if (!$acta) {
            $convocadosDefault = [];
            if ($task->assignedTo) {
                $convocadosDefault[] = "- {$task->assignedTo->name}";
            }

            $acta = ActivityTaskActa::create([
                'activity_task_id' => $task->id,
                'uuid'             => (string) Str::uuid(),
                'numero_acta'      => 'Acta ' . $task->id . '/' . date('Y'),
                'institucion'      => 'INSTITUTO DE PREVISIÓN SOCIAL',
                'dependencia'      => 'CENTRO DE ENSEÑANZA, DOCUMENTACIÓN Y ESTUDIOS DE LA SEGURIDAD SOCIAL - CEDESS',
                'lugar'            => 'REUNIÓN VIRTUAL',
                'fecha'            => $task->fecha_inicio ? $task->fecha_inicio->format('Y-m-d') : date('Y-m-d'),
                'hora_desde'       => '15:00',
                'hora_hasta'       => '16:00',
                'convocados_texto' => implode("\n", $convocadosDefault),
                'temas_tratar'     => $task->title . ($task->details ? "\n" . $task->details : ''),
                'objetivo'         => 'La reunión tuvo por objeto coordinar las acciones necesarias para el cumplimiento de las metas institucionales.',
                'desarrollo'       => "1. Apertura y verificación del quórum\nSe dio la bienvenida a los participantes y se dio inicio a la sesión técnica.\n\n2. Análisis de los puntos del orden del día\nSe revisaron los avances y compromisos correspondientes.\n",
                'acuerdos'         => "- Se acordó validar la consistencia metodológica de las actividades acordadas.\n- Remitir el borrador del acta a los participantes para su conocimiento.",
                'compromisos'      => [],
                'estado'           => 'borrador',
                'created_by'       => Auth::id(),
                'updated_by'       => Auth::id(),
            ]);
            $acta->load('participantes');
        }

        $logoUrl = $acta->logo_url;
        if (empty($logoUrl)) {
            $peiProfile = $task->activity?->peiProfile;
            if ($peiProfile && $peiProfile->parameters) {
                $params = json_decode($peiProfile->parameters, true);
                if (is_array($params) && !empty($params['acta_logo_url'])) {
                    $logoUrl = $params['acta_logo_url'];
                }
            }
        }

        $actaData = [
            'id'               => $acta->id,
            'activity_task_id' => $acta->activity_task_id,
            'uuid'             => $acta->uuid,
            'numero_acta'      => $acta->numero_acta,
            'logo_url'         => $logoUrl,
            'institucion'      => $acta->institucion ?? 'INSTITUTO DE PREVISIÓN SOCIAL',
            'dependencia'      => $acta->dependencia ?? 'CENTRO DE ENSEÑANZA, DOCUMENTACIÓN Y ESTUDIOS DE LA SEGURIDAD SOCIAL - CEDESS',
            'lugar'            => $acta->lugar ?? 'REUNIÓN VIRTUAL',
            'fecha'            => $acta->fecha ? $acta->fecha->format('Y-m-d') : date('Y-m-d'),
            'hora_desde'       => $acta->hora_desde,
            'hora_hasta'       => $acta->hora_hasta,
            'convocados_texto' => $acta->convocados_texto,
            'temas_tratar'     => $acta->temas_tratar,
            'objetivo'         => $acta->objetivo,
            'desarrollo'       => $acta->desarrollo,
            'acuerdos'         => $acta->acuerdos,
            'compromisos'      => $acta->compromisos ?? [],
            'estado'           => $acta->estado ?? 'borrador',
            'participantes'    => $acta->participantes->map(fn($p) => [
                'id'                => $p->id,
                'nombre'            => $p->nombre,
                'apellido'          => $p->apellido,
                'nombre_completo'   => $p->nombre_completo,
                'correo'            => $p->correo,
                'dependencia'       => $p->dependencia,
                'cargo'             => $p->cargo,
                'telefono'          => $p->telefono,
                'registrado_via_qr' => $p->registrado_via_qr,
                'created_at'        => $p->created_at?->format('d/m/Y H:i'),
            ]),
            'is_new'           => false,
        ];

        $publicUrl = route('actas.public.show', $actaData['uuid']);
        $qrSvg     = (string) QrCode::size(220)->margin(1)->generate($publicUrl);

        return response()->json([
            'ok'        => true,
            'success'   => true,
            'task'      => [
                'id'           => $task->id,
                'title'        => $task->title,
                'activity_id'  => $task->activity_id,
                'activity_name'=> $task->activity?->name ?? 'Actividad',
            ],
            'acta'      => $actaData,
            'public_url'=> $publicUrl,
            'qr_svg'    => $qrSvg,
        ]);
    }

    /**
     * Guarda o actualiza el Acta MECIP de una reunión.
     */
    public function storeOrUpdate(Request $request, int $taskId)
    {
        $task = ActivityTask::with('acta')->findOrFail($taskId);

        $validated = $request->validate([
            'institucion'      => 'nullable|string|max:255',
            'dependencia'      => 'nullable|string|max:255',
            'numero_acta'      => 'nullable|string|max:100',
            'logo_url'         => 'nullable|string|max:500',
            'lugar'            => 'nullable|string|max:255',
            'fecha'            => 'nullable|date',
            'hora_desde'       => 'nullable|string|max:10',
            'hora_hasta'       => 'nullable|string|max:10',
            'convocados_texto' => 'nullable|string',
            'temas_tratar'     => 'nullable|string',
            'objetivo'         => 'nullable|string',
            'desarrollo'       => 'nullable|string',
            'acuerdos'         => 'nullable|string',
            'compromisos'      => 'nullable',
            'estado'           => 'nullable|in:borrador,finalizada',
        ]);

        $compromisos = $validated['compromisos'] ?? [];
        if (is_string($compromisos)) {
            $compromisos = json_decode($compromisos, true) ?? [];
        }

        $acta = $task->acta;
        if (!$acta) {
            $acta = new ActivityTaskActa();
            $acta->activity_task_id = $task->id;
            $acta->uuid             = (string) Str::uuid();
            $acta->created_by       = Auth::id();
        }

        $acta->institucion      = $validated['institucion'] ?? 'INSTITUTO DE PREVISIÓN SOCIAL';
        $acta->dependencia      = $validated['dependencia'] ?? 'CENTRO DE ENSEÑANZA, DOCUMENTACIÓN Y ESTUDIOS DE LA SEGURIDAD SOCIAL - CEDESS';
        $acta->numero_acta      = $validated['numero_acta'] ?? ('Acta ' . $task->id . '/' . date('Y'));
        $acta->logo_url         = $validated['logo_url'] ?? null;
        $acta->lugar            = $validated['lugar'] ?? 'REUNIÓN VIRTUAL';
        $acta->fecha            = $validated['fecha'] ?? ($task->fecha_inicio ?? now());
        $acta->hora_desde       = $validated['hora_desde'] ?? '15:00';
        $acta->hora_hasta       = $validated['hora_hasta'] ?? '16:00';
        $acta->convocados_texto = $validated['convocados_texto'] ?? null;
        $acta->temas_tratar     = $validated['temas_tratar'] ?? null;
        $acta->objetivo         = $validated['objetivo'] ?? null;
        $acta->desarrollo       = $validated['desarrollo'] ?? null;
        $acta->acuerdos         = $validated['acuerdos'] ?? null;
        $acta->compromisos      = $compromisos;
        $acta->estado           = $validated['estado'] ?? ($acta->estado ?? 'borrador');
        $acta->updated_by       = Auth::id();
        $acta->save();

        $publicUrl = route('actas.public.show', $acta->uuid);
        $qrSvg     = (string) QrCode::size(220)->margin(1)->generate($publicUrl);

        return response()->json([
            'ok'        => true,
            'success'   => true,
            'message'   => 'Acta MECIP guardada exitosamente.',
            'acta'      => [
                'id'          => $acta->id,
                'uuid'        => $acta->uuid,
                'numero_acta' => $acta->numero_acta,
                'estado'      => $acta->estado,
            ],
            'public_url'=> $publicUrl,
            'qr_svg'    => $qrSvg,
        ]);
    }

    /**
     * Finaliza la reunión y oficializa el Acta MECIP.
     */
    public function finalizar(Request $request, int $taskId)
    {
        $task = ActivityTask::with('acta')->findOrFail($taskId);
        $acta = $task->acta;
        
        $firmaModerador = $request->input('firma_moderador');
        
        // Generar Hash de Seguridad Criptográfico (SHA-256 truncado para legibilidad)
        $dataToHash = ($acta ? $acta->uuid : Str::uuid()) . '-' . $taskId . '-' . now()->timestamp;
        $hashSeguridad = 'MECIP-' . date('Y') . '-' . strtoupper(substr(hash('sha256', $dataToHash), 0, 10));

        if (!$acta) {
            $acta = ActivityTaskActa::create([
                'activity_task_id'       => $task->id,
                'uuid'                   => (string) Str::uuid(),
                'numero_acta'            => 'Acta ' . $task->id . '/' . date('Y'),
                'institucion'            => 'INSTITUTO DE PREVISIÓN SOCIAL',
                'dependencia'            => 'CENTRO DE ENSEÑANZA, DOCUMENTACIÓN Y ESTUDIOS DE LA SEGURIDAD SOCIAL - CEDESS',
                'lugar'                  => 'REUNIÓN VIRTUAL',
                'fecha'                  => $task->fecha_inicio ?? now(),
                'estado'                 => 'finalizada',
                'hash_seguridad'         => $hashSeguridad,
                'firma_moderador'        => $firmaModerador,
                'fecha_firma_moderador'  => now(),
                'created_by'             => Auth::id(),
                'updated_by'             => Auth::id(),
            ]);
        } else {
            $acta->estado                = 'finalizada';
            $acta->hash_seguridad        = $hashSeguridad;
            $acta->firma_moderador       = $firmaModerador;
            $acta->fecha_firma_moderador = now();
            $acta->updated_by            = Auth::id();
            $acta->save();
        }

        // Si la tarea aún no está en estado Finalizada (2), podemos opcionalmente sincronizarla
        if ($task->status !== 2) {
            $task->status = 2;
            $task->save();
        }

        return response()->json([
            'ok'      => true,
            'success' => true,
            'message' => 'Reunión finalizada y Acta MECIP oficializada.',
            'acta'    => [
                'id'     => $acta->id,
                'uuid'   => $acta->uuid,
                'estado' => $acta->estado,
            ]
        ]);
    }

    /**
     * Agrega un participante manualmente desde el panel de gestión.
     */
    public function addParticipante(Request $request, int $taskId)
    {
        $task = ActivityTask::with('acta')->findOrFail($taskId);
        $acta = $task->acta;

        if (!$acta) {
            $acta = ActivityTaskActa::create([
                'activity_task_id' => $task->id,
                'uuid'             => (string) Str::uuid(),
                'numero_acta'      => 'Acta ' . $task->id . '/' . date('Y'),
                'institucion'      => 'INSTITUTO DE PREVISIÓN SOCIAL',
                'dependencia'      => 'CENTRO DE ENSEÑANZA, DOCUMENTACIÓN Y ESTUDIOS DE LA SEGURIDAD SOCIAL - CEDESS',
                'lugar'            => 'REUNIÓN VIRTUAL',
                'fecha'            => $task->fecha_inicio ?? now(),
                'estado'           => 'borrador',
                'created_by'       => Auth::id(),
                'updated_by'       => Auth::id(),
            ]);
        }

        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'apellido'    => 'required|string|max:100',
            'correo'      => 'nullable|email|max:150',
            'dependencia' => 'nullable|string|max:255',
            'cargo'       => 'nullable|string|max:150',
            'telefono'    => 'nullable|string|max:50',
        ]);

        $participante = ActivityTaskActaParticipante::create([
            'acta_id'           => $acta->id,
            'nombre'            => $validated['nombre'],
            'apellido'          => $validated['apellido'],
            'correo'            => $validated['correo'] ?? null,
            'dependencia'       => $validated['dependencia'] ?? null,
            'cargo'             => $validated['cargo'] ?? null,
            'telefono'          => $validated['telefono'] ?? null,
            'asistio'           => true,
            'registrado_via_qr' => false,
            'user_id'           => Auth::id(),
        ]);

        return response()->json([
            'ok'           => true,
            'success'      => true,
            'message'      => 'Participante registrado exitosamente.',
            'participante' => [
                'id'                => $participante->id,
                'nombre'            => $participante->nombre,
                'apellido'          => $participante->apellido,
                'nombre_completo'   => $participante->nombre_completo,
                'correo'            => $participante->correo,
                'dependencia'       => $participante->dependencia,
                'cargo'             => $participante->cargo,
                'telefono'          => $participante->telefono,
                'registrado_via_qr' => false,
                'created_at'        => $participante->created_at?->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Elimina un participante.
     */
    public function deleteParticipante(int $taskId, int $participanteId)
    {
        $task = ActivityTask::with('acta')->findOrFail($taskId);
        $acta = $task->acta;

        if (!$acta) {
            return response()->json(['success' => false, 'message' => 'Acta no encontrada.'], 404);
        }

        $participante = ActivityTaskActaParticipante::where('acta_id', $acta->id)->findOrFail($participanteId);
        $participante->delete();

        return response()->json([
            'success' => true,
            'message' => 'Participante eliminado.',
        ]);
    }

    /**
     * Retorna la vista imprimible / PDF con el formato oficial del MECIP.
     */
    public function imprimir(int $taskId)
    {
        $task = ActivityTask::with(['activity', 'acta.participantes', 'assignedTo'])->findOrFail($taskId);
        $acta = $task->acta;

        if (!$acta) {
            abort(404, 'No se ha redactado el acta para esta reunión.');
        }

        $publicUrl = route('actas.public.show', $acta->uuid);
        $qrSvg     = (string) QrCode::size(140)->margin(1)->generate($publicUrl);

        return view('admin.globales.activities.actas.imprimir', compact('task', 'acta', 'publicUrl', 'qrSvg'));
    }

    /**
     * Vista pública personalizada para participantes que acceden mediante Código QR.
     */
    public function publicView(string $token)
    {
        $acta = ActivityTaskActa::with(['task.activity', 'participantes'])->where('uuid', $token)->firstOrFail();
        $task = $acta->task;

        $publicUrl = route('actas.public.show', $acta->uuid);
        $qrSvg     = (string) QrCode::size(160)->margin(1)->generate($publicUrl);

        $yaFirmo = false;
        if (Auth::check()) {
            $userId = Auth::id();
            $email = Auth::user()->email;
            $yaFirmo = $acta->participantes->contains(function ($part) use ($userId, $email) {
                return $part->user_id === $userId || (!empty($email) && strtolower($part->correo) === strtolower($email));
            });
        }

        return view('public.actas.show', compact('acta', 'task', 'publicUrl', 'qrSvg', 'yaFirmo'));
    }

    /**
     * Registro de asistencia del participante vía formulario público del QR.
     */
    public function publicRegistrar(Request $request, string $token)
    {
        $acta = ActivityTaskActa::where('uuid', $token)->firstOrFail();

        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'apellido'    => 'required|string|max:100',
            'correo'      => 'nullable|email|max:150',
            'dependencia' => 'nullable|string|max:255',
            'cargo'       => 'nullable|string|max:150',
            'telefono'    => 'nullable|string|max:50',
            'firma'       => 'required|string',
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'correo.email'      => 'El formato del correo electrónico no es válido.',
            'firma.required'    => 'La firma digital es obligatoria.',
        ]);

        // Verificar si ya está registrado por correo en esta misma acta
        if (!empty($validated['correo'])) {
            $existente = ActivityTaskActaParticipante::where('acta_id', $acta->id)
                ->whereRaw('LOWER(correo) = ?', [strtolower(trim($validated['correo']))])
                ->first();

            if ($existente) {
                // Actualizar sus datos si los envió nuevamente
                $existente->update([
                    'nombre'            => $validated['nombre'],
                    'apellido'          => $validated['apellido'],
                    'dependencia'       => $validated['dependencia'] ?? $existente->dependencia,
                    'cargo'             => $validated['cargo'] ?? $existente->cargo,
                    'telefono'          => $validated['telefono'] ?? $existente->telefono,
                    'registrado_via_qr' => true,
                    'firma'             => $validated['firma'],
                    'ip_address'        => $request->ip(),
                    'user_agent'        => substr($request->userAgent() ?? '', 0, 500),
                ]);

                return response()->json([
                    'success'      => true,
                    'message'      => '¡Tu asistencia y firma ya estaban registradas! Se han actualizado tus datos correctamente.',
                    'participante' => [
                        'nombre_completo' => $existente->nombre_completo,
                        'cargo'           => $existente->cargo,
                        'dependencia'     => $existente->dependencia,
                    ],
                ]);
            }
        }

        $participante = ActivityTaskActaParticipante::create([
            'acta_id'           => $acta->id,
            'nombre'            => $validated['nombre'],
            'apellido'          => $validated['apellido'],
            'correo'            => $validated['correo'] ?? null,
            'dependencia'       => $validated['dependencia'] ?? null,
            'cargo'             => $validated['cargo'] ?? null,
            'telefono'          => $validated['telefono'] ?? null,
            'asistio'           => true,
            'registrado_via_qr' => true,
            'firma'             => $validated['firma'],
            'ip_address'        => $request->ip(),
            'user_agent'        => substr($request->userAgent() ?? '', 0, 500),
            'user_id'           => Auth::id(),
        ]);

        $totalParticipantes = ActivityTaskActaParticipante::where('acta_id', $acta->id)->count();

        return response()->json([
            'success'            => true,
            'message'            => '¡Muchas gracias! Tu participación ha sido registrada exitosamente en el Acta de la Reunión.',
            'participante'       => [
                'id'              => $participante->id,
                'nombre_completo' => $participante->nombre_completo,
                'cargo'           => $participante->cargo,
                'dependencia'     => $participante->dependencia,
                'hora'            => $participante->created_at?->format('H:i'),
            ],
            'total_participantes'=> $totalParticipantes,
        ]);
    }
}
