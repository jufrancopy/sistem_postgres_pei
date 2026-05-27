<?php

namespace App\Services;

use App\Enums\ComplejidadEnum;
use App\Models\Riiss\CarteraServicio;
use App\Models\Riiss\Establecimiento;
use Illuminate\Support\Collection;

class CarteraMatchingService
{
    /**
     * Servicios REQUERIDOS de la cartera para un establecimiento.
     */
    public function serviciosRequeridos(Establecimiento $est): Collection
    {
        return CarteraServicio::paraNivel($est->nivel_atencion, $est->grado_complejidad)
            ->requeridos()
            ->aplicaATipo($est->tipologia_clasificacion, $est->complejidad)
            ->get()
            ->filter(fn($s) => $s->aplicaPara($est));
    }

    /**
     * Todos los servicios que APLICAN (requeridos + opcionales).
     */
    public function serviciosAplicables(Establecimiento $est): Collection
    {
        return CarteraServicio::paraNivel($est->nivel_atencion, $est->grado_complejidad)
            ->aplicaATipo($est->tipologia_clasificacion, $est->complejidad)
            ->get()
            ->filter(fn($s) => $s->aplicaPara($est));
    }

    /**
     * Solo los servicios OPCIONALES.
     */
    public function serviciosOpcionales(Establecimiento $est): Collection
    {
        return $this->serviciosAplicables($est)->reject(fn($s) => $s->requerido);
    }

    /**
     * Especialidades requeridas según la cartera.
     */
    public function especialidadesRequeridas(Establecimiento $est): Collection
    {
        $servicios     = $this->serviciosRequeridos($est);
        $especialidades = collect();

        foreach ($servicios as $s) {
            foreach ([$s->especialidad_1, $s->especialidad_2] as $esp) {
                if ($esp) {
                    $especialidades->put($esp, [
                        'nombre'          => $esp,
                        'servicios_count' => ($especialidades[$esp]['servicios_count'] ?? 0) + 1,
                    ]);
                }
            }
        }

        return $especialidades->sortByDesc('servicios_count');
    }

    /**
     * Resumen ejecutivo: qué debería tener el establecimiento.
     */
    public function resumenRequisitos(Establecimiento $est): array
    {
        $requeridos    = $this->serviciosRequeridos($est);
        $opcionales    = $this->serviciosOpcionales($est);
        $especialidades = $this->especialidadesRequeridas($est);
        $porTipo       = $requeridos->groupBy('tipo_prestacion');
        $enum          = ComplejidadEnum::fromString($est->complejidad);

        return [
            'establecimiento'   => $est->only('id_establecimiento', 'nombre_oficial', 'tipologia_clasificacion', 'complejidad'),
            'nivel_requerido'   => [
                'nivel_atencion'   => $est->nivel_atencion,
                'grado_complejidad'=> $est->grado_complejidad,
                'label'            => $enum?->label(),
            ],
            'totales' => [
                'servicios_requeridos'  => $requeridos->count(),
                'servicios_opcionales'  => $opcionales->count(),
                'especialidades_requeridas' => $especialidades->count(),
            ],
            'por_tipo_prestacion'    => $porTipo->map(fn($items) => $items->count())->toArray(),
            'especialidades'         => $especialidades->values()->toArray(),
            'infraestructura_requerida' => [
                'internacion' => $enum?->requiereInternacion() ?? false,
                'urgencias'   => $enum?->requiereUrgencias()   ?? false,
                'quirofano'   => $enum?->requiereQuirofano()   ?? false,
                'uti'         => $enum?->requiereUTI()         ?? false,
                'laboratorio' => $enum?->requiereLaboratorio() ?? false,
                'imagenes'    => $enum?->requiereImagenes()    ?? false,
                'farmacia'    => $enum?->requiereFarmacia()    ?? false,
                'vacunatorio' => $enum?->requiereVacunatorio() ?? false,
            ],
            'servicios_detalle' => $requeridos->map(fn($s) => [
                'id'             => $s->id,
                'servicio'       => $s->servicio,
                'grupo'          => $s->grupo_servicio,
                'tipo_prestacion'=> $s->tipo_prestacion,
                'especialidad'   => $s->especialidad_1,
                'detalles'       => $s->detalles,
            ])->values()->toArray(),
        ];
    }

    /**
     * Servicios que FALTAN para subir de nivel.
     */
    public function serviciosFaltantesParaNivel(Establecimiento $est, int $nivelDestino, int $gradoDestino): Collection
    {
        $actuales = $this->serviciosRequeridos($est)->pluck('id');

        $destino = CarteraServicio::paraNivel($nivelDestino, $gradoDestino)
            ->requeridos()
            ->aplicaATipo($est->tipologia_clasificacion, $est->complejidad)
            ->get()
            ->filter(fn($s) => $s->aplicaPara($est));

        return $destino->reject(fn($s) => $actuales->contains($s->id));
    }
}
