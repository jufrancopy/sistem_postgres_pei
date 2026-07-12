<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;

class PgnEstructura extends Model
{
    protected $table = 'planificacion.pgn_estructura';

    protected $fillable = ['anio', 'orden', 'nombre', 'descripcion', 'activo'];

    protected $casts = ['activo' => 'boolean', 'anio' => 'integer', 'orden' => 'integer'];

    public function nodos()
    {
        return $this->hasMany(PgnNodo::class, 'pgn_estructura_id');
    }

    // Niveles de un año ordenados
    public static function deAnio(int $anio)
    {
        return static::where('anio', $anio)->where('activo', true)->orderBy('orden')->get();
    }

    // Años disponibles
    public static function aniosDisponibles()
    {
        return static::selectRaw('DISTINCT anio')->orderByDesc('anio')->pluck('anio');
    }
}
