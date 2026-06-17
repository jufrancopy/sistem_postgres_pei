<?php

namespace App\Http\Controllers\Admin\Riiss;

use App\Http\Controllers\Controller;
use App\Models\Riiss\FormularioPregunta;
use App\Models\Riiss\FormularioSeccion;
use App\Models\Riiss\ReglaSeccionFormulario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
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
        $validated = $request->validate([
            'servicio_cartera_grupo'   => 'nullable|string|max:200',
            'especialidad_relacionada' => 'nullable|string|max:200',
            'tags_cartera'             => 'nullable|array',
        ]);

        $pregunta->update($validated);

        return response()->json(['ok' => true, 'message' => 'Mapeo actualizado.']);
    }
}
