<?php

namespace App\Application\Bioestadistica\Sync;

/**
 * Acumula claves canónicas tocadas durante el upsert de Configuraciones
 * para poder podar (soft-delete) lo que ya no está en el origen.
 */
class CatalogSyncRegistry
{
    /** @var list<string> */
    private array $indicadores = [];

    /** @var list<string> */
    private array $reportes = [];

    /** @var list<string> */
    private array $dashboards = [];

    /** @var list<string> */
    private array $formularios = [];

    /** @var list<int> */
    private array $organos = [];

    /** @var list<string> */
    private array $organoTipos = [];

    public function reset(): void
    {
        $this->indicadores = [];
        $this->reportes = [];
        $this->dashboards = [];
        $this->formularios = [];
        $this->organos = [];
        $this->organoTipos = [];
    }

    public function rememberIndicador(string $codigo): void
    {
        $this->indicadores[] = $codigo;
    }

    public function rememberReporte(string $codigo): void
    {
        $this->reportes[] = $codigo;
    }

    public function rememberDashboard(string $codigo): void
    {
        $this->dashboards[] = $codigo;
    }

    public function rememberFormulario(string $codigo): void
    {
        $this->formularios[] = $codigo;
    }

    public function rememberOrgano(int $id): void
    {
        $this->organos[] = $id;
    }

    public function rememberOrganoTipo(string $codigo): void
    {
        $this->organoTipos[] = $codigo;
    }

    /** @return list<string> */
    public function indicadores(): array
    {
        return array_values(array_unique($this->indicadores));
    }

    /** @return list<string> */
    public function reportes(): array
    {
        return array_values(array_unique($this->reportes));
    }

    /** @return list<string> */
    public function dashboards(): array
    {
        return array_values(array_unique($this->dashboards));
    }

    /** @return list<string> */
    public function formularios(): array
    {
        return array_values(array_unique($this->formularios));
    }

    /** @return list<int> */
    public function organos(): array
    {
        return array_values(array_unique($this->organos));
    }

    /** @return list<string> */
    public function organoTipos(): array
    {
        return array_values(array_unique($this->organoTipos));
    }
}
