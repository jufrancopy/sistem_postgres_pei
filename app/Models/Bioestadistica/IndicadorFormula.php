<?php

namespace App\Models\Bioestadistica;

use App\Models\Bioestadistica\Concerns\Auditable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicadorFormula extends Model
{
    use Auditable;
    protected $table = 'bioestadistica.indicador_formulas';

    protected $guarded = ['id'];

    protected $casts = [
        'expresion' => 'array',
        'vigente_desde' => 'date',
        'vigente_hasta' => 'date',
    ];

    public function indicador(): BelongsTo
    {
        return $this->belongsTo(Indicador::class);
    }

    public function vigenteEn(CarbonInterface $periodo): bool
    {
        return (! $this->vigente_desde || $this->vigente_desde->lte($periodo))
            && (! $this->vigente_hasta || $this->vigente_hasta->gte($periodo));
    }
}
