<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganigramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organigramas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dependency');

            $table->unsignedInteger('dependency_id')->nullable();
            $table->foreign('dependency_id')->references('id')->on('organigramas')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            
            $table->string('responsable');
            $table->integer('telefono');
            $table->string('email')->unique();        

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
        Schema::dropIfExists('organigramas');
    }
}
