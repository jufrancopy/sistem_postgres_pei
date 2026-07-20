<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplejidadTipo extends Model
{
    protected $table = 'complejidad_tipos';

    protected $fillable = [
        'grado', 'nombre', 'nombre_legacy', 'nivel_atencion', 'tipo_establecimiento',
        'es_hospitalario', 'requiere_internacion', 'requiere_quirofano',
        'requiere_uti', 'requiere_urgencias', 'color', 'activo',
    ];

    protected $casts = [
        'es_hospitalario'    => 'boolean',
        'requiere_internacion' => 'boolean',
        'requiere_quirofano' => 'boolean',
        'requiere_uti'       => 'boolean',
        'requiere_urgencias' => 'boolean',
        'activo'             => 'boolean',
    ];

    public function establecimientos(): HasMany
    {
        return $this->hasMany(Establecimiento::class, 'complejidad_tipo_id');
    }

    public static function activos()
    {
        return static::where('activo', true)->orderBy('grado');
    }

    public static function paraSelect(): array
    {
        return static::activos()->get()->mapWithKeys(fn($t) => [
            $t->id => "Grado {$t->grado} - {$t->nombre}"
        ])->toArray();
    }
}
