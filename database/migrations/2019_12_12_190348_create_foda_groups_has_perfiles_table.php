<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFodaGroupsHasPerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.foda_groups_has_perfiles', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('perfil_id');
            $table->foreign('perfil_id')->references('id')->on('planificacion.foda_perfiles')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')
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
        Schema::dropIfExists('planificacion.foda_groups_has_perfiles');
    }
}
