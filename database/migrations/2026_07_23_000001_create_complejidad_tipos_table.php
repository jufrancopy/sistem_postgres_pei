<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complejidad_tipos', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('grado')->unique()->comment('1 al 6');
            $table->string('nombre', 80)->comment('Ej: No Hospitalaria de Baja Complejidad');
            $table->string('nombre_legacy', 80)->nullable()->comment('Valor anterior en establecimientos.complejidad');
            $table->tinyInteger('nivel_atencion')->comment('1, 2, 3 o 4');
            $table->string('tipo_establecimiento', 60)->nullable()->comment('Puesto Sanitario, Clínica Periférica, etc.');
            $table->boolean('es_hospitalario')->default(false);
            $table->boolean('requiere_internacion')->default(false);
            $table->boolean('requiere_quirofano')->default(false);
            $table->boolean('requiere_uti')->default(false);
            $table->boolean('requiere_urgencias')->default(false);
            $table->string('color', 10)->default('#6b7280');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complejidad_tipos');
    }
};
