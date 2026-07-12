<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Niveles del PGN por año (estructura dinámica) ──────────────────
        DB::statement("
            CREATE TABLE planificacion.pgn_estructura (
                id          BIGSERIAL PRIMARY KEY,
                anio        SMALLINT        NOT NULL,
                orden       SMALLINT        NOT NULL,
                nombre      VARCHAR(100)    NOT NULL,
                descripcion TEXT,
                activo      BOOLEAN         NOT NULL DEFAULT TRUE,
                created_at  TIMESTAMP,
                updated_at  TIMESTAMP,
                UNIQUE (anio, orden),
                UNIQUE (anio, nombre)
            )
        ");

        // ── 2. Nodos del árbol PGN (árbol con parent_id simple) ───────────────
        DB::statement("
            CREATE TABLE planificacion.pgn_nodos (
                id                  BIGSERIAL PRIMARY KEY,
                anio                SMALLINT        NOT NULL,
                pgn_estructura_id   BIGINT          NOT NULL REFERENCES planificacion.pgn_estructura(id) ON DELETE RESTRICT,
                parent_id           BIGINT          REFERENCES planificacion.pgn_nodos(id) ON DELETE CASCADE,
                codigo              VARCHAR(50),
                nombre              VARCHAR(500)    NOT NULL,
                monto_asignado_gs   NUMERIC(18,2),
                activo              BOOLEAN         NOT NULL DEFAULT TRUE,
                created_at          TIMESTAMP,
                updated_at          TIMESTAMP
            )
        ");

        DB::statement("CREATE INDEX pgn_nodos_anio_idx ON planificacion.pgn_nodos(anio)");
        DB::statement("CREATE INDEX pgn_nodos_parent_idx ON planificacion.pgn_nodos(parent_id)");

        // ── 3. Pivote: vinculación acción PEI ↔ nodo PGN ─────────────────────
        DB::statement("
            CREATE TABLE planificacion.pei_accion_pgn (
                id                  BIGSERIAL PRIMARY KEY,
                pei_profile_id      UUID            NOT NULL REFERENCES planificacion.pei_profiles(id) ON DELETE CASCADE,
                pgn_nodo_id         BIGINT          NOT NULL REFERENCES planificacion.pgn_nodos(id) ON DELETE RESTRICT,
                resultado           TEXT,
                monto_vinculado_gs  NUMERIC(18,2),
                monto_ejecutado_gs  NUMERIC(18,2),
                created_at          TIMESTAMP,
                updated_at          TIMESTAMP,
                UNIQUE (pei_profile_id, pgn_nodo_id)
            )
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS planificacion.pei_accion_pgn CASCADE");
        DB::statement("DROP TABLE IF EXISTS planificacion.pgn_nodos CASCADE");
        DB::statement("DROP TABLE IF EXISTS planificacion.pgn_estructura CASCADE");
    }
};
