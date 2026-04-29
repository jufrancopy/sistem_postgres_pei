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
        Schema::create('patrimony_profiles', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('dependency_id')->nullable();
            $table->foreign('dependency_id')->references('id')->on('organigramas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('description');
            $table->softDeletes();

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
        Schema::dropIfExists('patrimony_profiles');
    }
};
