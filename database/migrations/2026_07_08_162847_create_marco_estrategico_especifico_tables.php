<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Sección A: Marco Legal ────────────────────────────────────────────
        DB::statement("
            CREATE TABLE planificacion.mee_marco_legal (
                id              BIGSERIAL PRIMARY KEY,
                pei_profile_id  UUID NOT NULL
                    REFERENCES planificacion.pei_profiles(id) ON DELETE CASCADE,
                marco_legal     TEXT NOT NULL,
                competencias    TEXT,
                orden           SMALLINT NOT NULL DEFAULT 0,
                created_at      TIMESTAMPTZ,
                updated_at      TIMESTAMPTZ
            )
        ");

        // Tabla pivote: marco legal ↔ responsables (organigramas)
        DB::statement("
            CREATE TABLE planificacion.mee_marco_legal_responsables (
                marco_legal_id  BIGINT NOT NULL
                    REFERENCES planificacion.mee_marco_legal(id) ON DELETE CASCADE,
                organigrama_id  BIGINT NOT NULL
                    REFERENCES organigramas(id) ON DELETE CASCADE,
                PRIMARY KEY (marco_legal_id, organigrama_id)
            )
        ");

        // ── Sección B: Oferta de Servicios ────────────────────────────────────
        DB::statement("
            CREATE TABLE planificacion.mee_oferta_servicios (
                id              BIGSERIAL PRIMARY KEY,
                pei_profile_id  UUID NOT NULL
                    REFERENCES planificacion.pei_profiles(id) ON DELETE CASCADE,
                accion          TEXT NOT NULL,
                descripcion     TEXT,
                beneficiarios   TEXT,
                orden           SMALLINT NOT NULL DEFAULT 0,
                created_at      TIMESTAMPTZ,
                updated_at      TIMESTAMPTZ
            )
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS planificacion.mee_marco_legal_responsables CASCADE");
        DB::statement("DROP TABLE IF EXISTS planificacion.mee_marco_legal CASCADE");
        DB::statement("DROP TABLE IF EXISTS planificacion.mee_oferta_servicios CASCADE");
    }
};
