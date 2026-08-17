<?php

namespace App\Application\Bioestadistica\Imports;

use InvalidArgumentException;

class BioestadisticaDedicatedImporter
{
    public const VARIABLES_SALUD = 'variables_salud';

    public const ESTABLECIMIENTOS_DIM = 'establecimientos_dim';

    public const FORMULARIOS_SP = 'formularios_sp';

    public function __construct(
        private readonly VariablesSaludImporter $variablesSaludImporter,
        private readonly EstablecimientosDimImporter $establecimientosDimImporter,
        private readonly FormulariosSpImporter $formulariosSpImporter,
    ) {
    }

    /**
     * @param  array<string, string>  $decisions
     * @return array<string, mixed>
     */
    public function import(string $filePath, string $type, array $decisions = []): array
    {
        $this->validateFile($filePath);

        return match ($type) {
            self::VARIABLES_SALUD => $this->variablesSaludImporter->import($filePath),
            self::ESTABLECIMIENTOS_DIM => $this->establecimientosDimImporter->import($filePath),
            self::FORMULARIOS_SP => $this->formulariosSpImporter->import($filePath, $decisions),
            default => throw new InvalidArgumentException(
                "Tipo de importación '{$type}' inválido."
            ),
        };
    }

    /**
     * Alias en español para consumidores del módulo.
     *
     * @param  array<string, string>  $decisions
     * @return array<string, mixed>
     */
    public function importar(string $filePath, string $type, array $decisions = []): array
    {
        return $this->import($filePath, $type, $decisions);
    }

    private function validateFile(string $filePath): void
    {
        if ($filePath === '' || ! is_file($filePath)) {
            throw new InvalidArgumentException("No se encontró el archivo de importación: {$filePath}");
        }

        if (! is_readable($filePath)) {
            throw new InvalidArgumentException("El archivo de importación no se puede leer: {$filePath}");
        }

        $extension = strtolower((string) pathinfo($filePath, PATHINFO_EXTENSION));
        if (! in_array($extension, ['xls', 'xlsx'], true)) {
            throw new InvalidArgumentException(
                "El archivo '{$filePath}' no es un Excel válido (.xls o .xlsx)."
            );
        }
    }
}
