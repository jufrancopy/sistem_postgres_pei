<?php

namespace App\Http\Controllers\Admin\Planificacion\Estructura;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\Estructura\SolicitudAjusteEstructura;
use App\Models\Estructura\SolicitudAjusteEstructuraItem;
use App\Admin\Globales\Organigrama;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\HomeConfiguration;

class SolicitudAjusteEstructuraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'solicitarForm',
            'solicitarStore',
            'consultarPublica',
            'getAccionesDePerfil',
        ]);
    }

    /**
     * GET /admin/planificacion/estructura-solicitudes
     * Bandeja de Gestión de Solicitudes de Ajuste Estructural.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SolicitudAjusteEstructura::with([
                'peiProfile', 'dependenciaSolicitante', 'analista', 'items'
            ])
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->when($request->q, function($q) use ($request) {
                $search = $request->q;
                $q->where(function($sq) use ($search) {
                    $sq->where('codigo', 'ILIKE', "%{$search}%")
                       ->orWhere('solicitante_nombre', 'ILIKE', "%{$search}%")
                       ->orWhere('dependencia_solicitante_texto', 'ILIKE', "%{$search}%");
                });
            })
            ->when($request->pei_profile_id, function($q) use ($request) {
                $perfil = PeiProfile::find($request->pei_profile_id);
                if ($perfil) {
                    $accionIds = $perfil->descendants()->pluck('id')->push($perfil->id);
                    $q->whereIn('pei_profile_id', $accionIds);
                }
            })
            ->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('codigo_formatted', function (SolicitudAjusteEstructura $r) {
                    return '<span class="font-weight-bold text-dark"><i class="fa fa-file-alt text-primary mr-1"></i>' . e($r->codigo) . '</span><br><small class="text-muted">' . $r->fecha_solicitud->format('d/m/Y') . '</small>';
                })
                ->addColumn('solicitante_info', function (SolicitudAjusteEstructura $r) {
                    $dep = $r->dependenciaSolicitante?->dependency ?: ($r->dependencia_solicitante_texto ?: 'No especificada');
                    return '<div><strong>' . e($r->solicitante_nombre) . '</strong><br><small class="text-muted"><i class="fa fa-sitemap mr-1"></i>' . e($dep) . '</small></div>';
                })
                ->addColumn('pei_vinculo', function (SolicitudAjusteEstructura $r) {
                    if ($r->peiProfile) {
                        return '<span class="badge badge-success font-weight-bold p-1"><i class="fa fa-bullseye mr-1"></i>' . e(strip_tags($r->peiProfile->name)) . '</span>';
                    }
                    return '<span class="badge badge-warning text-dark">Sin vinculación PEI</span>';
                })
                ->addColumn('items_count', function (SolicitudAjusteEstructura $r) {
                    $cant = $r->items->count();
                    return '<span class="badge badge-info">' . $cant . ' ' . ($cant === 1 ? 'cambio' : 'cambios') . '</span>';
                })
                ->addColumn('estado_badge', function (SolicitudAjusteEstructura $r) {
                    return '<span class="badge ' . SolicitudAjusteEstructura::estadoBadge($r->estado) . ' font-weight-bold px-2 py-1" style="font-size:0.75rem;">' .
                        SolicitudAjusteEstructura::estadoLabel($r->estado) . '</span>';
                })
                ->addColumn('action', function (SolicitudAjusteEstructura $r) {
                    $btn = '<div class="btn-group btn-group-sm">';
                    $btn .= '<a href="' . route('admin.estructura-solicitudes.show', $r->id) . '" class="btn btn-info btn-round mr-1" title="Ver Detalle y Dictaminar"><i class="fa fa-eye"></i></a>';
                    $btn .= '<a href="' . route('solicitud-estructura.consulta', $r->token_qr) . '" target="_blank" class="btn btn-secondary btn-round" title="Ver Consulta Pública / QR"><i class="fa fa-qrcode"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['codigo_formatted', 'solicitante_info', 'pei_vinculo', 'items_count', 'estado_badge', 'action'])
                ->make(true);
        }

        // Métricas KPIs para la cabecera
        $kpis = [
            'total'       => SolicitudAjusteEstructura::count(),
            'solicitud'   => SolicitudAjusteEstructura::where('estado', 'solicitud')->count(),
            'en_analisis' => SolicitudAjusteEstructura::where('estado', 'en_analisis')->count(),
            'observado'   => SolicitudAjusteEstructura::where('estado', 'observado')->count(),
            'aprobado'    => SolicitudAjusteEstructura::where('estado', 'aprobado')->count(),
            'rechazado'   => SolicitudAjusteEstructura::where('estado', 'rechazado')->count(),
        ];

        $estados = SolicitudAjusteEstructura::ESTADOS;
        $config = HomeConfiguration::first();
        $targetPeiId = $config?->pei_profile_id ?: 'ce99f883-fdd0-4723-8f75-cf689aa8f0fa';

        return view('admin.planificacion.estructura_solicitudes.index', compact('kpis', 'estados', 'targetPeiId'));
    }

    /**
     * GET /admin/planificacion/estructura-solicitudes/{id}
     * Detalle completo de la solicitud y panel de dictamen técnico.
     */
    public function show($id)
    {
        $solicitud = SolicitudAjusteEstructura::with([
            'items', 'peiProfile', 'dependenciaSolicitante', 'analista'
        ])->findOrFail($id);

        $qrSvg = (string) QrCode::size(180)->margin(1)->generate($solicitud->url_qr);
        $estados = SolicitudAjusteEstructura::ESTADOS;

        return view('admin.planificacion.estructura_solicitudes.show', compact('solicitud', 'qrSvg', 'estados'));
    }

    /**
     * POST /admin/planificacion/estructura-solicitudes/{id}/estado
     * Cambiar estado de la solicitud y registrar dictamen técnico.
     */
    public function cambiarEstado(Request $request, $id)
    {
        $solicitud = SolicitudAjusteEstructura::findOrFail($id);

        $request->validate([
            'estado'           => 'required|string|in:' . implode(',', array_keys(SolicitudAjusteEstructura::ESTADOS)),
            'dictamen_tecnico' => 'nullable|string|max:5000',
        ]);

        $solicitud->update([
            'estado'           => $request->estado,
            'dictamen_tecnico' => $request->dictamen_tecnico,
            'analista_id'      => Auth::id(),
            'fecha_dictamen'   => now(),
        ]);

        return redirect()->route('admin.estructura-solicitudes.show', $solicitud->id)
            ->with('success', "Estado actualizado a '" . SolicitudAjusteEstructura::estadoLabel($request->estado) . "' y dictamen registrado correctamente.");
    }

    /**
     * GET /pei-profiles/{profileId}/solicitar-ajuste-estructura
     * Formulario institucional de Solicitud de Ajuste de Estructura Organizacional.
     */
    public function solicitarForm($profileId = null)
    {
        if (!$profileId) {
            $config = HomeConfiguration::first();
            $profileId = $config?->pei_profile_id ?: 'ce99f883-fdd0-4723-8f75-cf689aa8f0fa';
        }

        $perfil = PeiProfile::find($profileId);
        if (!$perfil) {
            $perfil = PeiProfile::whereNull('parent_id')->firstOrFail();
        }

        $dependencias = Organigrama::whereNull('parent_id')->with('children')->orderBy('dependency')->get();
        $tiposReorganizacion = SolicitudAjusteEstructura::TIPOS_REORGANIZACION;

        return view('public.estructura_solicitudes.solicitar', compact('perfil', 'dependencias', 'tiposReorganizacion'));
    }

    /**
     * POST /pei-profiles/{profileId}/solicitar-ajuste-estructura
     * Procesar y registrar solicitud de ajuste de estructura.
     */
    public function solicitarStore(Request $request, $profileId = null)
    {
        $request->validate([
            'solicitante_nombre'   => 'required|string|max:255',
            'solicitante_cargo'    => 'nullable|string|max:255',
            'solicitante_email'    => 'required|email|max:255',
            'solicitante_telefono' => 'nullable|string|max:100',
            'pei_profile_id'       => 'required|exists:planificacion.pei_profiles,id',
            'dependencia_solicitante_id' => 'nullable|exists:organigramas,id',
            'dependencia_solicitante_texto' => 'nullable|string|max:255',
            'fundamentacion_general' => 'nullable|string',
            'documento_respaldo'   => 'nullable|file|mimes:pdf,docx,zip|max:10240',
            'organigrama_adjunto'  => 'nullable|file|mimes:pdf,png,jpg,jpeg,zip|max:10240',
            'items'                => 'required|array|min:1',
            'items.*.tipo_reorganizacion'            => 'required|string',
            'items.*.denominacion_propuesta'         => 'required|string|max:255',
            'items.*.objetivo_dependencia_propuesta' => 'required|string',
            'items.*.descripcion_motivos'            => 'required|string',
            'items.*.denominacion_actual'            => 'nullable|string|max:255',
            'items.*.observaciones'                  => 'nullable|string',
        ], [
            'pei_profile_id.required'     => 'Debe vincular la solicitud con una Acción / Objetivo del Plan Estratégico.',
            'items.required'              => 'Debe incluir al menos un renglón de modificación estructural en la tabla.',
            'items.*.denominacion_propuesta.required' => 'La denominación propuesta es obligatoria en todos los renglones.',
            'items.*.objetivo_dependencia_propuesta.required' => 'El objetivo de la dependencia propuesta es obligatorio.',
            'items.*.descripcion_motivos.required' => 'La descripción y motivos de reorganización son obligatorios.',
        ]);

        $docRespaldoPath = null;
        if ($request->hasFile('documento_respaldo')) {
            $docRespaldoPath = $request->file('documento_respaldo')->store('solicitudes_estructura/respaldos', 'public');
        }

        $orgAdjuntoPath = null;
        if ($request->hasFile('organigrama_adjunto')) {
            $orgAdjuntoPath = $request->file('organigrama_adjunto')->store('solicitudes_estructura/organigramas', 'public');
        }

        $solicitud = SolicitudAjusteEstructura::create([
            'codigo'                        => SolicitudAjusteEstructura::generarCodigo(),
            'fecha_solicitud'               => now()->toDateString(),
            'pei_profile_id'                => $request->pei_profile_id,
            'dependencia_solicitante_id'    => $request->dependencia_solicitante_id ?: null,
            'dependencia_solicitante_texto' => $request->dependencia_solicitante_texto ?: null,
            'solicitante_nombre'            => $request->solicitante_nombre,
            'solicitante_cargo'             => $request->solicitante_cargo,
            'solicitante_email'             => $request->solicitante_email,
            'solicitante_telefono'          => $request->solicitante_telefono,
            'fundamentacion_general'        => $request->fundamentacion_general,
            'documento_respaldo_path'       => $docRespaldoPath,
            'organigrama_adjunto_path'      => $orgAdjuntoPath,
            'estado'                        => 'solicitud',
            'token_qr'                      => SolicitudAjusteEstructura::generarToken(),
        ]);

        foreach ($request->items as $index => $itemData) {
            SolicitudAjusteEstructuraItem::create([
                'solicitud_id'                   => $solicitud->id,
                'tipo_reorganizacion'            => $itemData['tipo_reorganizacion'] ?? 'CREACION',
                'denominacion_actual'            => $itemData['denominacion_actual'] ?? null,
                'denominacion_propuesta'         => $itemData['denominacion_propuesta'],
                'objetivo_dependencia_propuesta' => $itemData['objetivo_dependencia_propuesta'],
                'descripcion_motivos'            => $itemData['descripcion_motivos'],
                'observaciones'                  => $itemData['observaciones'] ?? null,
                'orden'                          => $index + 1,
            ]);
        }

        return redirect()->route('solicitud-estructura.consulta', $solicitud->token_qr)
            ->with('success', "¡Solicitud {$solicitud->codigo} registrada exitosamente! Guardá este comprobante con tu Código QR.");
    }

    /**
     * GET /solicitud-estructura/{token}
     * Consulta pública y comprobante digital con QR de la solicitud.
     */
    public function consultarPublica($token)
    {
        $solicitud = SolicitudAjusteEstructura::with([
            'items', 'peiProfile', 'dependenciaSolicitante'
        ])
        ->where('token_qr', $token)
        ->orWhere('codigo', $token)
        ->firstOrFail();

        $qrSvg = (string) QrCode::size(200)->margin(1)->generate($solicitud->url_qr);

        return view('public.estructura_solicitudes.consulta', compact('solicitud', 'qrSvg'));
    }

    /**
     * GET /pei-profiles/{profileId}/acciones-estrategicas
     * API para Select2 de Acciones y Objetivos PEI.
     */
    public function getAccionesDePerfil(Request $request, $profileId)
    {
        $perfil = PeiProfile::findOrFail($profileId);
        $ids = $perfil->descendants()->where('level', 'action')->pluck('id');

        // Si no hay acciones directas en descendientes, traer todas las acciones del plan
        if ($ids->isEmpty()) {
            $ids = PeiProfile::where('level', 'action')->pluck('id');
        }

        $data = PeiProfile::whereIn('id', $ids)
            ->when($request->q, fn($q) => $q->where('name', 'ILIKE', '%' . $request->q . '%'))
            ->limit(50)
            ->get()
            ->map(fn($p) => [
                'id'   => $p->id,
                'text' => strip_tags($p->name),
            ]);

        return response()->json($data);
    }
}
