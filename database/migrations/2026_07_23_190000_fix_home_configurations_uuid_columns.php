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
        // Cambiar de bigInteger a uuid para compatibilidad con FodaPerfil y PeiProfile
        DB::statement('ALTER TABLE home_configurations ALTER COLUMN foda_profile_id TYPE UUID USING NULL');
        DB::statement('ALTER TABLE home_configurations ALTER COLUMN foda_analisis_id TYPE UUID USING NULL');
        DB::statement('ALTER TABLE home_configurations ALTER COLUMN pei_profile_id TYPE UUID USING NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE home_configurations ALTER COLUMN foda_profile_id TYPE BIGINT USING NULL');
        DB::statement('ALTER TABLE home_configurations ALTER COLUMN foda_analisis_id TYPE BIGINT USING NULL');
        DB::statement('ALTER TABLE home_configurations ALTER COLUMN pei_profile_id TYPE BIGINT USING NULL');
    }
};
