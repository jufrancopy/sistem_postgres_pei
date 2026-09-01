<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('planificacion.relevamiento_procesos', function (Blueprint $table) {
            $table->jsonb('participantes_externos')->nullable()->after('analisis_ia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planificacion.relevamiento_procesos', function (Blueprint $table) {
            $table->dropColumn('participantes_externos');
        });
    }
};
