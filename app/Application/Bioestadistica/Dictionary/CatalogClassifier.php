<?php

namespace App\Application\Bioestadistica\Dictionary;

use Illuminate\Support\Str;

class CatalogClassifier
{
    /** Filas amarillas SP9: columnas del formulario, no catálogo. */
    private const SP9_COLUMNAS = [
        'CONSULTA DE URGENCIAS ADULTOS',
        'OBSERVACION ADULTOS',
        'PROCEDIMIENTOS URGENCIAS ADULTOS',
        'CONSULTA URGENCIAS PEDIATRICA',
        'OBSERVACION PEDIATRICA',
        'PROCEDIMIENTOS URGENCIAS PEDIATRICAS',
    ];

    /** Filas amarillas SP8: columnas del cruce vacunación. */
    private const SP8_COLUMNAS = [
        'MENORES DE 1 AÑO',
        '1 A 3 AÑOS',
        '4 A 14 AÑOS',
        '15 A 59 AÑOS',
        '60 Y MAS',
        'FEMENINO',
        'MASCULINO',
        'NRO.DE BENEFICIAIOS',
        'NRO.DE BENEFICIARIOS',
    ];

    public function isFormColumn(string $codigoDominio, string $tipoRegistro, string $prestacion): bool
    {
        $prestacionKey = $this->key($prestacion);
        $tipoKey = $this->key($tipoRegistro);

        if (in_array($prestacionKey, array_map([$this, 'key'], self::SP9_COLUMNAS), true)) {
            return str_contains($tipoKey, 'URGENCIA');
        }

        if ($codigoDominio === '16' && str_contains($tipoKey, 'BENEFICIARIOS')) {
            return in_array($prestacionKey, array_map([$this, 'key'], self::SP8_COLUMNAS), true);
        }

        return false;
    }

    public function layoutForColumn(string $codigoDominio, string $tipoRegistro): ?string
    {
        $tipoKey = $this->key($tipoRegistro);

        if (str_contains($tipoKey, 'URGENCIA')) {
            return 'matriz';
        }

        if ($codigoDominio === '16' && str_contains($tipoKey, 'BENEFICIARIOS')) {
            return 'cruce';
        }

        return null;
    }

    public function columnDefinitions(string $codigoDominio, string $tipoRegistro): array
    {
        $tipoKey = $this->key($tipoRegistro);

        if (str_contains($tipoKey, 'URGENCIA')) {
            return [
                ['code' => 'consulta', 'label' => 'Consulta', 'type' => 'integer', 'min' => 0],
                ['code' => 'observacion', 'label' => 'Observación', 'type' => 'integer', 'min' => 0],
                ['code' => 'procedimientos', 'label' => 'Procedimientos', 'type' => 'integer', 'min' => 0],
            ];
        }

        if ($codigoDominio === '16' && str_contains($tipoKey, 'BENEFICIARIOS')) {
            return [
                ['code' => 'menores_1', 'label' => 'Menores de 1 año', 'type' => 'integer', 'min' => 0],
                ['code' => '1_3', 'label' => '1 a 3 años', 'type' => 'integer', 'min' => 0],
                ['code' => '4_14', 'label' => '4 a 14 años', 'type' => 'integer', 'min' => 0],
                ['code' => '15_59', 'label' => '15 a 59 años', 'type' => 'integer', 'min' => 0],
                ['code' => '60_mas', 'label' => '60 y más', 'type' => 'integer', 'min' => 0],
                ['code' => 'femenino', 'label' => 'Femenino', 'type' => 'integer', 'min' => 0],
                ['code' => 'masculino', 'label' => 'Masculino', 'type' => 'integer', 'min' => 0],
                ['code' => 'beneficiarios', 'label' => 'Nro. de beneficiarios', 'type' => 'integer', 'min' => 0],
            ];
        }

        return [];
    }

    public function classify(string $codigoDominio, string $tipoRegistro, string $prestacion): ?CatalogType
    {
        if ($this->isFormColumn($codigoDominio, $tipoRegistro, $prestacion)) {
            return null;
        }

        $tipoKey = $this->key($tipoRegistro);
        $codigo = strtolower(trim($codigoDominio));

        if ($this->isEspecialidadMedica($codigo, $tipoKey)) {
            return CatalogType::EspecialidadMedica;
        }

        if (in_array($codigo, ['10', '11', '12'], true)) {
            return CatalogType::Determinacion;
        }

        if ($codigo === '14') {
            return CatalogType::Procedimiento;
        }

        if ($codigo === '16' && str_contains($tipoKey, 'CLASIFICACION DE VACUNACION')) {
            return CatalogType::Vacuna;
        }

        return CatalogType::Prestacion;
    }

    public function especialidadContexto(string $tipoRegistro, string $prestacion = ''): string
    {
        $tipoKey = $this->key($tipoRegistro);

        if ($prestacion !== '' && $this->isOdontologiaConsultaName($prestacion) && ! str_contains($tipoKey, 'URGENCIA')) {
            return 'odontologia_consulta';
        }

        return match (true) {
            str_contains($tipoKey, 'CONVENIO') => 'convenio',
            str_contains($tipoKey, 'TELECONSULT') => 'teleconsulta',
            str_contains($tipoKey, 'INTERCONSULT') => 'interconsulta',
            str_contains($tipoKey, 'URGENCIA') => 'urgencia',
            default => 'ambulatorio',
        };
    }

    public function especialidadBase(string $nombre, string $contexto): ?string
    {
        if ($contexto === 'urgencia') {
            return preg_replace('/^URGENCIAS?\s+/iu', '', preg_replace('/^URGENCIA\s+/iu', '', trim($nombre))) ?: null;
        }

        if ($contexto === 'teleconsulta') {
            return preg_replace('/^TELE\s+/iu', '', trim($nombre)) ?: null;
        }

        if ($contexto === 'odontologia_consulta' || str_starts_with($this->key($nombre), 'ODONTOLOG')) {
            return 'Odontología';
        }

        return trim($nombre) ?: null;
    }

    public function determinacionFamilia(string $codigoDominio): string
    {
        return match ($codigoDominio) {
            '10' => 'laboratorio',
            '11' => 'alta_complejidad',
            '12' => 'baja_complejidad',
            default => 'laboratorio',
        };
    }

    public function procedimientoCategoria(string $tipoRegistro): string
    {
        $tipoKey = $this->key($tipoRegistro);

        return match (true) {
            str_contains($tipoKey, 'ODONTOLOG') => 'odontologico',
            str_contains($tipoKey, 'BANCO DE SANGRE') => 'banco_sangre',
            str_contains($tipoKey, 'PLANIF') => 'planificacion_familiar',
            str_contains($tipoKey, 'FISIOTERAPIA') => 'fisioterapia',
            str_contains($tipoKey, 'SALUD MENTAL') => 'salud_mental',
            default => 'otro',
        };
    }

    public function prestacionFamilia(string $codigoDominio, string $tipoRegistro): string
    {
        if ($codigoDominio === 'x') {
            return 'dominio_x';
        }

        $tipoKey = $this->key($tipoRegistro);

        return match ($codigoDominio) {
            '13' => 'enfermeria',
            '15' => 'preventiva',
            '17' => 'programa_salud',
            '18' => 'apoyo_servicios',
            'x' => 'dominio_x',
            default => match (true) {
                str_contains($tipoKey, 'CHARLA') => 'preventiva',
                str_contains($tipoKey, 'MEDICAMENT') => 'medicamentos',
                str_contains($tipoKey, 'HOSPITAL') || str_contains($tipoKey, 'EGRESO') => 'gestion_hospitalaria',
                default => 'metrica',
            },
        };
    }

    public function isIndicador(string $codigoDominio, string $prestacion): bool
    {
        if ($codigoDominio !== 'x') {
            return false;
        }

        $key = $this->key($prestacion);

        return str_contains($key, 'TASA')
            || str_contains($key, 'PORCENTAJE')
            || str_contains($key, 'PROMEDIO')
            || str_contains($key, 'GIRO CAMA');
    }

    private function isEspecialidadMedica(string $codigo, string $tipoKey): bool
    {
        if ($codigo === '1' && (
            str_contains($tipoKey, 'CONSULTA POR ESPECIALIDAD')
            || str_contains($tipoKey, 'CONVENIO CONSULTAS')
            || str_contains($tipoKey, 'INTERCONSULT')
            || str_contains($tipoKey, 'TELECONSULT')
        )) {
            return true;
        }

        if ($codigo === '2' && str_contains($tipoKey, 'INTERCONSULT')) {
            return true;
        }

        if ($codigo === '4' && str_contains($tipoKey, 'URGENCIA')) {
            return true;
        }

        return false;
    }

    public function isOdontologiaConsultaName(string $nombre): bool
    {
        return str_starts_with($this->key($nombre), 'ODONTOLOG');
    }

    private function key(string $value): string
    {
        return Str::upper(Str::ascii(trim($value)));
    }
}
