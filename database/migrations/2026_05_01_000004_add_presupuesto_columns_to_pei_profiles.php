<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->decimal('presupuesto_asignado', 18, 2)->nullable()->after('semaforo');
            $table->decimal('presupuesto_ejecutado', 18, 2)->nullable()->after('presupuesto_asignado');
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->dropColumn(['presupuesto_asignado', 'presupuesto_ejecutado']);
        });
    }
};
