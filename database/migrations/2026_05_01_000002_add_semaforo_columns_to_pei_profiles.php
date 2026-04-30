<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->enum('tipo_indicador', ['lead', 'lag'])->nullable()->after('indicator');
            $table->enum('semaforo', ['verde', 'amarillo', 'rojo'])->nullable()->after('tipo_indicador');
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->dropColumn(['tipo_indicador', 'semaforo']);
        });
    }
};
