<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE IF NOT EXISTS planificacion.pei_asesorias (
                id BIGSERIAL PRIMARY KEY,
                pei_profile_id VARCHAR(64) NOT NULL,
                nombre VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                institucion VARCHAR(255) NULL,
                codigo_acceso VARCHAR(32) NOT NULL UNIQUE,
                dictamen_general TEXT NULL,
                estado VARCHAR(32) NOT NULL DEFAULT 'PENDIENTE',
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            );
        ");

        DB::statement("
            CREATE TABLE IF NOT EXISTS planificacion.pei_asesoria_comentarios (
                id BIGSERIAL PRIMARY KEY,
                pei_asesoria_id BIGINT NOT NULL,
                node_id VARCHAR(64) NOT NULL,
                node_type VARCHAR(32) NOT NULL DEFAULT 'node',
                comentario TEXT NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                CONSTRAINT fk_pei_asesoria FOREIGN KEY (pei_asesoria_id) REFERENCES planificacion.pei_asesorias(id) ON DELETE CASCADE
            );
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS planificacion.pei_asesoria_comentarios;");
        DB::statement("DROP TABLE IF EXISTS planificacion.pei_asesorias;");
    }
};
