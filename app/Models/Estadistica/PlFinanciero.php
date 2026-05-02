<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlFinanciero extends Model
{
    protected $table = 'estadistica.pl_financiero';

    protected $fillable = [
        'extracto_id', 'periodo_id',
        'total_activos', 'total_pasivos',
        'relacion_activo_pasivo', 'tasa_activo_pasivo',
    ];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    // Calcular relación automáticamente antes de guardar
    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->total_pasivos > 0) {
                $model->relacion_activo_pasivo = round($model->total_activos / $model->total_pasivos, 4);
                $model->tasa_activo_pasivo     = round($model->total_activos / $model->total_pasivos, 4);
            }
        });
    }

    public static function evolucionAnual(int $anio): \Illuminate\Support\Collection
    {
        return self::join('estadistica.siess_periodos as p', 'p.id', '=', 'estadistica.pl_financiero.periodo_id')
            ->where('p.anio', $anio)
            ->selectRaw('p.mes, estadistica.pl_financiero.total_activos, estadistica.pl_financiero.total_pasivos, estadistica.pl_financiero.relacion_activo_pasivo')
            ->orderBy('p.mes')
            ->get();
    }
}
