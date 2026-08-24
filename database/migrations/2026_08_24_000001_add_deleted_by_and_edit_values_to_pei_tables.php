<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE planificacion.pei_profiles ADD COLUMN IF NOT EXISTS deleted_by BIGINT NULL");
        DB::statement("ALTER TABLE plan_acciones ADD COLUMN IF NOT EXISTS deleted_by BIGINT NULL");
        DB::statement("ALTER TABLE pei_profile_edits ADD COLUMN IF NOT EXISTS old_values JSONB NULL");
        DB::statement("ALTER TABLE pei_profile_edits ADD COLUMN IF NOT EXISTS new_values JSONB NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE planificacion.pei_profiles DROP COLUMN IF EXISTS deleted_by");
        DB::statement("ALTER TABLE plan_acciones DROP COLUMN IF EXISTS deleted_by");
        DB::statement("ALTER TABLE pei_profile_edits DROP COLUMN IF EXISTS old_values");
        DB::statement("ALTER TABLE pei_profile_edits DROP COLUMN IF EXISTS new_values");
    }
};
