<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEPCTalentoHumanosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto.e_p_c_talento_humanos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item');

            $table->unsignedInteger('specialty_id');
            $table->foreign('specialty_id')->references('id')->on('proyecto.e_p_c_especialidads')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('hours');
            $table->string('type');
            $table->float('cost');

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
        Schema::dropIfExists('proyecto.e_p_c_talento_humanos');
    }
}
