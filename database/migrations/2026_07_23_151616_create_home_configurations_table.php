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
        Schema::create('home_configurations', function (Blueprint $table) {
            $table->id();
            // Selección de FODA
            $table->unsignedBigInteger('foda_profile_id')->nullable()->comment('FODA consolidado para mostrar');
            $table->unsignedBigInteger('foda_analisis_id')->nullable()->comment('Análisis específico del FODA');
            // Selección de PEI
            $table->unsignedBigInteger('pei_profile_id')->nullable()->comment('PEI corporativo para mostrar');
            // Módulos activos
            $table->boolean('show_foda')->default(true);
            $table->boolean('show_pei')->default(true);
            $table->boolean('show_riiss')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_configurations');
    }
};
