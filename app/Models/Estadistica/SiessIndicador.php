<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiessIndicador extends Model
{
    protected $table = 'estadistica.siess_indicadores';

    protected $fillable = ['modulo_id', 'codigo', 'nombre', 'descripcion', 'unidad', 'tipo_carga', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(SiessModulo::class, 'modulo_id');
    }

    public function extractos(): HasMany
    {
        return $this->hasMany(SiessExtracto::class, 'indicador_id');
    }
}
