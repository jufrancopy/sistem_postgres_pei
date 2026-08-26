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

class FormularioController extends Controller
{
    /**
     * GET /riiss/formularios/secciones/{seccion}
     * Detalle de una sección con CRUD de preguntas.
     */
    public function showSeccion(FormularioSeccion $seccion)
    {
        $seccion->load(['preguntas' => fn($q) => $q->orderBy('orden')]);
        return view('admin.riiss.formularios.seccion', compact('seccion'));
    }

    /**
     * PATCH /riiss/formularios/secciones/{seccion}
     * Editar nombre de sección.
     */
    public function updateSeccion(Request $request, FormularioSeccion $seccion): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'seccion'     => 'required|string|max:200',
            'sub_seccion' => 'nullable|string|max:200',
        ]);
        $seccion->update($validated);
        return response()->json(['ok' => true, 'message' => 'Sección actualizada.']);
    }

    /**
     * POST /riiss/formularios/secciones
     * Crear una nueva sección.
     */
    public function storeSeccion(Request $request): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'seccion'     => 'required|string|max:200',
            'sub_seccion' => 'nullable|string|max:200',
            'orden'       => 'nullable|integer',
        ]);

        $orden = $validated['orden'] ?? (FormularioSeccion::max('orden') + 1);

        $seccion = FormularioSeccion::create([
            'seccion'     => $validated['seccion'],
            'sub_seccion' => $validated['sub_seccion'] ?? '',
            'orden'       => $orden,
            'slug'        => \Illuminate\Support\Str::slug($validated['seccion'] . '-' . ($validated['sub_seccion'] ?? '')),
        ]);

        return response()->json(['ok' => true, 'data' => $seccion, 'message' => 'Sección creada.']);
    }

    /**
     * POST /riiss/formularios/secciones/{seccion}/preguntas
     * Agregar pregunta a una sección.
     */
    public function storePregunta(Request $request, FormularioSeccion $seccion): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'pregunta'      => 'required|string|max:1000',
            'tipo_respuesta'=> 'required|in:si_no,si_no_na,texto,numero,lista,checklist',
            'opciones'      => 'nullable|array',
            'orden'         => 'nullable|integer',
        ]);

        $orden = $validated['orden'] ?? ($seccion->preguntas()->max('orden') + 1);

        $pregunta = $seccion->preguntas()->create([
            'pregunta'       => $validated['pregunta'],
            'tipo_respuesta' => $validated['tipo_respuesta'],
            'opciones'       => $validated['opciones'] ?? null,
            'orden'          => $orden,
            'activa'         => true,
        ]);

        return response()->json(['ok' => true, 'data' => $pregunta, 'message' => 'Pregunta agregada.'], 201);
    }

    /**
     * PATCH /riiss/formularios/preguntas/{pregunta}
     * Editar una pregunta.
     */
    public function updatePregunta(Request $request, FormularioPregunta $pregunta): JsonResponse
    {
        $this->checkFormularioPermission();

        $validated = $request->validate([
            'pregunta'      => 'sometimes|string|max:1000',
            'tipo_respuesta'=> 'sometimes|in:si_no,si_no_na,texto,numero,lista,checklist',
            'opciones'      => 'nullable|array',
            'orden'         => 'nullable|integer',
            'activa'        => 'nullable|boolean',
        ]);
        $pregunta->update($validated);
        return response()->json(['ok' => true, 'message' => 'Pregunta actualizada.']);
    }

    /**
     * DELETE /riiss/formularios/preguntas/{pregunta}
     * Desactivar (soft) o eliminar una pregunta.
     */
    public function destroyPregunta(Request $request, FormularioPregunta $pregunta): JsonResponse
    {
        $this->checkFormularioPermission();

        if ($request->get('force')) {
            $pregunta->delete();
            return response()->json(['ok' => true, 'message' => 'Pregunta eliminada.']);
        }
        $pregunta->update(['activa' => false]);
        return response()->json(['ok' => true, 'message' => 'Pregunta desactivada.']);
    }

    /**
     * GET /riiss/formularios
     * Vista principal del módulo de formularios por nivel.
     */
    public function index()
    {
        return view('admin.riiss.formularios.index');
    }

    /**
     * GET /riiss/formularios/datos
     * Retorna secciones con sus preguntas, opcionalmente filtradas por tipología.
     */
    public function datos(Request $request): JsonResponse
    {
        $tipologia = $request->get('tipologia');

        // Obtener secciones que aplican según la tipología seleccionada
        $query = FormularioSeccion::with(['preguntas' => fn($q) => $q->where('activa', true)->orderBy('orden')])
            ->where('activa', true)
            ->orderBy('orden');

        if ($tipologia) {
            $seccionIds = ReglaSeccionFormulario::where('tipologia_clasificacion', $tipologia)
                ->where('aplica', true)
                ->pluck('formulario_seccion_id');

            // Incluir también las secciones base
            $seccionesBase = FormularioSeccion::whereIn('seccion', [
                'Introducción',
                'Datos de Identificación',
                'Datos del encargado de llenado del formulario',
                'Requerimientos documentales',
            ])->pluck('id');

            $query->whereIn('id', $seccionIds->merge($seccionesBase)->unique());
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
                'nombre_completo' => $s->sub_seccion ? "{$s->seccion} > {$s->sub_seccion}" : $s->seccion,
                'orden'           => $s->orden,
                'requerida'       => $regla?->requerida ?? false,
                'condicion'       => $regla?->condicion,
                'total_preguntas' => $s->preguntas->count(),
                'preguntas'       => $s->preguntas->map(fn($p) => [
                    'id'                       => $p->id,
                    'pregunta'                 => $p->pregunta,
                    'tipo_respuesta'           => $p->tipo_respuesta,
                    'servicio_cartera_grupo'   => $p->servicio_cartera_grupo,
                    'especialidad_relacionada' => $p->especialidad_relacionada,
                    'tags_cartera'             => $p->tags_cartera,
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
     * PATCH /riiss/formularios/tipologias/{tipologia}/renombrar
     * Renombra una tipología en todas las tablas relacionadas.
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
     * Vista de edición de reglas de una tipología.
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
                'regla'       => $s->reglas->first(),
            ]);

        return view('admin.riiss.formularios.tipologia', compact('tipologia', 'secciones'));
    }

    /**
     * POST /riiss/formularios/tipologias/{tipologia}/reglas
     * Guardar todas las reglas de una tipología de una vez.
     */
    public function updateTipologia(Request $request, string $tipologia): JsonResponse
    {
        $tipologia = urldecode($tipologia);
        $reglas = $request->input('reglas', []);

        foreach ($reglas as $seccionId => $valores) {
            ReglaSeccionFormulario::updateOrCreate(
                ['tipologia_clasificacion' => $tipologia, 'formulario_seccion_id' => $seccionId],
                [
                    'aplica'    => !empty($valores['aplica']),
                    'requerida' => !empty($valores['requerida']),
                    'condicion' => $valores['condicion'] ?? null,
                    'nota'      => "Regla para {$tipologia}",
                ]
            );
        }

        return response()->json(['ok' => true, 'message' => 'Reglas actualizadas correctamente.']);
    }

    /**
     * GET /riiss/formularios/tipologias
     * Lista de tipologías disponibles con sus complejidades.
     */
    public function tipologias(): JsonResponse
    {
        $tipologias = ReglaSeccionFormulario::select('tipologia_clasificacion')
            ->distinct()
            ->orderBy('tipologia_clasificacion')
            ->pluck('tipologia_clasificacion');

        return response()->json(['ok' => true, 'data' => $tipologias]);
    }

    /**
     * PATCH /riiss/formularios/preguntas/{id}/mapeo
     * Actualiza el mapeo de una pregunta a la cartera de servicios.
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

        return response()->json(['ok' => true, 'message' => 'Mapeo actualizado.']);
    }

    private function checkFormularioPermission(): void
    {
        $user = auth()->user();
        if ($user && $user->hasAnyRole(['Analista - RIISS', 'Analista RIISS']) && !$user->hasAnyRole(['Administrador', 'Super Admin', 'Coordinador RIISS', 'Coordinador - RIISS', 'Coordinación RIISS'])) {
            abort(403, 'No tienes permisos para modificar la configuración de formularios.');
        }
    }
}
