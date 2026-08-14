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
}
