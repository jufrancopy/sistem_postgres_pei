<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('typetask_id');
            $table->foreign('typetask_id')->references('id')->on('planificacion.typetasks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('analyst_id');
            $table->foreign('analyst_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('details')->nullable();
            $table->integer('status')->nullable()->default(0);

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
        Schema::dropIfExists('planificacion.tasks');
    }
}
