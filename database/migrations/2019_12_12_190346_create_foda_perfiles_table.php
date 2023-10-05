<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFodaPerfilesTable extends Migration
{
    protected $fillable = ['nombre', 'contexto', 'user_id', 'modelo_id'];
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_perfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('context');

            $table->unsignedInteger('model_id');
            $table->foreign('model_id')->references('id')->on('planificacion.foda_modelos')
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
        Schema::dropIfExists('planificacion.foda_perfiles', function (Blueprint $table) {
            //
        });
    }
}
