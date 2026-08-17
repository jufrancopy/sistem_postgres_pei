<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicadorCache extends Model
{
    protected $table = 'bioestadistica.indicador_cache';

    protected $guarded = ['id'];

    protected $casts = [
        'valor' => 'decimal:4',
        'calculado_at' => 'datetime',
    ];

    public function indicador(): BelongsTo
    {
        return $this->belongsTo(Indicador::class);
    }

    public function formula(): BelongsTo
    {
        return $this->belongsTo(IndicadorFormula::class, 'formula_id');
    }

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }
}
