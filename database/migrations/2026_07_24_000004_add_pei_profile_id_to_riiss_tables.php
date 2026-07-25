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
        if (!Schema::hasColumn('evaluaciones', 'pei_profile_id')) {
            DB::statement('ALTER TABLE evaluaciones ADD COLUMN pei_profile_id UUID REFERENCES planificacion.pei_profiles(id) ON DELETE SET NULL;');
        }
        if (!Schema::hasColumn('riiss_asignaciones', 'pei_profile_id')) {
            DB::statement('ALTER TABLE riiss_asignaciones ADD COLUMN pei_profile_id UUID REFERENCES planificacion.pei_profiles(id) ON DELETE SET NULL;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('evaluaciones', 'pei_profile_id')) {
            Schema::table('evaluaciones', function (Blueprint $table) {
                $table->dropColumn('pei_profile_id');
            });
        }
        if (Schema::hasColumn('riiss_asignaciones', 'pei_profile_id')) {
            Schema::table('riiss_asignaciones', function (Blueprint $table) {
                $table->dropColumn('pei_profile_id');
            });
        }
    }
};
