<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;


class CreateFodaModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('owner');
            $table->string('environment')->nullable();
            $table->text('description')->nullable();
            
            $table->nestedSet();

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
        Schema::dropIfExists('planificacion.foda_models', function (Blueprint $table) {
            //
        });
    }

    
}
