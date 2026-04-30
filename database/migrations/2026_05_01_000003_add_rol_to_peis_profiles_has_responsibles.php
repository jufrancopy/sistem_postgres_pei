<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('pgsql')->table('planificacion.peis_profiles_has_responsibles', function (Blueprint $table) {
            $table->enum('rol', ['R', 'A', 'C', 'I'])->default('R')->after('responsible_id');
        });
    }

    public function down()
    {
        Schema::connection('pgsql')->table('planificacion.peis_profiles_has_responsibles', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};
