<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plan_acciones', function (Blueprint $table) {
            if (!Schema::hasColumn('plan_acciones', 'indicador_id')) {
                $table->bigInteger('indicador_id')->nullable()->after('pei_profile_id');
                $table->foreign('indicador_id')
                      ->references('id')
                      ->on('planificacion.indicadores')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plan_acciones', function (Blueprint $table) {
            if (Schema::hasColumn('plan_acciones', 'indicador_id')) {
                $table->dropForeign(['indicador_id']);
                $table->dropColumn('indicador_id');
            }
        });
    }
};
