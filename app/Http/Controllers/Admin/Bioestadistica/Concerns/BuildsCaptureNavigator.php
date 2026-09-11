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
        ?int $organoId = null
    ): array {
        $forms = Formulario::query()
            ->where('estado', 'activo')
            ->ordenSp()
            ->get();
        $records = Record::query()
            ->where('establecimiento_id', $establecimientoId)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->whereIn('formulario_id', $forms->pluck('id'))
            ->get();

        return $forms->map(function (Formulario $formulario) use (
            $records,
            $currentFormularioId,
            $organoId
        ): array {
            $isNominativo = $formulario->codigo === 'SP10' || $formulario->layout_type === 'nominativo';
            $record = $records->first(function (Record $item) use ($formulario, $organoId) {
                if ((int) $item->formulario_id !== (int) $formulario->id) {
                    return false;
                }

                return (int) ($item->organo_id ?? 0) === (int) ($organoId ?? 0);
            });
            $url = null;
            if ($record) {
                $url = $isNominativo
                    ? route('bioestadistica.hospitalizacion.spreadsheet', $record->spreadsheetParams())
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
