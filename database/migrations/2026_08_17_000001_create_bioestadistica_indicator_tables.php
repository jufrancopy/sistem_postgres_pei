<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.indicadores', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 80)->unique();
            $table->string('nombre', 250);
            $table->text('descripcion')->nullable();
            $table->string('unidad', 50)->nullable();
            $table->string('ambito', 50)->default('establecimiento');
            $table->smallInteger('decimales')->default(2);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        DB::connection('pgsql')->statement(
            "ALTER TABLE bioestadistica.indicadores
             ADD CONSTRAINT bio_indicadores_ambito_check
             CHECK (ambito IN ('establecimiento','distrito','departamento','microred','pais'))"
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.indicadores
             ADD CONSTRAINT bio_indicadores_decimales_check CHECK (decimales BETWEEN 0 AND 4)'
        );

        Schema::connection('pgsql')->create('bioestadistica.indicador_formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicador_id')
                ->constrained('bioestadistica.indicadores')
                ->cascadeOnDelete();
            $table->jsonb('expresion');
            $table->date('vigente_desde')->nullable();
            $table->date('vigente_hasta')->nullable();
            $table->timestamps();
            $table->index(['indicador_id', 'vigente_desde']);
        });
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.indicador_formulas
             ADD CONSTRAINT bio_indicador_formula_vigencia_check
             CHECK (vigente_hasta IS NULL OR vigente_desde IS NULL OR vigente_hasta >= vigente_desde)'
        );
        DB::connection('pgsql')->statement(
            'CREATE INDEX bio_indicador_formulas_expr_gin
             ON bioestadistica.indicador_formulas USING gin (expresion)'
        );

        Schema::connection('pgsql')->create('bioestadistica.indicador_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicador_id')
                ->constrained('bioestadistica.indicadores')
                ->cascadeOnDelete();
            $table->foreignId('formula_id')
                ->constrained('bioestadistica.indicador_formulas')
                ->cascadeOnDelete();
            $table->smallInteger('periodo_anio');
            $table->smallInteger('periodo_mes');
            $table->foreignId('establecimiento_id')
                ->constrained('bioestadistica.establecimientos')
                ->cascadeOnDelete();
            $table->decimal('valor', 18, 4)->nullable();
            $table->timestamp('calculado_at');
            $table->timestamps();
            $table->unique(
                ['indicador_id', 'formula_id', 'periodo_anio', 'periodo_mes', 'establecimiento_id'],
                'bio_indicador_cache_unique'
            );
            $table->index(['establecimiento_id', 'periodo_anio', 'periodo_mes'], 'bio_indicador_cache_periodo_idx');
        });
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.indicador_cache
             ADD CONSTRAINT bio_indicador_cache_mes_check CHECK (periodo_mes BETWEEN 1 AND 12)'
        );

        $this->createViews();
    }

    private function createViews(): void
    {
        DB::connection('pgsql')->statement(<<<'SQL'
            CREATE OR REPLACE VIEW bioestadistica.v_establecimientos_geo AS
            SELECT
                e.id AS establecimiento_id,
                e.codigo AS establecimiento_codigo,
                e.nombre AS establecimiento_nombre,
                e.distrito_id,
                d.nombre AS distrito_nombre,
                d.departamento_id,
                dep.nombre AS departamento_nombre,
                e.microred_id,
                mr.nombre AS microred_nombre,
                e.tipo_establecimiento_id,
                te.nombre AS tipo_establecimiento_nombre,
                e.grado_complejidad_id,
                gc.codigo AS grado_complejidad_codigo,
                gc.descripcion AS grado_complejidad_descripcion,
                e.area_gestion_id,
                ag.nombre AS area_gestion_nombre,
                e.nivel_atencion,
                e.prestador
            FROM bioestadistica.establecimientos e
            LEFT JOIN bioestadistica.distritos d ON d.id = e.distrito_id AND d.deleted_at IS NULL
            LEFT JOIN bioestadistica.departamentos dep ON dep.id = d.departamento_id AND dep.deleted_at IS NULL
            LEFT JOIN bioestadistica.microredes mr ON mr.id = e.microred_id AND mr.deleted_at IS NULL
            LEFT JOIN bioestadistica.tipos_establecimiento te ON te.id = e.tipo_establecimiento_id AND te.deleted_at IS NULL
            LEFT JOIN bioestadistica.grados_complejidad gc ON gc.id = e.grado_complejidad_id AND gc.deleted_at IS NULL
            LEFT JOIN bioestadistica.areas_gestion ag ON ag.id = e.area_gestion_id AND ag.deleted_at IS NULL
            WHERE e.deleted_at IS NULL
            SQL);

        DB::connection('pgsql')->statement(<<<'SQL'
            CREATE OR REPLACE VIEW bioestadistica.v_valores_numericos AS
            SELECT
                r.id AS record_id,
                r.formulario_id,
                fo.codigo AS formulario_codigo,
                r.establecimiento_id,
                r.periodo_anio,
                r.periodo_mes,
                r.estado,
                rv.field_id,
                f.code AS field_code,
                NULL::varchar AS metric_code,
                NULL::bigint AS catalog_item_id,
                rv.value_num::numeric AS valor
            FROM bioestadistica.record_values rv
            JOIN bioestadistica.records r ON r.id = rv.record_id AND r.deleted_at IS NULL
            JOIN bioestadistica.formularios fo ON fo.id = r.formulario_id AND fo.deleted_at IS NULL
            JOIN bioestadistica.fields f ON f.id = rv.field_id AND f.deleted_at IS NULL
            WHERE rv.value_num IS NOT NULL

            UNION ALL

            SELECT
                r.id AS record_id,
                r.formulario_id,
                fo.codigo AS formulario_codigo,
                r.establecimiento_id,
                r.periodo_anio,
                r.periodo_mes,
                r.estado,
                rv.field_id,
                f.code AS field_code,
                metric.key::varchar AS metric_code,
                CASE WHEN row_value.key ~ '^[0-9]+$' THEN row_value.key::bigint ELSE NULL END AS catalog_item_id,
                (metric.value #>> '{}')::numeric AS valor
            FROM bioestadistica.record_values rv
            JOIN bioestadistica.records r ON r.id = rv.record_id AND r.deleted_at IS NULL
            JOIN bioestadistica.formularios fo ON fo.id = r.formulario_id AND fo.deleted_at IS NULL
            JOIN bioestadistica.fields f ON f.id = rv.field_id AND f.deleted_at IS NULL
            CROSS JOIN LATERAL jsonb_each(COALESCE(rv.value_json->'rows', '{}'::jsonb)) row_value
            CROSS JOIN LATERAL jsonb_each(row_value.value) metric
            WHERE f.type = 'tabla'
              AND (
                jsonb_typeof(metric.value) = 'number'
                OR (
                    jsonb_typeof(metric.value) = 'string'
                    AND (metric.value #>> '{}') ~ '^-?[0-9]+([.][0-9]+)?$'
                )
              )
            SQL);
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP VIEW IF EXISTS bioestadistica.v_valores_numericos');
        DB::connection('pgsql')->statement('DROP VIEW IF EXISTS bioestadistica.v_establecimientos_geo');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.indicador_cache');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.indicador_formulas');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.indicadores');
    }
};
