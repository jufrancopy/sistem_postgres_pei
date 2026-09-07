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
        Schema::create('riiss_est_esp_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->string('establecimiento_id');
            $table->foreignId('especialidad_id')->constrained('riiss_especialidades')->onDelete('cascade');
            $table->foreignId('medicamento_id')->constrained('riiss_medicamentos')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id_establecimiento')->on('establecimientos')->onDelete('cascade');
            $table->unique(['establecimiento_id', 'especialidad_id', 'medicamento_id'], 'eem_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_est_esp_medicamentos');
    }
};
