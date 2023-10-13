<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksHasAnalystsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.tasks_has_analysts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('task_id');
            $table->foreign('task_id')->references('id')->on('planificacion.tasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('analyst_id');
            $table->foreign('analyst_id')->references('id')->on('users')
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
        Schema::dropIfExists('planificacion.tasks_has_analysts');
    }
}
