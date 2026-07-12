<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;

class MarcoTipo extends Model
{
    protected $table = 'planificacion.marco_tipos';

    protected $fillable = ['clave', 'label', 'color', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    // Paleta disponible para el frontend (hex)
    const COLORES = [
        '#dc3545' => 'Rojo',
        '#e85d04' => 'Naranja fuerte',
        '#f48c06' => 'Naranja',
        '#ffc107' => 'Amarillo',
        '#2dc653' => 'Verde claro',
        '#28a745' => 'Verde',
        '#0f7b45' => 'Verde oscuro',
        '#17a2b8' => 'Celeste',
        '#0d6efd' => 'Azul',
        '#1976d2' => 'Azul medio',
        '#1a237e' => 'Azul oscuro',
        '#7c3aed' => 'Violeta',
        '#c026d3' => 'Fucsia',
        '#db2777' => 'Rosa',
        '#6c757d' => 'Gris',
        '#495057' => 'Gris oscuro',
        '#343a40' => 'Negro',
        '#6b4226' => 'Marrón',
        '#0e7490' => 'Teal',
        '#059669' => 'Esmeralda',
    ];

    public function marcos()
    {
        return $this->hasMany(MarcoReferencial::class, 'tipo', 'clave');
    }

    // Calcula si el texto sobre este color debe ser blanco o negro
    public function colorTexto(): string
    {
        $hex = ltrim($this->color, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $luminancia = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        return $luminancia > 0.55 ? '#000000' : '#ffffff';
    }

    public function toSelect2(): array
    {
        return [
            'id'         => $this->clave,
            'text'       => strtoupper($this->clave) . ' — ' . $this->label,
            'color'      => $this->color,
            'colorTexto' => $this->colorTexto(),
            'label'      => $this->label,
        ];
    }
}
