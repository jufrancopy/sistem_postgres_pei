<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FormularioSeccion extends Model
{
    protected $table = 'formulario_secciones';

    protected $fillable = ['seccion', 'sub_seccion', 'orden', 'activa'];

    protected $casts = ['activa' => 'boolean', 'orden' => 'integer'];

    public function preguntas(): HasMany
    {
        return $this->hasMany(FormularioPregunta::class, 'formulario_seccion_id')->orderBy('orden');
    }

    public function reglas(): HasMany
    {
        return $this->hasMany(ReglaSeccionFormulario::class, 'formulario_seccion_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->sub_seccion
            ? "{$this->seccion} > {$this->sub_seccion}"
            : $this->seccion;
    }

    public function getSlugAttribute(): string
    {
        return Str::slug($this->sub_seccion ?? $this->seccion, '_');
    }
}
