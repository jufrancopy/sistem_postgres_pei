<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.pei_profiles_has_strategies', function (Blueprint $table) {
            $table->id();
            $table->uuid('profile_id');
            $table->foreign('profile_id')->references('id')->on('planificacion.pei_profiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('strategy_id');
            $table->foreign('strategy_id')->references('id')->on('planificacion.foda_cruce_ambientes')
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
        Schema::dropIfExists('planificacion_pei_profiles_has_strategies');
    }
};
