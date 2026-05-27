<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EphDataset extends Model
{
    use SoftDeletes;

    protected $table = 'estadistica.eph_datasets';

    protected $fillable = [
        'titulo', 'categoria', 'anio', 'fuente',
        'descripcion', 'datos', 'columnas', 'total_filas', 'cargado_por',
    ];

    /**
     * Excluir el campo JSONB pesado por defecto.
     * Usar ->withDatos() o ->select(['datos']) cuando realmente se necesite.
     */
    protected $hidden = ['datos'];

    protected $casts = [
        'datos'   => 'array',
        'columnas'=> 'array',
    ];

    const CATEGORIAS = [
        'ingreso_familiar' => 'Ingreso Familiar',
        'vivienda'         => 'Vivienda e Inventario de Bienes Duraderos',
        'poblacion'        => 'Población',
        'ipm'              => 'Índice de Pobreza Multidimensional',
        'otro'             => 'Otro',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function cargadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cargado_por');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Incluir el campo datos (JSONB pesado) explícitamente cuando se necesite.
     * Uso: EphDataset::withDatos()->find($id)
     */
    public function scopeWithDatos($query)
    {
        return $query->addSelect('datos');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function categoriaLabel(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? ucfirst($this->categoria ?? 'Sin categoría');
    }

    /**
     * Detecta automáticamente las columnas del JSON cargado
     */
    public static function detectarColumnas(array $datos): array
    {
        if (empty($datos)) return [];
        $primera = is_array($datos[0]) ? $datos[0] : (array) $datos[0];
        return array_keys($primera);
    }
}
