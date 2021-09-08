<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEPCEspecialidadsTable extends Migration
{
    public function up()
    {
        Schema::create('proyecto.e_p_c_especialidads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item');
            $table->string('type');
            $table->text('detail');
            $table->float('cost');
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
        Schema::dropIfExists('proyecto.e_p_c_especialidads');
    }
}
