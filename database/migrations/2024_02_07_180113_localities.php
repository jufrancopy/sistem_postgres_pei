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
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_dpto');
            $table->string('desc_dpto');
            $table->integer('cod_dist');
            $table->string('desc_dist');
            $table->integer('area');
            $table->integer('cod_barrio_loc');
            $table->string('desc_barrio_loc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localities');
    }
};
