<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFodasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fodas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('nivel', ['infraestructura', 'tecnologia', 'tthh','compras', 'logistica_interna', 'operaciones', 'logistica_externa', 'marqueting_ventas', 'servicio_post_venta'])->nullable();    
            $table->string('aspecto')->nullable();
            $table->enum('tipo', ['fortaleza', 'oportunidad', 'debilidad', 'amenaza'])->nullable();
            $table->enum('ambiente', ['interno', 'externo'])->nullable();
            $table->enum('ocurrencia', [0.10, 0.30, 0.50, 0.70, 0.90])->nullable();
            $table->enum('impacto', [0.05, 0.1, 0.2, 0.4, 0.8])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return vpopmail_del_domain(domain)
     */
    public function down()
    {
        Schema::dropIfExists('fodas');
    }
}
