<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEPCServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto.e_p_c_servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item');
            $table->string('type');
            $table->text('description');
            

            /*    
            $table->unsignedInteger('detail_infraestructura_id');
            $table->foreign('detail_infraestructura_id')->references('id')->on('proyecto.e_p_c_infraestructuras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('detail_medicamento-insumo_id');
            $table->foreign('detail_medicamento-insumo_id')->references('id')->on('proyecto.e_p_c_medicamento_insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('detail_apoyo-administrativo_id');
            $table->foreign('detail_apoyo-administrativo_id')->references('id')->on('proyecto.e_p_c_apoyo_administrativos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            */

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
        Schema::dropIfExists('proyecto.e_p_c_servicios');
    }
}
