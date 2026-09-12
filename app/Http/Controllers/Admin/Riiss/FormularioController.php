<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\CarteraServicio;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\FormularioPregunta;
use App\Models\Riiss\FormularioSeccion;
use App\Models\Riiss\ReglaSeccionFormulario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormularioController extends Controller
{
    /**
     * GET /riiss/formularios
     * Vista principal del constructor Drag & Drop de formularios por Dimensión y Tipología.
     */
    public function index()
    {
        $dimensiones = FormularioSeccion::DIMENSIONES;

        // Conteo por dimensión
        $conteos = [
            'total_secciones' => FormularioSeccion::count(),
            'total_preguntas' => FormularioPregunta::count(),
            'preguntas_activas' => FormularioPregunta::where('activa', true)->count(),
            'por_dimension'   => [],
        ];

        foreach ($dimensiones as $dimKey => $dimMeta) {
            $conteos['por_dimension'][$dimKey] = [
                'secciones' => FormularioSeccion::where('dimension', $dimKey)->count(),
                'preguntas' => FormularioPregunta::where('dimension', $dimKey)->where('activa', true)->count(),
            ];
        }

        $tipologias = Establecimiento::whereNotNull('tipologia_clasificacion')
            ->distinct()
            ->orderBy('tipologia_clasificacion')
            ->pluck('tipologia_clasificacion');

        return view('admin.riiss.formularios.index', compact('dimensiones', 'conteos', 'tipologias'));
    }

    /**
     * GET /riiss/formularios/datos
     * Retorna secciones y preguntas organizadas, filtradas por dimensión, tipología y búsqueda.
     */
    public function datos(Request $request): JsonResponse
    {
        $dimension = $request->get('dimension');
        $tipologia = $request->get('tipologia');
        $buscar    = trim($request->get('buscar', ''));

        $query = FormularioSeccion::with(['preguntas' => function ($q) use ($buscar, $tipologia) {
            if ($buscar) {
                $q->where(function ($sub) use ($buscar) {
                    $sub->where('pregunta', 'ilike', "%{$buscar}%")
                        ->orWhere('servicio_cartera_grupo', 'ilike', "%{$buscar}%")
                        ->orWhere('especialidad_relacionada', 'ilike', "%{$buscar}%");
                });
            }

            if ($tipologia) {
                $minGrado = match($tipologia) {
                    'Puesto Sanitario', 'PUESTO SANITARIO' => 1,
                    'Clínica Periférica', 'CLINICA PERIFERICA' => 2,
                    'Unidad Sanitaria', 'UNIDAD SANITARIA' => 3,
                    'Hospital Regional', 'HOSPITAL REGIONAL', 'Hospital Básico', 'HOSPITAL BASICO' => 4,
                    'Hospital Interregional', 'HOSPITAL INTERREGIONAL' => 5,
                    'Hospital Especializado', 'HOSPITAL ESPECIALIZADO' => 6,
                    default => 1,
                };
                $q->where('grado_complejidad_min', '<=', $minGrado);
            }

            $q->orderBy('orden');
        }])
        ->where('activa', true)
        ->orderBy('dimension')
        ->orderBy('orden');

        if ($dimension && $dimension !== 'all') {
            $query->where('dimension', $dimension);
        }

        if ($tipologia) {
            $seccionIds = ReglaSeccionFormulario::where('tipologia_clasificacion', $tipologia)
                ->where('aplica', true)
                ->pluck('formulario_seccion_id');

            $seccionesBase = FormularioSeccion::whereIn('dimension', ['gobernanza_procesos', 'infraestructura', 'talento_humano'])
                ->pluck('id');

            $query->where(function($q) use ($seccionIds, $seccionesBase) {
                $q->whereIn('id', $seccionIds)->orWhereIn('id', $seccionesBase)->orWhere('dimension', 'cartera_servicios');
            });
        }

        $secciones = $query->get()->map(function ($s) use ($tipologia) {
            $regla = $tipologia
                ? ReglaSeccionFormulario::where('formulario_seccion_id', $s->id)
                    ->where('tipologia_clasificacion', $tipologia)
                    ->first()
                : null;

            return [
                'id'              => $s->id,
                'seccion'         => $s->seccion,
                'sub_seccion'     => $s->sub_seccion,
                'dimension'       => $s->dimension ?: 'cartera_servicios',
                'dimension_info'  => $s->dimension_config,
                'icono'           => $s->icono ?: $s->dimension_config['icono'],
                'nombre_completo' => $s->nombre_completo,
                'orden'           => $s->orden,
                'requerida'       => $regla?->requerida ?? true,
                'total_preguntas' => $s->preguntas->count(),
                'preguntas'       => $s->preguntas->map(fn($p) => [
                    'id'                       => $p->id,
                    'formulario_seccion_id'    => $p->formulario_seccion_id,
                    'dimension'                => $p->dimension ?: $s->dimension,
                    'dimension_info'           => $p->dimension_config,
                    'pregunta'                 => $p->pregunta,
                    'tipo_respuesta'           => $p->tipo_respuesta,
                    'grado_complejidad_min'    => $p->grado_complejidad_min ?: 1,
                    'es_requerido'             => $p->es_requerido,
                    'activa'                   => $p->activa,
                    'servicio_cartera_grupo'   => $p->servicio_cartera_grupo,
                    'especialidad_relacionada' => $p->especialidad_relacionada,
                    'tags_cartera'             => $p->tags_cartera,
                    'metadata_cartera'         => $p->metadata_cartera,
                    'orden'                    => $p->orden,
                ])->values(),
            ];
        });

        return response()->json([
            'ok'   => true,
            'data' => $secciones->values(),
        ]);
    }

    /**
     * GET /riiss/formularios/banco-preguntas
     * Retorna preguntas del banco general para empaquetado y drag & drop.
     */
    public function bancoPreguntas(Request $request): JsonResponse
    {
        $dimension     = $request->get('dimension');
        $buscar        = trim($request->get('buscar', ''));
        $soloEvaluadas = filter_var($request->get('solo_evaluadas', false), FILTER_VALIDATE_BOOLEAN);
        $limite        = (int) $request->get('limite', 50);
        $offset        = (int) $request->get('offset', 0);

        $query = FormularioPregunta::with('seccion')
            ->where('activa', true);

        if ($dimension && $dimension !== 'all') {
            $query->where(function($q) use ($dimension) {
                $q->where('dimension', $dimension)
                  ->orWhereHas('seccion', fn($sq) => $sq->where('dimension', $dimension));
            });
        }

        if ($soloEvaluadas) {
            $preguntasConRespuestasIds = DB::table('evaluacion_respuestas')
                ->distinct()
                ->pluck('formulario_pregunta_id');
            $query->whereIn('id', $preguntasConRespuestasIds);
        }

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('pregunta', 'ilike', "%{$buscar}%")
                  ->orWhere('servicio_cartera_grupo', 'ilike', "%{$buscar}%")
                  ->orWhere('especialidad_relacionada', 'ilike', "%{$buscar}%")
                  ->orWhereHas('seccion', fn($sq) => $sq->where('seccion', 'ilike', "%{$buscar}%")->orWhere('sub_seccion', 'ilike', "%{$buscar}%"));
            });
        }

        $total = (clone $query)->count();

        $preguntas = $query->orderBy('id', 'desc')
            ->skip($offset)
            ->limit($limite)
            ->get()
            ->map(fn($p) => [
                'id'                     => $p->id,
                'formulario_seccion_id'  => $p->formulario_seccion_id,
                'seccion_nombre'         => $p->seccion?->nombre_completo ?? 'General',
                'seccion_id'             => $p->formulario_seccion_id,
                'dimension'              => $p->dimension ?: ($p->seccion?->dimension ?: 'cartera_servicios'),
                'dimension_info'         => $p->dimension_config,
                'pregunta'               => $p->pregunta,
                'tipo_respuesta'         => $p->tipo_respuesta,
                'grado_complejidad_min'  => $p->grado_complejidad_min ?: 1,
                'es_requerido'           => $p->es_requerido,
                'servicio_cartera_grupo' => $p->servicio_cartera_grupo,
                'especialidad_relacionada' => $p->especialidad_relacionada,
            ]);

        return response()->json([
            'ok'       => true,
            'total'    => $total,
            'data'     => $preguntas,
            'offset'   => $offset,
            'has_more' => ($offset + $limite) < $total,
        ]);
    }

    /**
     * POST /riiss/formularios/vincular-pregunta
     * Vincula, reutiliza o mueve una pregunta desde el banco universal hacia una sección de destino.
     */
    public function vincularPregunta(Request $request): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'pregunta_id'           => 'required|integer|exists:formulario_preguntas,id',
            'formulario_seccion_id' => 'required|integer|exists:formulario_secciones,id',
            'modo'                  => 'nullable|string|in:copiar,mover,duplicar',
            'orden'                 => 'nullable|integer',
        ]);

        $modo = $validated['modo'] ?? 'copiar';
        $targetSeccion = FormularioSeccion::findOrFail($validated['formulario_seccion_id']);
        $preguntaOriginal = FormularioPregunta::findOrFail($validated['pregunta_id']);

        $maxOrden = FormularioPregunta::where('formulario_seccion_id', $targetSeccion->id)->max('orden') ?? 0;
        $nuevoOrden = $validated['orden'] ?? ($maxOrden + 1);

        if ($modo === 'mover') {
            $preguntaOriginal->formulario_seccion_id = $targetSeccion->id;
            $preguntaOriginal->dimension = $targetSeccion->dimension;
            $preguntaOriginal->orden = $nuevoOrden;
            $preguntaOriginal->save();
            $preguntaResultado = $preguntaOriginal;
            $mensaje = "Pregunta movida a la sección '{$targetSeccion->nombre_completo}'.";
        } else {
            $preguntaResultado = $preguntaOriginal->replicate();
            $preguntaResultado->formulario_seccion_id = $targetSeccion->id;
            $preguntaResultado->dimension = $targetSeccion->dimension;
            $preguntaResultado->orden = $nuevoOrden;
            $preguntaResultado->save();
            $mensaje = "Pregunta reutilizada / vinculada exitosamente a '{$targetSeccion->nombre_completo}'.";
        }

        return response()->json([
            'ok'       => true,
            'message'  => $mensaje,
            'modo'     => $modo,
            'pregunta' => [
                'id'                       => $preguntaResultado->id,
                'formulario_seccion_id'    => $preguntaResultado->formulario_seccion_id,
                'dimension'                => $preguntaResultado->dimension,
                'dimension_info'           => $preguntaResultado->dimension_config,
                'pregunta'                 => $preguntaResultado->pregunta,
                'tipo_respuesta'           => $preguntaResultado->tipo_respuesta,
                'grado_complejidad_min'    => $preguntaResultado->grado_complejidad_min ?: 1,
                'es_requerido'             => $preguntaResultado->es_requerido,
                'activa'                   => $preguntaResultado->activa,
                'servicio_cartera_grupo'   => $preguntaResultado->servicio_cartera_grupo,
                'especialidad_relacionada' => $preguntaResultado->especialidad_relacionada,
                'tags_cartera'             => $preguntaResultado->tags_cartera,
                'metadata_cartera'         => $preguntaResultado->metadata_cartera,
                'orden'                    => $preguntaResultado->orden,
            ]
        ]);
    }

    /**
     * POST /riiss/formularios/reordenar-preguntas
     * Persiste el reordenamiento Drag & Drop de preguntas entre secciones y dimensiones.
     */
    public function reordenarPreguntas(Request $request): JsonResponse
    {
        $this->checkFormularioPermission();

        $request->validate([
            'items'                         => 'required|array',
            'items.*.id'                    => 'required|integer|exists:formulario_preguntas,id',
            'items.*.formulario_seccion_id' => 'required|integer|exists:formulario_secciones,id',
            'items.*.orden'                 => 'required|integer',
            'items.*.dimension'             => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                $updateData = [
                    'formulario_seccion_id' => $item['formulario_seccion_id'],
                    'orden'                 => $item['orden'],
                ];
                if (!empty($item['dimension'])) {
                    $updateData['dimension'] = $item['dimension'];
                }
                FormularioPregunta::where('id', $item['id'])->update($updateData);
            }
        });

        return response()->json(['ok' => true, 'message' => 'Estructura y orden de preguntas guardados.']);
    }

    /**
     * POST /riiss/formularios/reordenar-secciones
     * Persiste el orden Drag & Drop de las secciones.
     */
    public function reordenarSecciones(Request $request): JsonResponse
    {
        $this->checkFormularioPermission();

        $request->validate([
            'secciones'         => 'required|array',
            'secciones.*.id'    => 'required|integer|exists:formulario_secciones,id',
            'secciones.*.orden' => 'required|integer',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->secciones as $sec) {
                FormularioSeccion::where('id', $sec['id'])->update(['orden' => $sec['orden']]);
            }
        });

        return response()->json(['ok' => true, 'message' => 'Orden de secciones guardado.']);
    }

    /**
     * POST /riiss/formularios/preguntas/{pregunta}/duplicar
     * Duplica una pregunta existente.
     */
    public function duplicarPregunta(Request $request, FormularioPregunta $pregunta): JsonResponse
    {
        $this->checkFormularioPermission();

        $seccionId = $request->get('formulario_seccion_id', $pregunta->formulario_seccion_id);
        $maxOrden = FormularioPregunta::where('formulario_seccion_id', $seccionId)->max('orden') ?? 0;

        $nueva = $pregunta->replicate();
        $nueva->formulario_seccion_id = $seccionId;
        $nueva->pregunta = $pregunta->pregunta . ' (Copia)';
        $nueva->orden = $maxOrden + 1;
        $nueva->save();

        return response()->json([
            'ok'      => true,
            'message' => 'Pregunta duplicada exitosamente.',
            'data'    => $nueva->load('seccion'),
        ]);
    }

    /**
     * POST /riiss/formularios/preguntas/{pregunta}/toggle-activa
     * Activa o desactiva una pregunta.
     */
    public function toggleActivaPregunta(Request $request, FormularioPregunta $pregunta): JsonResponse
    {
        $this->checkFormularioPermission();

        $pregunta->activa = !$pregunta->activa;
        $pregunta->save();

        return response()->json([
            'ok'      => true,
            'activa'  => $pregunta->activa,
            'message' => $pregunta->activa ? 'Pregunta activada.' : 'Pregunta desactivada.',
        ]);
    }

    /**
     * GET /riiss/formularios/secciones/{seccion}
     */
    public function showSeccion(FormularioSeccion $seccion)
    {
        $seccion->load(['preguntas' => fn($q) => $q->orderBy('orden')]);
        return view('admin.riiss.formularios.seccion', compact('seccion'));
    }

    /**
     * PATCH /riiss/formularios/secciones/{seccion}
     */
    public function updateSeccion(Request $request, FormularioSeccion $seccion): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'seccion'     => 'required|string|max:150',
            'sub_seccion' => 'nullable|string|max:255',
            'dimension'   => 'nullable|string|in:cartera_servicios,infraestructura,talento_humano,medicamentos_insumos,gobernanza_procesos',
            'icono'       => 'nullable|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $seccion->update($validated);

        // Si cambió la dimensión de la sección, actualizar sus preguntas
        if (!empty($validated['dimension'])) {
            $seccion->preguntas()->update(['dimension' => $validated['dimension']]);
        }

        return response()->json(['ok' => true, 'message' => 'Sección actualizada exitosamente.']);
    }

    /**
     * POST /riiss/formularios/secciones
     */
    public function storeSeccion(Request $request): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'seccion'     => 'required|string|max:150',
            'sub_seccion' => 'nullable|string|max:255',
            'dimension'   => 'required|string|in:cartera_servicios,infraestructura,talento_humano,medicamentos_insumos,gobernanza_procesos',
            'icono'       => 'nullable|string|max:50',
            'descripcion' => 'nullable|string|max:1000',
            'orden'       => 'nullable|integer',
        ]);

        $orden = $validated['orden'] ?? (FormularioSeccion::where('dimension', $validated['dimension'])->max('orden') + 1);

        $seccion = FormularioSeccion::create([
            'seccion'     => $validated['seccion'],
            'sub_seccion' => $validated['sub_seccion'] ?? '',
            'dimension'   => $validated['dimension'],
            'icono'       => $validated['icono'] ?: (FormularioSeccion::DIMENSIONES[$validated['dimension']]['icono'] ?? 'fa-folder'),
            'descripcion' => $validated['descripcion'] ?? null,
            'orden'       => $orden,
            'activa'      => true,
        ]);

        return response()->json(['ok' => true, 'data' => $seccion, 'message' => 'Sección creada exitosamente.']);
    }

    /**
     * POST /riiss/formularios/secciones/{seccion}/preguntas
     */
    public function storePregunta(Request $request, FormularioSeccion $seccion): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'pregunta'              => 'required|string|max:1000',
            'tipo_respuesta'        => 'required|in:si_no,si_no_na,texto,numero,lista,checklist',
            'dimension'             => 'nullable|string|in:cartera_servicios,infraestructura,talento_humano,medicamentos_insumos,gobernanza_procesos',
            'grado_complejidad_min' => 'nullable|integer|between:1,6',
            'es_requerido'          => 'nullable|boolean',
            'peso_ponderacion'      => 'nullable|numeric|between:0.1,10',
            'opciones'              => 'nullable|array',
            'orden'                 => 'nullable|integer',
        ]);

        $orden = $validated['orden'] ?? ($seccion->preguntas()->max('orden') + 1);
        $dimension = $validated['dimension'] ?: ($seccion->dimension ?: 'cartera_servicios');

        $pregunta = $seccion->preguntas()->create([
            'dimension'             => $dimension,
            'pregunta'              => $validated['pregunta'],
            'tipo_respuesta'        => $validated['tipo_respuesta'],
            'grado_complejidad_min' => $validated['grado_complejidad_min'] ?? 1,
            'es_requerido'          => $validated['es_requerido'] ?? true,
            'peso_ponderacion'      => $validated['peso_ponderacion'] ?? 1.00,
            'opciones'              => $validated['opciones'] ?? null,
            'orden'                 => $orden,
            'activa'                => true,
        ]);

        return response()->json(['ok' => true, 'data' => $pregunta, 'message' => 'Pregunta agregada exitosamente.'], 201);
    }

    /**
     * PATCH /riiss/formularios/preguntas/{pregunta}
     */
    public function updatePregunta(Request $request, FormularioPregunta $pregunta): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'pregunta'              => 'sometimes|string|max:1000',
            'tipo_respuesta'        => 'sometimes|in:si_no,si_no_na,texto,numero,lista,checklist',
            'dimension'             => 'sometimes|string|in:cartera_servicios,infraestructura,talento_humano,medicamentos_insumos,gobernanza_procesos',
            'grado_complejidad_min' => 'sometimes|integer|between:1,6',
            'es_requerido'          => 'sometimes|boolean',
            'peso_ponderacion'      => 'sometimes|numeric|between:0.1,10',
            'opciones'              => 'nullable|array',
            'orden'                 => 'nullable|integer',
            'activa'                => 'nullable|boolean',
        ]);

        $pregunta->update($validated);
        return response()->json(['ok' => true, 'message' => 'Pregunta actualizada exitosamente.']);
    }

    /**
     * DELETE /riiss/formularios/preguntas/{pregunta}
     */
    public function destroyPregunta(Request $request, FormularioPregunta $pregunta): JsonResponse
    {
        $this->checkFormularioPermission();

        if ($request->get('force')) {
            $pregunta->delete();
            return response()->json(['ok' => true, 'message' => 'Pregunta eliminada permanentemente.']);
        }
        $pregunta->update(['activa' => false]);
        return response()->json(['ok' => true, 'message' => 'Pregunta desactivada.']);
    }

    /**
     * PATCH /riiss/formularios/tipologias/{tipologia}/renombrar
     */
    public function renombrarTipologia(Request $request, string $tipologia): JsonResponse
    {
        $tipologia = urldecode($tipologia);
        $request->validate(['nuevo_nombre' => 'required|string|max:100']);
        $nuevo = trim($request->nuevo_nombre);

        if ($nuevo === $tipologia) {
            return response()->json(['ok' => false, 'message' => 'El nombre es igual al actual.'], 422);
        }

        DB::transaction(function () use ($tipologia, $nuevo) {
            ReglaSeccionFormulario::where('tipologia_clasificacion', $tipologia)
                ->update(['tipologia_clasificacion' => $nuevo]);
            Establecimiento::where('tipologia_clasificacion', $tipologia)
                ->update(['tipologia_clasificacion' => $nuevo]);
            CarteraServicio::where('tipo_establecimiento', $tipologia)
                ->update(['tipo_establecimiento' => $nuevo]);
        });

        return response()->json(['ok' => true, 'message' => "Tipología renombrada a '{$nuevo}' en todas las tablas."]);
    }

    /**
     * GET /riiss/formularios/tipologias/{tipologia}
     */
    public function showTipologia(string $tipologia)
    {
        $tipologia = urldecode($tipologia);
        $secciones = FormularioSeccion::with(['reglas' => fn($q) => $q->where('tipologia_clasificacion', $tipologia)])
            ->where('activa', true)
            ->orderBy('orden')
            ->get()
            ->map(fn($s) => [
                'id'          => $s->id,
                'nombre'      => $s->sub_seccion ? "{$s->seccion} > {$s->sub_seccion}" : $s->seccion,
                'seccion'     => $s->seccion,
                'sub_seccion' => $s->sub_seccion,
                'dimension'   => $s->dimension,
                'regla'       => $s->reglas->first(),
            ]);

        return view('admin.riiss.formularios.tipologia', compact('tipologia', 'secciones'));
    }

    /**
     * POST /riiss/formularios/tipologias/{tipologia}/reglas
     */
    public function updateTipologia(Request $request, string $tipologia): JsonResponse
    {
        $this->checkFormularioPermission();

        $tipologia = urldecode($tipologia);
        $request->validate([
            'reglas'                         => 'required|array',
            'reglas.*.formulario_seccion_id' => 'required|integer|exists:formulario_secciones,id',
            'reglas.*.aplica'                => 'required|boolean',
            'reglas.*.requerida'             => 'required|boolean',
            'reglas.*.condicion'             => 'nullable|string|max:200',
        ]);

        DB::transaction(function () use ($tipologia, $request) {
            foreach ($request->reglas as $regla) {
                ReglaSeccionFormulario::updateOrCreate(
                    [
                        'tipologia_clasificacion' => $tipologia,
                        'formulario_seccion_id'   => $regla['formulario_seccion_id'],
                    ],
                    [
                        'aplica'    => $regla['aplica'],
                        'requerida' => $regla['requerida'],
                        'condicion' => $regla['condicion'] ?? null,
                    ]
                );
            }
        });

        return response()->json(['ok' => true, 'message' => 'Reglas de tipología actualizadas exitosamente.']);
    }

    /**
     * GET /riiss/formularios/tipologias
     */
    public function tipologias(): JsonResponse
    {
        $tipologias = Establecimiento::whereNotNull('tipologia_clasificacion')
            ->distinct()
            ->orderBy('tipologia_clasificacion')
            ->pluck('tipologia_clasificacion');

        return response()->json(['ok' => true, 'data' => $tipologias]);
    }

    /**
     * PATCH /riiss/formularios/preguntas/{id}/mapeo
     */
    public function actualizarMapeo(Request $request, FormularioPregunta $pregunta): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'servicio_cartera_grupo'   => 'nullable|string|max:200',
            'especialidad_relacionada' => 'nullable|string|max:200',
            'tags_cartera'             => 'nullable|array',
        ]);

        $pregunta->update($validated);

        return response()->json(['ok' => true, 'message' => 'Mapeo actualizado exitosamente.']);
    }

    private function checkFormularioPermission(): void
    {
        $user = auth()->user();
        if ($user && $user->hasAnyRole(['Analista - RIISS', 'Analista RIISS']) && !$user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS'])) {
            abort(403, 'No tienes permisos para modificar la configuración de formularios.');
        }
    }
}
