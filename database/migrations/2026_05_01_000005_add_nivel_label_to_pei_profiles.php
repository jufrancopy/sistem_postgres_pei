<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->string('nivel_label')->nullable()->after('level');
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->dropColumn('nivel_label');
        });
    }
};
