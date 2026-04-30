<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIeaColumnsToFodaAnalisisTable extends Migration
{
    public function up()
    {
        Schema::table('planificacion.foda_analisis', function (Blueprint $table) {
            $table->decimal('promedio_desempeno_6m', 10, 4)->nullable()->after('impacto');
            $table->decimal('inversion_historica_6m', 15, 4)->nullable()->after('promedio_desempeno_6m');
            $table->decimal('iea_valor', 5, 4)->nullable()->after('inversion_historica_6m');
            $table->enum('iea_clasificacion', ['fortaleza', 'debilidad', 'neutro'])->nullable()->after('iea_valor');
        });
    }

    public function down()
    {
        Schema::table('planificacion.foda_analisis', function (Blueprint $table) {
            $table->dropColumn(['promedio_desempeno_6m', 'inversion_historica_6m', 'iea_valor', 'iea_clasificacion']);
        });
    }
}
