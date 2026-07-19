<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('activity_id')->nullable()->after('indicador_id');
            $table->foreign('activity_id')
                  ->references('id')->on('activities')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->dropForeign(['activity_id']);
            $table->dropColumn('activity_id');
        });
    }
};
