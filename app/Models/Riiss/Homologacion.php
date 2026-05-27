<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Homologacion extends Model
{
    protected $table = 'homologaciones';

    protected $fillable = [
        'alias_fuente', 'id_establecimiento_destino', 'fuente',
    ];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento_destino', 'id_establecimiento');
    }

    public function scopePorFuente($query, string $fuente)
    {
        return $query->where('fuente', $fuente);
    }

    /**
     * Resolver un alias a su establecimiento.
     */
    public static function resolverAlias(string $alias): ?Establecimiento
    {
        $homologacion = static::where('alias_fuente', 'ILIKE', $alias)->first();
        return $homologacion?->establecimiento;
    }
}
