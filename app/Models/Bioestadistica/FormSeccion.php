<?php

namespace App\Models\Bioestadistica;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormSeccion extends BioestadisticaModel
{
    protected $table = 'bioestadistica.form_secciones';

    public function formulario(): BelongsTo
    {
        return $this->belongsTo(Formulario::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class, 'seccion_id')->orderBy('orden');
    }
}
