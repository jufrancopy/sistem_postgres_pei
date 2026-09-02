<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\DeterminacionEstudio;
use App\Models\Bioestadistica\EspecialidadMedica;
use App\Models\Bioestadistica\Prestacion;
use App\Models\Bioestadistica\Procedimiento;
use App\Models\Bioestadistica\Vacuna;
use InvalidArgumentException;

enum CatalogType: string
{
    case EspecialidadMedica = 'especialidad_medica';
    case Determinacion = 'determinacion';
    case Procedimiento = 'procedimiento';
    case Vacuna = 'vacuna';
    case Prestacion = 'prestacion';

    public function modelClass(): string
    {
        return match ($this) {
            self::EspecialidadMedica => EspecialidadMedica::class,
            self::Determinacion => DeterminacionEstudio::class,
            self::Procedimiento => Procedimiento::class,
            self::Vacuna => Vacuna::class,
            self::Prestacion => Prestacion::class,
        };
    }

    public function table(): string
    {
        return match ($this) {
            self::EspecialidadMedica => 'bioestadistica.especialidades_medicas',
            self::Determinacion => 'bioestadistica.determinaciones_estudios',
            self::Procedimiento => 'bioestadistica.procedimientos',
            self::Vacuna => 'bioestadistica.vacunas',
            self::Prestacion => 'bioestadistica.prestaciones',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::EspecialidadMedica => 'Especialidad médica',
            self::Determinacion => 'Determinación / estudio',
            self::Procedimiento => 'Procedimiento',
            self::Vacuna => 'Vacuna',
            self::Prestacion => 'Prestación general',
        };
    }

    public static function tryFromString(?string $value): ?self
    {
        if ($value === null || $value === '') {
            return null;
        }

        return self::tryFrom($value) ?? throw new InvalidArgumentException("Tipo de catálogo desconocido: {$value}");
    }

    public static function totalItemsCount(): int
    {
        $total = 0;
        foreach (self::cases() as $type) {
            $total += $type->modelClass()::query()->count();
        }

        return $total;
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $type) {
            $options[$type->value] = $type->label();
        }

        return $options;
    }
}
