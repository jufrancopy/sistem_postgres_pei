<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE planificacion.indicadores (
                id              BIGSERIAL PRIMARY KEY,
                pei_profile_id  UUID            NOT NULL
                                REFERENCES planificacion.pei_profiles(id) ON DELETE CASCADE,

                -- 1. Identificación
                nombre          TEXT            NOT NULL,
                codigo_letras   VARCHAR(10)     NOT NULL DEFAULT '',
                codigo_numeros  VARCHAR(10)     NOT NULL DEFAULT '',

                -- 2. Clasificación
                dimension       VARCHAR(20)     NOT NULL
                                CHECK (dimension IN ('eficiencia','eficacia','calidad','economia')),
                ambito          VARCHAR(30)     NOT NULL
                                CHECK (ambito IN ('objetivo_estrategico','objetivo_especifico','accion_estrategica','accion_operativa')),

                -- 3. Descripción técnica
                descripcion     TEXT,
                variables       TEXT,
                formula         TEXT,
                unidad_medida   VARCHAR(100),

                -- 4. Parámetros de medición
                frecuencia      VARCHAR(20)     NOT NULL DEFAULT 'anual'
                                CHECK (frecuencia IN ('mensual','trimestral','semestral','anual','otro')),
                frecuencia_otro VARCHAR(100),
                cobertura       VARCHAR(20)     NOT NULL DEFAULT 'nacional'
                                CHECK (cobertura IN ('nacional','regional','departamental','municipal')),
                sentido         VARCHAR(15)     NOT NULL DEFAULT 'ascendente'
                                CHECK (sentido IN ('ascendente','descendente')),

                -- 5. Línea de base
                linea_base_anio SMALLINT,
                linea_base_valor TEXT,

                -- Metas periódicas en JSON
                metas           JSONB           NOT NULL DEFAULT '[]',

                -- 7. Fuente y responsable
                fuente          TEXT,
                dependencia_responsable TEXT,

                -- 8. Comentarios
                comentarios     TEXT,

                created_at      TIMESTAMPTZ,
                updated_at      TIMESTAMPTZ
            )
        ");

        DB::statement("CREATE INDEX idx_indicadores_pei ON planificacion.indicadores(pei_profile_id)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS planificacion.indicadores CASCADE");
    }
};
