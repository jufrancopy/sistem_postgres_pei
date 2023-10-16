<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeisProfilesHasAnalystsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.peis_profiles_has_analysts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pei_profile_id');
            $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('analyst_id');
            $table->foreign('analyst_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planificacion.peis_profiles_has_analysts');
    }
}
