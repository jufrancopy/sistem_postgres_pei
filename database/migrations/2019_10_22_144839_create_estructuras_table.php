<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstructurasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globales.estructuras', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('dependencia');
            $table->foreign('dependencia')->references('id')->on('globales.organigramas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
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
        Schema::dropIfExists('globales.estructuras');
    }
}
