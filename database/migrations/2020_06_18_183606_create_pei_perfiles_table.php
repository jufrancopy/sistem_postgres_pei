<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeiPerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        Schema::table('planificacion.pei_perfiles', function (Blueprint $table) {
            //
        });


        Schema::create('planificacion.pei_perfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->text('mision');
            $table->text('tevision');
            $table->text('valores');
            $table->integer('vigencia');


            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('planificacion.pei_perfiles', function (Blueprint $table) {
            //
        });
    }
}
