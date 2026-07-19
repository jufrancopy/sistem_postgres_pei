<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE planificacion.pei_profiles ADD COLUMN IF NOT EXISTS public_tabs JSONB NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE planificacion.pei_profiles DROP COLUMN IF EXISTS public_tabs");
    }
};
