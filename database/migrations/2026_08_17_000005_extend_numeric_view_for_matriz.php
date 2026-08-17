<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
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
                row_value.key::varchar AS metric_code,
                NULL::bigint AS catalog_item_id,
                COALESCE(
                    CASE
                        WHEN jsonb_typeof(row_value.value->'total') = 'number' THEN (row_value.value->>'total')::numeric
                        WHEN jsonb_typeof(row_value.value->'total') = 'string'
                             AND (row_value.value->>'total') ~ '^-?[0-9]+([.][0-9]+)?$'
                            THEN (row_value.value->>'total')::numeric
                        ELSE NULL
                    END,
                    0
                ) AS valor
            FROM bioestadistica.record_values rv
            JOIN bioestadistica.records r ON r.id = rv.record_id AND r.deleted_at IS NULL
            JOIN bioestadistica.formularios fo ON fo.id = r.formulario_id AND fo.deleted_at IS NULL
            JOIN bioestadistica.fields f ON f.id = rv.field_id AND f.deleted_at IS NULL
            CROSS JOIN LATERAL jsonb_each(COALESCE(rv.value_json->'rows', '{}'::jsonb)) row_value
            WHERE f.type = 'matriz'
              AND jsonb_typeof(row_value.value) = 'object'
            SQL);
    }

    public function down(): void
    {
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
};
