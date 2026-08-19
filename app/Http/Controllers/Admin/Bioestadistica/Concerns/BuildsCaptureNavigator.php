<?php

namespace App\Http\Controllers\Admin\Bioestadistica\Concerns;

use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;

trait BuildsCaptureNavigator
{
    /**
     * @return array<int, array{formulario: Formulario, record: Record|null, url: string|null, current: bool}>
     */
    protected function captureNavigator(
        int $establecimientoId,
        int $year,
        int $month,
        ?int $currentFormularioId = null,
        ?int $departamentoId = null,
        ?int $servicioId = null
    ): array {
        $forms = Formulario::query()
            ->where('estado', 'activo')
            ->orderBy('codigo')
            ->get();
        $records = Record::query()
            ->where('establecimiento_id', $establecimientoId)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->whereIn('formulario_id', $forms->pluck('id'))
            ->get();

        return $forms->map(function (Formulario $formulario) use (
            $records,
            $establecimientoId,
            $year,
            $month,
            $currentFormularioId,
            $departamentoId,
            $servicioId
        ): array {
            $isNominativo = $formulario->codigo === 'SP10' || $formulario->layout_type === 'nominativo';
            $record = $records->first(function (Record $item) use ($formulario, $isNominativo, $departamentoId, $servicioId) {
                if ((int) $item->formulario_id !== (int) $formulario->id) {
                    return false;
                }
                if ($isNominativo) {
                    return $item->estructura_servicio_id === null;
                }

                return (int) $item->estructura_departamento_id === (int) $departamentoId
                    && (int) $item->estructura_servicio_id === (int) $servicioId;
            });
            $url = null;
            if ($record) {
                $url = $isNominativo
                    ? route('bioestadistica.hospitalizacion.spreadsheet', [
                        'establecimiento_id' => $establecimientoId,
                        'periodo_anio' => $year,
                        'periodo_mes' => $month,
                    ])
                    : route('bioestadistica.captura.edit', $record);
            }

            return [
                'formulario' => $formulario,
                'record' => $record,
                'url' => $url,
                'current' => $currentFormularioId !== null && (int) $formulario->id === $currentFormularioId,
            ];
        })->all();
    }
}
