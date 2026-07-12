<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE planificacion.pei_profiles
            ADD COLUMN IF NOT EXISTS ri_presupuestario   TEXT    NULL,
            ADD COLUMN IF NOT EXISTS ri_programa         TEXT    NULL,
            ADD COLUMN IF NOT EXISTS ri_recursos_gs      NUMERIC(20,2) NULL,
            ADD COLUMN IF NOT EXISTS ri_metas            JSONB   NOT NULL DEFAULT '[]'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE planificacion.pei_profiles
            DROP COLUMN IF EXISTS ri_presupuestario,
            DROP COLUMN IF EXISTS ri_programa,
            DROP COLUMN IF EXISTS ri_recursos_gs,
            DROP COLUMN IF EXISTS ri_metas
        ");
    }
};
