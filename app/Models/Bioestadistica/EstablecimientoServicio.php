<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstablecimientoServicio extends BioestadisticaModel
{
    protected $table = 'bioestadistica.establecimiento_servicios';

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(EstructuraDepartamento::class, 'departamento_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(EstructuraServicio::class, 'servicio_id');
    }

    public function etiqueta(): string
    {
        return trim(($this->departamento?->nombre ?? '').' / '.($this->servicio?->nombre ?? ''), ' /');
    }
}
