<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->string('public_token', 64)->nullable()->unique()->after('foda_perfil_id')
                ->comment('Token público para vista compartida sin login');
        });
    }

    public function down(): void
    {
        Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->dropColumn('public_token');
        });
    }
};
