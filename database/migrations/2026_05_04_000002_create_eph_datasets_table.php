<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estadistica.eph_datasets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('categoria')->nullable()
                ->comment('ingreso_familiar, vivienda, poblacion, ipm, otro');
            $table->smallInteger('anio');
            $table->string('fuente')->default('INE Paraguay — EPH');
            $table->text('descripcion')->nullable();
            $table->jsonb('datos');           // el JSON completo
            $table->jsonb('columnas')->nullable(); // columnas detectadas automáticamente
            $table->integer('total_filas')->default(0);
            $table->unsignedInteger('cargado_por')->nullable();
            $table->foreign('cargado_por')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['categoria', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadistica.eph_datasets');
    }
};
