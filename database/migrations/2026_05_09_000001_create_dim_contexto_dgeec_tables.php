<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tablas de contexto nacional DGEEC para el SIESS-IPS.
 *
 * dim_indicadores_vivienda_dgeec  — Determinantes ambientales de salud (EPHC REG01)
 * dim_mpi_dgeec                   — Índice de Pobreza Multidimensional 2024
 *
 * Ambas usan SUM(FEX) para proyecciones. Nunca COUNT de registros.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════════════════════════════════
        // 1. VIVIENDA — Determinantes Ambientales de Salud (EPHC REG01)
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.dim_indicadores_vivienda_dgeec', function (Blueprint $table) {
            $table->id();

            // Dimensiones geográficas
            $table->smallInteger('anio');
            $table->tinyInteger('departamento_codigo')
                ->comment('DPTOREP: 0=Total País, 1-17');
            $table->enum('area', ['urbana', 'rural', 'total'])->default('total');

            // ── Hogares totales (SUM FEX) ─────────────────────────────────────
            $table->decimal('hogares_total', 15, 2)->default(0)
                ->comment('SUM(FEX) — total hogares proyectados');

            // ── Determinantes Ambientales (predictores de enfermedad) ─────────
            // V08: Agua para beber (riesgo = códigos 7, 9, 13)
            $table->decimal('hogares_sin_agua_potable', 15, 2)->default(0)
                ->comment('SUM(FEX) donde V08 IN (7,9,13) — pozo sin protección, manantial, agua superficial');
            $table->decimal('pct_sin_agua_potable', 8, 4)->default(0);

            // V14B: Combustible para cocinar (riesgo = 1=Leña, 3=Carbón)
            $table->decimal('hogares_cocina_lena', 15, 2)->default(0)
                ->comment('SUM(FEX) donde V14B=1 — predictor EPOC/asma');
            $table->decimal('pct_cocina_lena', 8, 4)->default(0);

            // V13: Desagüe del baño (riesgo = 3=Pozo ciego sin cámara, 4=Superficie)
            $table->decimal('hogares_sin_desague', 15, 2)->default(0)
                ->comment('SUM(FEX) donde V13 IN (3,4)');
            $table->decimal('pct_sin_desague', 8, 4)->default(0);

            // V10: Sin luz eléctrica
            $table->decimal('hogares_sin_electricidad', 15, 2)->default(0)
                ->comment('SUM(FEX) donde V10=6');
            $table->decimal('pct_sin_electricidad', 8, 4)->default(0);

            // ── Hacinamiento (TOTAL personas / V02B dormitorios > 3) ──────────
            $table->decimal('hogares_hacinados', 15, 2)->default(0)
                ->comment('SUM(FEX) donde (TOTAL/V02B) > 3');
            $table->decimal('pct_hacinados', 8, 4)->default(0);

            // ── Índice de Riqueza / NSE (bienes duraderos V24XX) ─────────────
            // Bajo NSE: sin heladera (V2403=6) + sin lavarropas (V2405=6) + leña
            $table->decimal('hogares_bajo_nse', 15, 2)->default(0)
                ->comment('SUM(FEX) sin heladera + sin lavarropas + cocina leña');
            $table->decimal('pct_bajo_nse', 8, 4)->default(0);

            // Alto NSE: tiene AC (V2408=1) + auto (V2413=1) + internet (V23B=1)
            $table->decimal('hogares_alto_nse', 15, 2)->default(0)
                ->comment('SUM(FEX) con AC + auto + internet');
            $table->decimal('pct_alto_nse', 8, 4)->default(0);

            // ── Pobreza ───────────────────────────────────────────────────────
            $table->decimal('hogares_pobre_extremo', 15, 2)->default(0)
                ->comment('SUM(FEX) donde POBREZAI=1');
            $table->decimal('hogares_pobre', 15, 2)->default(0)
                ->comment('SUM(FEX) donde POBREZAI IN (1,2)');
            $table->decimal('pct_pobreza', 8, 4)->default(0);

            // ── Metadatos ─────────────────────────────────────────────────────
            $table->string('fuente', 50)->default('EPHC-DGEEC REG01');
            $table->string('periodo_referencia')->nullable();
            $table->text('notas')->nullable();
            $table->unsignedInteger('cargado_por')->nullable();
            $table->foreign('cargado_por')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['anio', 'departamento_codigo', 'area'], 'ux_vivienda_dgeec');
            $table->index(['anio', 'departamento_codigo']);
        });

        // ══════════════════════════════════════════════════════════════════════
        // 2. MPI — Índice de Pobreza Multidimensional 2024
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.dim_mpi_dgeec', function (Blueprint $table) {
            $table->id();

            // Dimensiones
            $table->smallInteger('anio');
            $table->tinyInteger('departamento_codigo')
                ->comment('dpto: 0=Total País, 1-17');
            $table->enum('area', ['urbana', 'rural', 'total'])->default('total');

            // ── Indicadores MPI pre-calculados (ya vienen del CSV) ───────────
            // El MPI ya aplica FEX internamente — estos son porcentajes directos

            // Incidencia (H): % de hogares pobres multidimensionales
            $table->decimal('incidencia_h', 8, 4)->nullable()
                ->comment('H_26: % hogares pobres multidimensionales (umbral 26%)');
            $table->decimal('intensidad_a', 8, 4)->nullable()
                ->comment('A_26: Intensidad promedio de privaciones');
            $table->decimal('mpi_m0', 8, 4)->nullable()
                ->comment('M0_26 = H * A — Índice MPI ajustado');

            // ── Privaciones por dimensión (% hogares con privación) ───────────
            // EDUCACIÓN
            $table->decimal('d_ni_noasis', 8, 4)->nullable()
                ->comment('hh_d_ni_noasis: Niños que no asisten a la escuela');
            $table->decimal('d_esc_retardada', 8, 4)->nullable()
                ->comment('hh_d_esc_retardada: Escolaridad retrasada');
            $table->decimal('d_logro_min', 8, 4)->nullable()
                ->comment('hh_d_logro_min: Sin logro educativo mínimo');

            // SALUD — CRÍTICO PARA IPS
            $table->decimal('d_sin_salud', 8, 4)->nullable()
                ->comment('hh_d_sin_salud: Sin acceso a salud — demanda potencial IPS');
            $table->decimal('d_no_afil', 8, 4)->nullable()
                ->comment('hh_d_no_afil: Sin afiliación a seguro — BRECHA IPS directa');
            $table->decimal('d_jubi_pens', 8, 4)->nullable()
                ->comment('hh_d_jubi_pens: Sin jubilación/pensión — cruce con JU1');

            // TRABAJO
            $table->decimal('d_destotalmax', 8, 4)->nullable()
                ->comment('hh_d_destotalmax: Desempleo total máximo en el hogar');
            $table->decimal('d_subocup_max', 8, 4)->nullable()
                ->comment('hh_d_subocup_max: Subocupación máxima');
            $table->decimal('d_10a17_ocup', 8, 4)->nullable()
                ->comment('hh_d_10a17_ocup: Trabajo infantil (10-17 años)');

            // VIVIENDA Y SERVICIOS
            $table->decimal('d_materialidad', 8, 4)->nullable()
                ->comment('hh_d_materialidad: Materialidad deficiente');
            $table->decimal('d_hacinamiento', 8, 4)->nullable()
                ->comment('hh_d_hacinamiento: Hacinamiento');
            $table->decimal('d_sin_basur', 8, 4)->nullable()
                ->comment('hh_d_sin_basur: Sin recolección de basura');
            $table->decimal('d_agua_mejor', 8, 4)->nullable()
                ->comment('hh_d_agua_mejor: Sin agua mejorada');
            $table->decimal('d_san_mejor', 8, 4)->nullable()
                ->comment('hh_d_san_mejor: Sin saneamiento mejorado');
            $table->decimal('d_combus', 8, 4)->nullable()
                ->comment('hh_d_combus: Combustible inadecuado (leña)');

            // ── Conteos absolutos (para cruce con IPS) ────────────────────────
            $table->decimal('hogares_mpi_pobres', 15, 2)->nullable()
                ->comment('SUM(fex_2022) donde multid_poor_26=1');
            $table->decimal('hogares_total', 15, 2)->nullable()
                ->comment('SUM(fex_2022) total');

            // ── Metadatos ─────────────────────────────────────────────────────
            $table->string('fuente', 50)->default('MPI-DGEEC 2024');
            $table->string('periodo_referencia')->nullable();
            $table->text('notas')->nullable();
            $table->unsignedInteger('cargado_por')->nullable();
            $table->foreign('cargado_por')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['anio', 'departamento_codigo', 'area'], 'ux_mpi_dgeec');
            $table->index(['anio', 'departamento_codigo']);
        });

        // ══════════════════════════════════════════════════════════════════════
        // Vista: Mapa de Riesgo Sanitario IPS
        // Cruza MPI + Vivienda para generar score de vulnerabilidad por dpto
        // ══════════════════════════════════════════════════════════════════════
        DB::connection('pgsql')->statement("
            CREATE OR REPLACE VIEW estadistica.vw_mapa_riesgo_sanitario AS
            SELECT
                COALESCE(m.anio, v.anio)                    AS anio,
                COALESCE(m.departamento_codigo, v.departamento_codigo) AS departamento_codigo,
                COALESCE(m.area, v.area)                    AS area,

                -- MPI
                m.incidencia_h                              AS mpi_incidencia,
                m.mpi_m0                                    AS mpi_m0,
                m.d_no_afil                                 AS pct_sin_afiliacion,
                m.d_sin_salud                               AS pct_sin_acceso_salud,
                m.d_jubi_pens                               AS pct_sin_jubilacion,
                m.hogares_mpi_pobres,

                -- Vivienda
                v.pct_sin_agua_potable,
                v.pct_cocina_lena,
                v.pct_sin_desague,
                v.pct_hacinados,
                v.pct_pobreza,

                -- Score de riesgo compuesto (0-100)
                -- Pondera: sin afiliación (30%) + sin agua (20%) + leña (20%) + MPI (30%)
                ROUND((
                    COALESCE(m.d_no_afil, 0) * 30 +
                    COALESCE(v.pct_sin_agua_potable, 0) * 20 +
                    COALESCE(v.pct_cocina_lena, 0) * 20 +
                    COALESCE(m.mpi_m0, 0) * 30
                )::numeric, 2) AS score_riesgo_sanitario

            FROM estadistica.dim_mpi_dgeec m
            FULL OUTER JOIN estadistica.dim_indicadores_vivienda_dgeec v
                ON m.anio = v.anio
                AND m.departamento_codigo = v.departamento_codigo
                AND m.area = v.area
            ORDER BY score_riesgo_sanitario DESC NULLS LAST
        ");
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP VIEW IF EXISTS estadistica.vw_mapa_riesgo_sanitario');
        Schema::connection('pgsql')->dropIfExists('estadistica.dim_mpi_dgeec');
        Schema::connection('pgsql')->dropIfExists('estadistica.dim_indicadores_vivienda_dgeec');
    }
};
