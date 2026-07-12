<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planificacion.marcos_referenciales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo', 50)->default('general'); // pnd, ods, bsc, mecip, general...
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['nombre', 'tipo']);
        });

        Schema::create('planificacion.pei_profile_marcos', function (Blueprint $table) {
            $table->uuid('pei_profile_id');
            $table->foreignId('marco_id')->constrained('planificacion.marcos_referenciales')->cascadeOnDelete();

            $table->primary(['pei_profile_id', 'marco_id']);

            $table->foreign('pei_profile_id')
                ->references('id')
                ->on('planificacion.pei_profiles')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planificacion.pei_profile_marcos');
        Schema::dropIfExists('planificacion.marcos_referenciales');
    }
};
