<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFodaAnalisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_analisis', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('perfil_id');
            $table->foreign('perfil_id')->references('id')->on('planificacion.foda_perfiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('aspecto_id')->nullable();
            $table->foreign('aspecto_id')->references('id')->on('planificacion.foda_aspectos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->enum('tipo', ['Pendiente', 'Fortaleza', 'Oportunidad', 'Debilidad', 'Amenaza']);
            $table->decimal('ocurrencia')->nullable();
            $table->decimal('impacto')->nullable();


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
        Schema::dropIfExists('planificacion.foda_analisis');
    }
}
