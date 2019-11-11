<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormularioItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('globales.formulario_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item');

            $table->unsignedInteger('variable_id')->nullable();
            $table->foreign('variable_id')->references('id')->on('globales.formulario_variables')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('globales.formulario_items');
    }
}
