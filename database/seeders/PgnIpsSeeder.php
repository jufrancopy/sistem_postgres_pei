<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Planificacion\PgnEstructura;
use App\Models\Planificacion\PgnNodo;

/**
 * PgnIpsSeeder — Estructura PGN IPS 2024-2028
 * Basado en los 3 Resultados Intermedios de la Hoja 12:
 *   - Programa 14: Salud (Gs. 6.341.255.316.165)
 *   - Programa 15: Prestaciones Económicas (Gs. 6.385.106.032.434)
 *   - Programa 16: Gestión Administrativa (Gs. 467.065.260.973)
 */
class PgnIpsSeeder extends Seeder
{
    const ANIO = 2026;

    public function run(): void
    {
        $this->command->info('── Sembrando estructura PGN IPS ' . self::ANIO . '...');

        // ── Estructura de niveles ─────────────────────────────────────────────
        $niveles = [
            ['orden' => 1, 'nombre' => 'Programa',     'descripcion' => 'Nivel 1 — Programa presupuestario'],
            ['orden' => 2, 'nombre' => 'Subprograma',  'descripcion' => 'Nivel 2 — Subprograma'],
            ['orden' => 3, 'nombre' => 'Proyecto',     'descripcion' => 'Nivel 3 — Proyecto'],
            ['orden' => 4, 'nombre' => 'Actividad',    'descripcion' => 'Nivel hoja — Actividad vinculable al PEI'],
        ];

        foreach ($niveles as $n) {
            PgnEstructura::firstOrCreate(
                ['anio' => self::ANIO, 'nombre' => $n['nombre']],
                array_merge($n, ['anio' => self::ANIO, 'activo' => true])
            );
        }

        $ePrograma   = PgnEstructura::where(['anio' => self::ANIO, 'nombre' => 'Programa'])->first();
        $eSubprog    = PgnEstructura::where(['anio' => self::ANIO, 'nombre' => 'Subprograma'])->first();
        $eProyecto   = PgnEstructura::where(['anio' => self::ANIO, 'nombre' => 'Proyecto'])->first();
        $eActividad  = PgnEstructura::where(['anio' => self::ANIO, 'nombre' => 'Actividad'])->first();

        // ══ PROGRAMA 14 — SALUD ═══════════════════════════════════════════════
        $p14 = $this->nodo(null, $ePrograma->id, '14', 'Salud', 6341255316165);

        $sp1401 = $this->nodo($p14, $eSubprog->id, '14-01', 'Atención Médica y Sanitaria', 4500000000000);
        $sp1402 = $this->nodo($p14, $eSubprog->id, '14-02', 'Medicamentos e Insumos Médicos', 1000000000000);
        $sp1403 = $this->nodo($p14, $eSubprog->id, '14-03', 'Infraestructura y Equipamiento Hospitalario', 841255316165);

        // Proyectos bajo 14-01
        $pr140101 = $this->nodo($sp1401, $eProyecto->id, '14-01-01', 'Fortalecimiento de la Red Hospitalaria', 2500000000000);
        $pr140102 = $this->nodo($sp1401, $eProyecto->id, '14-01-02', 'Atención Primaria y Preventiva', 1200000000000);
        $pr140103 = $this->nodo($sp1401, $eProyecto->id, '14-01-03', 'Telemedicina y Salud Digital', 800000000000);

        // Actividades (nodos hoja)
        $this->nodo($pr140101, $eActividad->id, '14-01-01-001', 'Servicios de Prestaciones Sanitarias', 1500000000000);
        $this->nodo($pr140101, $eActividad->id, '14-01-01-002', 'Equipamiento de Hospitales Regionales', 1000000000000);
        $this->nodo($pr140102, $eActividad->id, '14-01-02-001', 'Clínicas Periféricas — Fortalecimiento', 700000000000);
        $this->nodo($pr140102, $eActividad->id, '14-01-02-002', 'Programas de Salud Preventiva y Vacunación', 500000000000);
        $this->nodo($pr140103, $eActividad->id, '14-01-03-001', 'Expediente Clínico Electrónico — Implementación', 500000000000);
        $this->nodo($pr140103, $eActividad->id, '14-01-03-002', 'Telemedicina en Centros Periféricos', 300000000000);

        // Proyectos bajo 14-02
        $pr140201 = $this->nodo($sp1402, $eProyecto->id, '14-02-01', 'Medicamentos Esenciales', 700000000000);
        $pr140202 = $this->nodo($sp1402, $eProyecto->id, '14-02-02', 'Trazabilidad y Logística Farmacéutica', 300000000000);

        $this->nodo($pr140201, $eActividad->id, '14-02-01-001', 'Adquisición de Medicamentos Esenciales', 700000000000);
        $this->nodo($pr140202, $eActividad->id, '14-02-02-001', 'Sistema de Trazabilidad Farmacéutica', 300000000000);

        // Proyectos bajo 14-03
        $pr140301 = $this->nodo($sp1403, $eProyecto->id, '14-03-01', 'Construcción y Ampliación Hospitalaria', 600000000000);
        $pr140302 = $this->nodo($sp1403, $eProyecto->id, '14-03-02', 'Equipamiento Biomédico', 241255316165);

        $this->nodo($pr140301, $eActividad->id, '14-03-01-001', 'Infraestructura Hospitalaria — Ampliaciones', 600000000000);
        $this->nodo($pr140302, $eActividad->id, '14-03-02-001', 'Adquisición de Equipos Biomédicos', 241255316165);

        // ══ PROGRAMA 15 — PRESTACIONES ECONÓMICAS ════════════════════════════
        $p15 = $this->nodo(null, $ePrograma->id, '15', 'Prestaciones Económicas y Previsionales', 6385106032434);

        $sp1501 = $this->nodo($p15, $eSubprog->id, '15-01', 'Jubilaciones y Pensiones', 5000000000000);
        $sp1502 = $this->nodo($p15, $eSubprog->id, '15-02', 'Subsidios y Prestaciones a Corto Plazo', 1000000000000);
        $sp1503 = $this->nodo($p15, $eSubprog->id, '15-03', 'Gestión de Inversiones y Reservas', 385106032434);

        $pr150101 = $this->nodo($sp1501, $eProyecto->id, '15-01-01', 'Gestión para Jubilados y Pensionados', 5000000000000);
        $this->nodo($pr150101, $eActividad->id, '15-01-01-001', 'Pago de Haberes Jubilatorios y Pensiones', 4500000000000);
        $this->nodo($pr150101, $eActividad->id, '15-01-01-002', 'Digitalización de Expedientes Previsionales', 500000000000);

        $pr150201 = $this->nodo($sp1502, $eProyecto->id, '15-02-01', 'Subsidios de Maternidad, Enfermedad y Accidentes', 1000000000000);
        $this->nodo($pr150201, $eActividad->id, '15-02-01-001', 'Prestaciones Económicas a Corto Plazo', 1000000000000);

        $pr150301 = $this->nodo($sp1503, $eProyecto->id, '15-03-01', 'Administración de Reservas Técnicas', 385106032434);
        $this->nodo($pr150301, $eActividad->id, '15-03-01-001', 'Inversiones y Gestión de Portafolio', 385106032434);

        // ══ PROGRAMA 16 — GESTIÓN ADMINISTRATIVA ═════════════════════════════
        $p16 = $this->nodo(null, $ePrograma->id, '16', 'Gestión Administrativa Institucional', 467065260973);

        $sp1601 = $this->nodo($p16, $eSubprog->id, '16-01', 'Talento Humano y Desarrollo Institucional', 200000000000);
        $sp1602 = $this->nodo($p16, $eSubprog->id, '16-02', 'Tecnología e Infraestructura Institucional', 150000000000);
        $sp1603 = $this->nodo($p16, $eSubprog->id, '16-03', 'Gestión Financiera y Recaudación', 117065260973);

        $pr160101 = $this->nodo($sp1601, $eProyecto->id, '16-01-01', 'Concursos de Méritos y Carrera Institucional', 100000000000);
        $pr160102 = $this->nodo($sp1601, $eProyecto->id, '16-01-02', 'Gestión Administrativa y Contrataciones', 100000000000);

        $this->nodo($pr160101, $eActividad->id, '16-01-01-001', 'Gestión del Talento Humano — IPS', 100000000000);
        $this->nodo($pr160102, $eActividad->id, '16-01-02-001', 'Programa Anual de Contrataciones (PAC)', 100000000000);

        $pr160201 = $this->nodo($sp1602, $eProyecto->id, '16-02-01', 'Infraestructura Tecnológica y Ciberseguridad', 150000000000);
        $this->nodo($pr160201, $eActividad->id, '16-02-01-001', 'Conectividad y Ciberseguridad Institucional', 150000000000);

        $pr160301 = $this->nodo($sp1603, $eProyecto->id, '16-03-01', 'Recaudación AOP y Control de Evasión', 117065260973);
        $this->nodo($pr160301, $eActividad->id, '16-03-01-001', 'Fiscalización y Recaudación del Aporte Obrero Patronal', 117065260973);

        $this->command->info('✅ PGN IPS ' . self::ANIO . ' sembrado correctamente.');
        $this->command->info('   Programas: 3 | Actividades (nodos hoja): ' .
            PgnNodo::hojas()->where('anio', self::ANIO)->count());
    }

    private function nodo(?int $parentId, int $estructuraId, string $codigo, string $nombre, ?float $monto): int
    {
        $nodo = PgnNodo::firstOrCreate(
            ['anio' => self::ANIO, 'codigo' => $codigo],
            [
                'pgn_estructura_id' => $estructuraId,
                'parent_id'         => $parentId,
                'nombre'            => $nombre,
                'monto_asignado_gs' => $monto,
                'activo'            => true,
            ]
        );
        return $nodo->id;
    }
}
