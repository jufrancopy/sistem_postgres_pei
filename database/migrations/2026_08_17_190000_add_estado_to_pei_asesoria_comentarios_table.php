<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE planificacion.pei_asesoria_comentarios
            ADD COLUMN IF NOT EXISTS estado VARCHAR(32) NULL DEFAULT 'PENDIENTE',
            ADD COLUMN IF NOT EXISTS integrated_at TIMESTAMP NULL;
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE planificacion.pei_asesoria_comentarios
            DROP COLUMN IF EXISTS estado,
            DROP COLUMN IF EXISTS integrated_at;
        ");
    }
};
