<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\Prestacion;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\VariableDetalle;

class HealthVariableDictionary
{
    /**
     * @return array{variable: Variable, detalle: VariableDetalle, prestacion: Prestacion, created: array{variable: bool, detalle: bool, prestacion: bool}}
     */
    public function remember(string $codigo, string $dominio, string $tipo, string $prestacionNombre): array
    {
        $variable = Variable::withTrashed()->firstOrNew([
            'codigo' => $codigo,
            'nombre' => $dominio,
        ]);
        $variableCreated = ! $variable->exists;
        if ($variable->exists && method_exists($variable, 'trashed') && $variable->trashed()) {
            $variable->restore();
        }
        $variable->fill(['activo' => true]);
        $variable->save();

        $detalle = VariableDetalle::withTrashed()->firstOrNew([
            'variable_id' => $variable->id,
            'nombre' => $tipo,
        ]);
        $detalleCreated = ! $detalle->exists;
        if ($detalle->exists && method_exists($detalle, 'trashed') && $detalle->trashed()) {
            $detalle->restore();
        }
        $detalle->fill(['activo' => true]);
        $detalle->save();

        $prestacion = Prestacion::withTrashed()->firstOrNew([
            'detalle_id' => $detalle->id,
            'nombre' => $prestacionNombre,
        ]);
        $prestacionCreated = ! $prestacion->exists;
        if ($prestacion->exists && method_exists($prestacion, 'trashed') && $prestacion->trashed()) {
            $prestacion->restore();
        }
        $prestacion->fill(['activo' => true]);
        $prestacion->save();

        return [
            'variable' => $variable,
            'detalle' => $detalle,
            'prestacion' => $prestacion,
            'created' => [
                'variable' => $variableCreated,
                'detalle' => $detalleCreated,
                'prestacion' => $prestacionCreated,
            ],
        ];
    }
}
