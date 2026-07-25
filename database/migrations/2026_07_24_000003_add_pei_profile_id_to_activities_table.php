<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('activities', 'pei_profile_id')) {
            DB::statement('ALTER TABLE activities ADD COLUMN pei_profile_id UUID REFERENCES planificacion.pei_profiles(id) ON DELETE SET NULL;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('activities', 'pei_profile_id')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->dropColumn('pei_profile_id');
            });
        }
    }
};
