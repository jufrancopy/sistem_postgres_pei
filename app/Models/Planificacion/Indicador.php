<?php

namespace App\Models\Planificacion;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Planificacion\Pei\PeiProfile;

class Indicador extends Model
{
    protected $table = 'planificacion.indicadores';

    protected $fillable = [
        'pei_profile_id',
        'nombre', 'codigo_letras', 'codigo_numeros',
        'dimension', 'ambito',
        'descripcion', 'variables', 'formula', 'unidad_medida',
        'frecuencia', 'frecuencia_otro', 'cobertura', 'sentido',
        'linea_base_anio', 'linea_base_valor',
        'metas',
        'fuente', 'dependencia_responsable', 'comentarios',
    ];

    protected $casts = [
        'metas'          => 'array',
        'linea_base_anio'=> 'integer',
    ];

    const DIMENSIONES = [
        'eficiencia' => 'Eficiencia',
        'eficacia'   => 'Eficacia',
        'calidad'    => 'Calidad',
        'economia'   => 'Economía',
    ];

    const AMBITOS = [
        'objetivo_estrategico' => 'Objetivo Estratégico',
        'objetivo_especifico'  => 'Objetivo Específico',
        'accion_estrategica'   => 'Acción Estratégica',
        'accion_operativa'     => 'Acción Operativa',
    ];

    const FRECUENCIAS = [
        'mensual'     => 'Mensual',
        'trimestral'  => 'Trimestral',
        'semestral'   => 'Semestral',
        'anual'       => 'Anual',
        'otro'        => 'Otro',
    ];

    const COBERTURAS = [
        'nacional'      => 'Nacional',
        'regional'      => 'Regional',
        'departamental' => 'Departamental',
        'municipal'     => 'Municipal',
    ];

    public function codigoCompleto(): string
    {
        $letras  = strtoupper(trim($this->codigo_letras  ?? ''));
        $numeros = trim($this->codigo_numeros ?? '');
        if ($letras && $numeros) return "{$letras}-{$numeros}";
        return $letras ?: $numeros ?: '—';
    }

    public function perfil()
    {
        return $this->belongsTo(PeiProfile::class, 'pei_profile_id');
    }
}
