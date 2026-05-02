<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiessModulo extends Model
{
    protected $table = 'estadistica.siess_modulos';

    protected $fillable = ['codigo', 'nombre', 'descripcion', 'periodicidad', 'activo', 'orden'];

    protected $casts = ['activo' => 'boolean'];

    public function indicadores(): HasMany
    {
        return $this->hasMany(SiessIndicador::class, 'modulo_id');
    }

    public function extractos(): HasMany
    {
        return $this->hasMany(SiessExtracto::class, 'modulo_id');
    }

    public function resumenEstados(): array
    {
        $counts = $this->extractos()
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        return [
            'borrador'             => $counts['borrador']             ?? 0,
            'pendiente_validacion' => $counts['pendiente_validacion'] ?? 0,
            'aprobado'             => $counts['aprobado']             ?? 0,
            'objetado'             => $counts['objetado']             ?? 0,
            'aprobado_silencio'    => $counts['aprobado_silencio']    ?? 0,
        ];
    }
}
