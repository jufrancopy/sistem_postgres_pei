<?php

namespace App\Enums;

enum TipoEstablecimientoEnum: string
{
    case PS  = 'PS';
    case HR  = 'HR';
    case US  = 'US';
    case CE  = 'CE';
    case CP  = 'CP';
    case HO  = 'HO';
    case HC  = 'HC';
    case OT  = 'OT';
    case CO  = 'CO';
    case ADM = 'ADM';
    case H   = 'H';

    public function label(): string
    {
        return match($this) {
            self::PS  => 'Puesto Sanitario',
            self::HR  => 'Hospital Regional',
            self::US  => 'Unidad Sanitaria',
            self::CE  => 'Centro',
            self::CP  => 'Clínica Periférica',
            self::HO  => 'Hospital',
            self::HC  => 'Hospital Especializado',
            self::OT  => 'Otros',
            self::CO  => 'Convenio',
            self::ADM => 'Administrativo',
            self::H   => 'Hospital',
        };
    }

    public function esAsistencial(): bool
    {
        return in_array($this, [self::PS, self::HR, self::US, self::CE, self::CP, self::HO, self::HC, self::H]);
    }
}
