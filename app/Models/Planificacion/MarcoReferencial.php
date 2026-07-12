<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;

class MarcoReferencial extends Model
{
    protected $table = 'planificacion.marcos_referenciales';

    protected $fillable = ['nombre', 'tipo', 'descripcion', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    const TIPOS_SUGERIDOS = ['pnd', 'ods', 'mecip', 'pgn', 'general'];

    public function scopeBuscar($query, string $termino)
    {
        return $query->where('activo', true)
            ->where(function ($q) use ($termino) {
                $q->whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($termino) . '%'])
                  ->orWhereRaw('LOWER(tipo) LIKE ?', ['%' . strtolower($termino) . '%']);
            });
    }

    public function toSelect2(): array
    {
        return [
            'id'   => $this->id,
            'text' => '[' . strtoupper($this->tipo) . '] ' . $this->nombre,
            'tipo' => $this->tipo,
        ];
    }
}
