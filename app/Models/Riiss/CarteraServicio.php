<?php

namespace App\Models\Riiss;

use Illuminate\Database\Eloquent\Model;

class CarteraServicio extends Model
{
    protected $table = 'cartera_servicios';

    protected $fillable = [
        'paciente_objetivo', 'nivel_atencion', 'grado_complejidad',
        'tipo_establecimiento', 'tipo_prestacion', 'objetivos_generales',
        'variable_prestacion', 'servicio', 'detalles', 'detalles_2',
        'descripcion', 'especialidad_1', 'especialidad_2',
        'patologias_por_sistema', 'patologias', 'encargado_profesional',
        'requerido', 'observacion', 'grupo_servicio',
        'codigo_formulario_relacionado',
        'aplica_puesto_sanitario', 'aplica_unidad_sanitaria',
        'aplica_clinica_periferica', 'aplica_hospital_baja',
        'aplica_hospital_mediana', 'aplica_hospital_alta',
    ];

    protected $casts = [
        'requerido'               => 'boolean',
        'aplica_puesto_sanitario' => 'boolean',
        'aplica_unidad_sanitaria' => 'boolean',
        'aplica_clinica_periferica' => 'boolean',
        'aplica_hospital_baja'    => 'boolean',
        'aplica_hospital_mediana' => 'boolean',
        'aplica_hospital_alta'    => 'boolean',
    ];

    // ─── SCOPES ──────────────────────────────────────────────────

    public function scopeParaNivel($query, int $nivel, int $grado)
    {
        return $query->where('nivel_atencion', '<=', $nivel)
                     ->where('grado_complejidad', '<=', $grado);
    }

    public function scopeRequeridos($query)
    {
        return $query->where('requerido', true);
    }

    public function scopeOpcionales($query)
    {
        return $query->where('requerido', false);
    }

    public function scopePorTipoPrestacion($query, string $tipo)
    {
        return $query->where('tipo_prestacion', $tipo);
    }

    public function scopePorGrupo($query, string $grupo)
    {
        return $query->where('grupo_servicio', $grupo);
    }

    public function scopeAplicaATipo($query, string $tipologia, string $complejidad)
    {
        $columna = match(true) {
            str_contains(strtoupper($tipologia), 'PUESTO SANITARIO') => 'aplica_puesto_sanitario',
            str_contains(strtoupper($tipologia), 'UNIDAD SANITARIA') => 'aplica_unidad_sanitaria',
            str_contains(strtoupper($tipologia), 'CLINICA PERIFERICA'),
            str_contains(strtoupper($tipologia), 'CLÍNICA PERIFÉRICA') => 'aplica_clinica_periferica',
            str_contains(strtoupper($tipologia), 'HOSPITAL') => match(true) {
                str_contains($complejidad, 'Alta')    => 'aplica_hospital_alta',
                str_contains($complejidad, 'Mediana') => 'aplica_hospital_mediana',
                default                               => 'aplica_hospital_baja',
            },
            default => null,
        };

        if ($columna) {
            $query->where($columna, true);
        }

        return $query;
    }

    // ─── METHODS ─────────────────────────────────────────────────

    public function aplicaPara(Establecimiento $est): bool
    {
        if ($est->nivel_atencion < $this->nivel_atencion) return false;
        if ($est->grado_complejidad < $this->grado_complejidad) return false;

        $tipologia = strtoupper($est->tipologia_clasificacion ?? '');

        return match(true) {
            str_contains($tipologia, 'PUESTO SANITARIO')  => $this->aplica_puesto_sanitario,
            str_contains($tipologia, 'UNIDAD SANITARIA')  => $this->aplica_unidad_sanitaria,
            str_contains($tipologia, 'CLINICA PERIFERICA'),
            str_contains($tipologia, 'CLÍNICA PERIFÉRICA') => $this->aplica_clinica_periferica,
            str_contains($tipologia, 'HOSPITAL') => match(true) {
                $est->grado_complejidad === 3 => $this->aplica_hospital_alta,
                $est->grado_complejidad === 2 => $this->aplica_hospital_mediana,
                default                       => $this->aplica_hospital_baja,
            },
            default => false,
        };
    }
}
