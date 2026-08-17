<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE planificacion.pei_profiles ADD COLUMN IF NOT EXISTS comentario_asesor TEXT NULL;");
        DB::statement("ALTER TABLE planificacion.pei_profiles ADD COLUMN IF NOT EXISTS asesor_token VARCHAR(64) NULL UNIQUE;");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE planificacion.pei_profiles DROP COLUMN IF EXISTS comentario_asesor;");
        DB::statement("ALTER TABLE planificacion.pei_profiles DROP COLUMN IF EXISTS asesor_token;");
    }
};
