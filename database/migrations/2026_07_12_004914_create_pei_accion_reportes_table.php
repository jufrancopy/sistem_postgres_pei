<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE planificacion.pei_accion_reportes (
                id                  BIGSERIAL PRIMARY KEY,
                pei_profile_id      UUID        NOT NULL
                    REFERENCES planificacion.pei_profiles(id) ON DELETE CASCADE,
                user_id             INTEGER     NOT NULL
                    REFERENCES users(id) ON DELETE CASCADE,

                -- Período del reporte
                fecha_reporte       DATE        NOT NULL DEFAULT CURRENT_DATE,
                periodo_label       VARCHAR(50) NULL,  -- ej: 'Ene-Jun 2025', '1er Trim 2025'

                -- Valor cuantitativo del indicador (numerador)
                valor_numerador     NUMERIC(18,4) NULL,

                -- Descripción narrativa del avance
                descripcion_avance  TEXT        NULL,

                -- Evidencia opcional
                evidencia_url       TEXT        NULL,
                evidencia_label     VARCHAR(200) NULL,

                -- Semáforo calculado al momento del reporte
                semaforo            VARCHAR(15) NULL
                    CHECK (semaforo IN ('verde','amarillo','rojo','sin-datos')),

                -- % de avance calculado al momento del reporte
                pct_avance          NUMERIC(6,2) NULL,

                created_at          TIMESTAMPTZ,
                updated_at          TIMESTAMPTZ
            )
        ");

        DB::statement("CREATE INDEX idx_par_accion ON planificacion.pei_accion_reportes(pei_profile_id)");
        DB::statement("CREATE INDEX idx_par_user   ON planificacion.pei_accion_reportes(user_id)");
        DB::statement("CREATE INDEX idx_par_fecha  ON planificacion.pei_accion_reportes(fecha_reporte DESC)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS planificacion.pei_accion_reportes CASCADE");
    }
};
