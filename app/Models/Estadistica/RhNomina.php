<?php

namespace App\Models\Estadistica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RhNomina extends Model
{
    protected $table = 'estadistica.rh_nomina';

    protected $fillable = [
        'extracto_id', 'periodo_id', 'cedula', 'cargo', 'grupo_ocupacional',
        'dependencia', 'carga_horaria', 'remuneracion_presupuestada',
        'remuneracion_devengada', 'tiene_discapacidad', 'sexo',
    ];

    protected $casts = ['tiene_discapacidad' => 'boolean'];

    public function extracto(): BelongsTo { return $this->belongsTo(SiessExtracto::class, 'extracto_id'); }
    public function periodo(): BelongsTo  { return $this->belongsTo(SiessPeriodo::class, 'periodo_id'); }

    public static function resumenPorPeriodo(int $periodoId): array
    {
        $base = self::where('periodo_id', $periodoId);
        return [
            'total'                    => $base->count(),
            'mujeres'                  => $base->where('sexo', 'F')->count(),
            'hombres'                  => $base->where('sexo', 'M')->count(),
            'con_discapacidad'         => $base->where('tiene_discapacidad', true)->count(),
            'masa_salarial_presup'     => $base->sum('remuneracion_presupuestada'),
            'masa_salarial_devengada'  => $base->sum('remuneracion_devengada'),
            'por_grupo'                => self::where('periodo_id', $periodoId)
                ->selectRaw('grupo_ocupacional, COUNT(*) as total, AVG(remuneracion_devengada) as salario_promedio')
                ->groupBy('grupo_ocupacional')->orderByDesc('total')->get(),
        ];
    }
}
