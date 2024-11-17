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
        Schema::create('planificacion.tasks_has_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('task_id');
            $table->foreign('task_id')->references('id')->on('planificacion.tasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('participant_id');
            $table->foreign('participant_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planificacion.tasks_has_participants');
    }
};
