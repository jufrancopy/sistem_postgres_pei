<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglaSeccionFormulario extends Model
{
    protected $table = 'regla_seccion_formularios';

    protected $fillable = [
        'tipologia_clasificacion', 'complejidad', 'formulario_seccion_id',
        'requerida', 'aplica', 'condicion', 'nota',
    ];

    protected $casts = [
        'requerida' => 'boolean',
        'aplica'    => 'boolean',
    ];

    public function seccion(): BelongsTo
    {
        return $this->belongsTo(FormularioSeccion::class, 'formulario_seccion_id');
    }

    public function scopeParaTipologia($query, ?string $tipologia)
    {
        if (empty($tipologia)) {
            return $query;
        }
        return $query->whereRaw('UPPER(TRIM(tipologia_clasificacion)) = UPPER(TRIM(?))', [trim($tipologia)]);
    }

    public function scopeAplican($query)
    {
        return $query->where('aplica', true);
    }

    public function scopeRequeridas($query)
    {
        return $query->where('requerida', true);
    }
}
