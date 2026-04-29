<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            if (!Schema::connection('pgsql')->hasColumn('planificacion.pei_profiles', 'report_type')) {
                $table->string('report_type')->nullable();
            }
            if (!Schema::connection('pgsql')->hasColumn('planificacion.pei_profiles', 'parameters')) {
                $table->text('parameters')->nullable();
            }
            if (!Schema::connection('pgsql')->hasColumn('planificacion.pei_profiles', 'number_target')) {
                $table->string('number_target')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->dropColumn(['report_type', 'parameters', 'number_target']);
        });
    }
};
