<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Establecimiento extends BioestadisticaModel
{
    protected $table = 'bioestadistica.establecimientos';

    protected $casts = [
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(Distrito::class);
    }

    public function microred(): BelongsTo
    {
        return $this->belongsTo(Microred::class);
    }

    public function tipoEstablecimiento(): BelongsTo
    {
        return $this->belongsTo(TipoEstablecimiento::class);
    }

    public function gradoComplejidad(): BelongsTo
    {
        return $this->belongsTo(GradoComplejidad::class);
    }

    public function areaGestion(): BelongsTo
    {
        return $this->belongsTo(AreaGestion::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    public function scopeBuscar(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $q, string $value) => $q
            ->where(fn (Builder $inner) => $inner
                ->where('nombre', 'ILIKE', "%{$value}%")
                ->orWhere('codigo', 'ILIKE', "%{$value}%")
                ->orWhere('codigo_sih', 'ILIKE', "%{$value}%")));
    }
}
