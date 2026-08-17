<?php

namespace App\Application\Bioestadistica\Indicators;

use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\IndicadorCache;
use App\Models\Bioestadistica\Record;

class IndicatorCacheService
{
    public function __construct(private FormulaAstValidator $validator)
    {
    }

    public function invalidateForRecord(Record $record): int
    {
        return IndicadorCache::where('establecimiento_id', $record->establecimiento_id)
            ->where('periodo_anio', $record->periodo_anio)
            ->where('periodo_mes', $record->periodo_mes)
            ->delete();
    }

    public function invalidateForIndicator(Indicador $indicator): int
    {
        return IndicadorCache::whereIn('indicador_id', $this->transitiveIds($indicator))->delete();
    }

    /**
     * @return array<int, int>
     */
    public function transitiveIds(Indicador $indicator): array
    {
        $indicators = Indicador::with('formulas')->get();
        $graph = [];
        foreach ($indicators as $item) {
            $expression = $item->formulas->first()?->expresion;
            $graph[$item->codigo] = is_array($expression) ? $this->validator->indicatorCodesIn($expression) : [];
        }

        $ids = [$indicator->id => true];
        $changed = true;
        while ($changed) {
            $changed = false;
            foreach ($indicators as $item) {
                if (isset($ids[$item->id])) {
                    continue;
                }
                foreach ($graph[$item->codigo] ?? [] as $dependencyCode) {
                    $dependency = $indicators->firstWhere('codigo', $dependencyCode);
                    if ($dependency && isset($ids[$dependency->id])) {
                        $ids[$item->id] = true;
                        $changed = true;
                        break;
                    }
                }
            }
        }

        return array_map('intval', array_keys($ids));
    }
}
