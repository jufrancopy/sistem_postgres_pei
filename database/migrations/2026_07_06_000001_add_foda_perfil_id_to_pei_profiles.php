<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFodaPerfilIdToPeiProfiles extends Migration
{
    public function up()
    {
        Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->uuid('foda_perfil_id')->nullable()->after('group_id');
        });
    }

    public function down()
    {
        Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
            $table->dropColumn('foda_perfil_id');
        });
    }
}
