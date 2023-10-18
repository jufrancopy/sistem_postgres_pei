<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksHasTypeTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.tasks_has_type_tasks', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('task_id');
            $table->foreign('task_id')->references('id')->on('planificacion.tasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('type_task_id');
            $table->foreign('type_task_id')->references('id')->on('planificacion.typetasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('status')->default(0);


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
        Schema::dropIfExists('planificacion.tasks_has_type_tasks');
    }
}
