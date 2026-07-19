<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion.pei_actores', function (Blueprint $table) {
            $table->unsignedBigInteger('institucion_id')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('planificacion.pei_actores', function (Blueprint $table) {
            $table->dropColumn('institucion_id');
        });
    }
};
