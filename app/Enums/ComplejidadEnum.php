<?php

namespace App\Enums;

enum ComplejidadEnum: string
{
    case NO_HOSPITALARIO_BAJA    = 'No Hospitalario de Baja Complejidad';
    case NO_HOSPITALARIO_MEDIANA = 'No Hospitalario de Mediana Complejidad';
    case HOSPITALARIO_1_BAJA     = 'Hospitalario 1 Baja Complejidad';
    case HOSPITALARIO_2_MEDIANA  = 'Hospitalario 2 Mediana Complejidad';
    case HOSPITALARIO_3_ALTA     = 'Hospitalario 3 Alta Complejidad';

    public function nivelAtencion(): int
    {
        return match($this) {
            self::NO_HOSPITALARIO_BAJA,
            self::NO_HOSPITALARIO_MEDIANA,
            self::HOSPITALARIO_1_BAJA    => 1,
            self::HOSPITALARIO_2_MEDIANA => 2,
            self::HOSPITALARIO_3_ALTA    => 3,
        };
    }

    public function gradoComplejidad(): int
    {
        return match($this) {
            self::NO_HOSPITALARIO_BAJA    => 1,
            self::NO_HOSPITALARIO_MEDIANA => 2,
            self::HOSPITALARIO_1_BAJA     => 1,
            self::HOSPITALARIO_2_MEDIANA  => 2,
            self::HOSPITALARIO_3_ALTA     => 3,
        };
    }

    public function esHospitalario(): bool
    {
        return match($this) {
            self::NO_HOSPITALARIO_BAJA,
            self::NO_HOSPITALARIO_MEDIANA => false,
            default                       => true,
        };
    }

    public function requiereInternacion(): bool
    {
        return $this->esHospitalario();
    }

    public function requiereUrgencias(): bool
    {
        return $this->esHospitalario();
    }

    public function requiereQuirofano(): bool
    {
        return $this->esHospitalario();
    }

    public function requiereUTI(): bool
    {
        return match($this) {
            self::HOSPITALARIO_2_MEDIANA,
            self::HOSPITALARIO_3_ALTA => true,
            default                   => false,
        };
    }

    public function requiereLaboratorio(): bool
    {
        return $this->esHospitalario();
    }

    public function requiereImagenes(): bool
    {
        return $this->esHospitalario();
    }

    public function requiereFarmacia(): bool
    {
        return true;
    }

    public function requiereVacunatorio(): bool
    {
        return true;
    }

    public static function fromString(string $texto): ?self
    {
        return match(trim($texto)) {
            'No Hospitalario de Baja Complejidad'    => self::NO_HOSPITALARIO_BAJA,
            'No Hospitalario de Mediana Complejidad' => self::NO_HOSPITALARIO_MEDIANA,
            'Hospitalario 1 Baja Complejidad'        => self::HOSPITALARIO_1_BAJA,
            'Hospitalario 2 Mediana Complejidad'     => self::HOSPITALARIO_2_MEDIANA,
            'Hospitalario 3 Alta Complejidad'        => self::HOSPITALARIO_3_ALTA,
            default                                  => null,
        };
    }

    public function label(): string
    {
        return match($this) {
            self::NO_HOSPITALARIO_BAJA    => 'Nivel 1 - No Hospitalario Baja',
            self::NO_HOSPITALARIO_MEDIANA => 'Nivel 1 - No Hospitalario Mediana',
            self::HOSPITALARIO_1_BAJA     => 'Nivel 1 - Hospitalario Baja',
            self::HOSPITALARIO_2_MEDIANA  => 'Nivel 2 - Hospitalario Mediana',
            self::HOSPITALARIO_3_ALTA     => 'Nivel 3 - Hospitalario Alta',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NO_HOSPITALARIO_BAJA    => '#22c55e',
            self::NO_HOSPITALARIO_MEDIANA => '#84cc16',
            self::HOSPITALARIO_1_BAJA     => '#eab308',
            self::HOSPITALARIO_2_MEDIANA  => '#f97316',
            self::HOSPITALARIO_3_ALTA     => '#ef4444',
        };
    }
}
