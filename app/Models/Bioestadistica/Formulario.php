<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Formulario extends BioestadisticaModel
{
    protected $table = 'bioestadistica.formularios';

    public function secciones(): HasMany
    {
        return $this->hasMany(FormSeccion::class)->orderBy('orden');
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }

    public function scopeOrdenSp($query)
    {
        return $query->orderByRaw(
            "CASE WHEN codigo ~ '^SP[0-9]+$' THEN CAST(substring(codigo from 3) AS integer) ELSE 100000 END, codigo"
        );
    }
}
