<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inmueble_contratos', function (Blueprint $table) {
            $table->id();
            $table->string('id_establecimiento');
            $table->string('tipo_contrato', 30)->comment('AMPLIACION, MANTENIMIENTO');
            $table->string('nro_contrato', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('costo_total', 15, 2)->nullable();
            $table->integer('porcentaje_avance')->nullable();
            $table->string('archivo_url')->nullable();
            $table->timestamps();
            
            $table->foreign('id_establecimiento')->references('id_establecimiento')->on('establecimientos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inmueble_contratos');
    }
};
