<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homologaciones', function (Blueprint $table) {
            $table->id();
            $table->string('alias_fuente');
            $table->string('id_establecimiento_destino');
            $table->foreign('id_establecimiento_destino')
                  ->references('id_establecimiento')
                  ->on('establecimientos');
            $table->string('fuente', 20)->comment('ACCESS, POBLACION, RRHH, DIM');
            $table->timestamps();

            $table->index('alias_fuente');
            $table->index('fuente');
            $table->index(['alias_fuente', 'fuente']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homologaciones');
    }
};
