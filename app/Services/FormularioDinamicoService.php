<?php

namespace App\Services;

use App\Enums\ComplejidadEnum;
use App\Models\Riiss\Establecimiento;
use App\Models\Riiss\FormularioPregunta;
use App\Models\Riiss\FormularioSeccion;
use App\Models\Riiss\ReglaSeccionFormulario;
use Illuminate\Support\Collection;

class FormularioDinamicoService
{
    /**
     * Genera el formulario dinámico para un establecimiento.
     * Solo incluye secciones y preguntas que aplican según tipología y complejidad.
     */
    public function generarFormulario(Establecimiento $est): array
    {
        $secciones = $this->seccionesAplicables($est);

        $formulario = [
            'establecimiento'    => $est->toResumenArray(),
            'requisitos_cartera' => (new CarteraMatchingService())->resumenRequisitos($est),
            'secciones'          => [],
            'resumen'            => [
                'total_secciones'      => 0,
                'total_preguntas'      => 0,
                'preguntas_requeridas' => 0,
                'preguntas_opcionales' => 0,
            ],
        ];

        foreach ($secciones as $seccion) {
            $preguntas  = $this->preguntasParaSeccion($seccion, $est);
            $esRequerida = $this->esSeccionRequerida($seccion, $est);

            if ($preguntas->isNotEmpty()) {
                $formulario['secciones'][] = [
                    'id'          => $seccion->id,
                    'slug'        => $seccion->slug,
                    'nombre'      => $seccion->getNombreCompletoAttribute(),
                    'seccion'     => $seccion->seccion,
                    'sub_seccion' => $seccion->sub_seccion,
                    'orden'       => $seccion->orden,
                    'requerida'   => $esRequerida,
                    'preguntas'   => $preguntas->values()->toArray(),
                ];

                $formulario['resumen']['total_secciones']++;
                $formulario['resumen']['total_preguntas']      += $preguntas->count();
                $formulario['resumen']['preguntas_requeridas'] += $preguntas->where('requerida', true)->count();
                $formulario['resumen']['preguntas_opcionales'] += $preguntas->where('requerida', false)->count();
            }
        }

        return $formulario;
    }

    /**
     * Secciones que aplican para un establecimiento.
     */
    public function seccionesAplicables(Establecimiento $est): Collection
    {
        // Secciones con regla explícita para esta tipología
        $seccionesConRegla = ReglaSeccionFormulario::paraTipologia($est->tipologia_clasificacion)
            ->aplican()
            ->with('seccion.preguntas')
            ->get()
            ->pluck('seccion')
            ->filter()
            ->unique('id');

        // Secciones base que siempre aplican
        $seccionesBase = FormularioSeccion::whereIn('seccion', [
            'Introducción',
            'Datos de Identificación',
            'Datos del encargado de llenado del formulario',
        ])->with('preguntas')->get();

        return $seccionesBase->merge($seccionesConRegla)
            ->unique('id')
            ->sortBy('orden')
            ->filter(fn($s) => $s->preguntas->where('activa', true)->isNotEmpty());
    }

    /**
     * Preguntas de una sección enriquecidas con metadata de contexto.
     */
    public function preguntasParaSeccion(FormularioSeccion $seccion, Establecimiento $est): Collection
    {
        $esRequerida = $this->esSeccionRequerida($seccion, $est);

        return $seccion->preguntas->where('activa', true)->map(function ($p) use ($est, $esRequerida, $seccion) {
            return [
                'id'                    => $p->id,
                'pregunta'              => $p->pregunta,
                'tipo_respuesta'        => $p->tipo_respuesta,
                'opciones'              => $p->opciones,
                'respuesta_ejemplo'     => $p->respuesta_ejemplo,
                'orden'                 => $p->orden,
                'requerida'             => $esRequerida,
                'servicio_cartera_grupo'=> $p->servicio_cartera_grupo,
                'especialidad_relacionada' => $p->especialidad_relacionada,
                'tags_cartera'          => $p->tags_cartera,
                'valor_prellenado'      => $this->valorPrellenado($p, $est, $seccion),
            ];
        });
    }

    /**
     * Determina si una sección es REQUERIDA para un establecimiento.
     */
    public function esSeccionRequerida(FormularioSeccion $seccion, Establecimiento $est): bool
    {
        if (in_array($seccion->seccion, [
            'Introducción',
            'Datos de Identificación',
            'Requerimientos documentales',
        ])) {
            return true;
        }

        $regla = ReglaSeccionFormulario::where('formulario_seccion_id', $seccion->id)
            ->paraTipologia($est->tipologia_clasificacion)
            ->first();

        if ($regla) {
            if ($regla->condicion) {
                return $this->evaluarCondicion($regla->condicion, $est);
            }
            return $regla->requerida;
        }

        return false;
    }

    private function evaluarCondicion(string $condicion, Establecimiento $est): bool
    {
        $c = strtolower(trim($condicion));

        $condiciones = [
            'solo si tiene internacion'                          => $est->tiene_internacion,
            'solo si tiene quirófano'                           => $est->tiene_quirofano_req,
            'solo si tiene uti'                                  => $est->tiene_uti_req,
            'solo si tiene urgencias'                            => $est->tiene_urgencias_req,
            'solo si es hospitalario'                            => $est->es_hospitalario,
            'solo si tiene laboratorio'                          => ComplejidadEnum::fromString($est->complejidad)?->requiereLaboratorio() ?? false,
            'solo si tiene imágenes'                            => ComplejidadEnum::fromString($est->complejidad)?->requiereImagenes() ?? false,
            'solo si es hospital de alta complejidad'            => $est->grado_complejidad === 3,
            'solo si es hospital de mediana complejidad o superior' => $est->grado_complejidad >= 2,
        ];

        foreach ($condiciones as $clave => $valor) {
            if (str_contains($c, $clave)) {
                return $valor;
            }
        }

        return true;
    }

    private function valorPrellenado(FormularioPregunta $pregunta, Establecimiento $est, FormularioSeccion $seccion): ?string
    {
        $pt = strtolower($pregunta->pregunta);

        if ($seccion->seccion === 'Datos de Identificación') {
            if (str_contains($pt, 'nombre del establecimiento')) return $est->nombre_oficial;
            if (str_contains($pt, 'departamento'))              return $est->departamento;
        }

        if ($seccion->seccion === 'Datos generales del establecimiento') {
            if (str_contains($pt, 'carácter del establecimiento')) return $est->prestador;
        }

        return null;
    }

    /**
     * Checklist rápido de secciones con su estado.
     */
    public function checklistSecciones(Establecimiento $est): Collection
    {
        return $this->seccionesAplicables($est)->map(fn($s) => [
            'id'              => $s->id,
            'slug'            => $s->slug,
            'nombre'          => $s->getNombreCompletoAttribute(),
            'requerida'       => $this->esSeccionRequerida($s, $est),
            'total_preguntas' => $s->preguntas->where('activa', true)->count(),
        ]);
    }
}
