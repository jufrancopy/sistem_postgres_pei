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
        Schema::create('riiss_establecimiento_especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('establecimiento_id')->comment('Referencia a establecimientos.id_establecimiento');
            $table->foreignId('especialidad_id')->constrained('riiss_especialidades')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id_establecimiento')->on('establecimientos')->onDelete('cascade');
            $table->unique(['establecimiento_id', 'especialidad_id'], 'est_esp_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_establecimiento_especialidades');
    }
};
