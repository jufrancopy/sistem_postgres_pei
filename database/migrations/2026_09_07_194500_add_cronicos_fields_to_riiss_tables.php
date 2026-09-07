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
        Schema::table('riiss_medicamentos', function (Blueprint $table) {
            $table->boolean('es_cronico')->default(false)->index();
            $table->string('categoria_terapeutica', 100)->nullable()->index();
            $table->boolean('es_psicotropico')->default(false)->index();
            $table->string('resolucion_respaldo', 100)->nullable();
        });

        Schema::table('establecimientos', function (Blueprint $table) {
            $table->boolean('habilita_farmacia_cronicos')->default(false)->index();
            $table->boolean('habilita_empadronamiento_cronicos')->default(false)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riiss_medicamentos', function (Blueprint $table) {
            $table->dropColumn(['es_cronico', 'categoria_terapeutica', 'es_psicotropico', 'resolucion_respaldo']);
        });

        Schema::table('establecimientos', function (Blueprint $table) {
            $table->dropColumn(['habilita_farmacia_cronicos', 'habilita_empadronamiento_cronicos']);
        });
    }
};
