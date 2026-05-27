<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regla_seccion_formularios', function (Blueprint $table) {
            $table->id();
            $table->string('tipologia_clasificacion', 40);
            $table->string('complejidad', 60)->nullable();
            $table->foreignId('formulario_seccion_id')->constrained('formulario_secciones')->cascadeOnDelete();
            $table->boolean('requerida')->default(true);
            $table->boolean('aplica')->default(true);
            $table->text('condicion')->nullable();
            $table->text('nota')->nullable();
            $table->timestamps();

            $table->index(['tipologia_clasificacion', 'complejidad']);
            $table->index('requerida');
            $table->unique(
                ['tipologia_clasificacion', 'complejidad', 'formulario_seccion_id'],
                'regla_unica'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regla_seccion_formularios');
    }
};
