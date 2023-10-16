<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeisProfilesHasDependenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.pei_profiles_has_dependencies', function (Blueprint $table) {
            $table->id();
            $table->uuid('pei_profile_id');
            $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('dependency_id');
            $table->foreign('dependency_id')->references('id')->on('organigramas')
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
        Schema::dropIfExists('planificacion.pei_profiles_has_dependencies');
    }
}
