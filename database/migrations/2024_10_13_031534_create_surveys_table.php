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
        Schema::create('surveys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type');

            $table->unsignedInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('dependency_id')->nullable();
            $table->foreign('dependency_id')->references('id')->on('organigramas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('description');
            $table->timestamps();
            $table->softDeletes(); // Agrega la columna deleted_at para el soft delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surveys');
    }
};
