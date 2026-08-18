<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE plan_acciones ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE plan_acciones DROP COLUMN IF EXISTS deleted_at;");
    }
};
