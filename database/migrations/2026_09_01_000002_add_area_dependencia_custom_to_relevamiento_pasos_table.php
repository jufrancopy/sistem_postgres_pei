<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion.relevamiento_pasos', function (Blueprint $table) {
            if (!Schema::hasColumn('planificacion.relevamiento_pasos', 'area_dependencia_custom')) {
                $table->string('area_dependencia_custom')->nullable()->after('organigrama_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('planificacion.relevamiento_pasos', function (Blueprint $table) {
            if (Schema::hasColumn('planificacion.relevamiento_pasos', 'area_dependencia_custom')) {
                $table->dropColumn('area_dependencia_custom');
            }
        });
    }
};
