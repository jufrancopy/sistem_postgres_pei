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
            $table->text('resolucion_respaldo')->nullable()->change();
            $table->text('nombre')->nullable()->change();
            $table->text('concentracion')->nullable()->change();
            $table->text('forma_farmaceutica')->nullable()->change();
            $table->text('presentacion')->nullable()->change();

            if (!Schema::hasColumn('riiss_medicamentos', 'es_vademecum')) {
                $table->boolean('es_vademecum')->default(false)->index();
            }
            if (!Schema::hasColumn('riiss_medicamentos', 'uso_vademecum')) {
                $table->string('uso_vademecum')->nullable()->index();
            }
            if (!Schema::hasColumn('riiss_medicamentos', 'via_administracion')) {
                $table->string('via_administracion')->nullable();
            }
            if (!Schema::hasColumn('riiss_medicamentos', 'unidad_medida')) {
                $table->string('unidad_medida')->nullable();
            }
            if (!Schema::hasColumn('riiss_medicamentos', 'especialidades_vademecum')) {
                $table->text('especialidades_vademecum')->nullable();
            }
        });

        if (!Schema::hasTable('riiss_especialidad_vademecum')) {
            Schema::create('riiss_especialidad_vademecum', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('especialidad_id');
                $table->foreign('especialidad_id')->references('id')->on('bioestadistica.especialidades_medicas')->onDelete('cascade');
                $table->foreignId('medicamento_id')->constrained('riiss_medicamentos')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['especialidad_id', 'medicamento_id'], 'riiss_esp_med_vademecum_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_especialidad_vademecum');

        Schema::table('riiss_medicamentos', function (Blueprint $table) {
            $table->dropColumn([
                'es_vademecum',
                'uso_vademecum',
                'concentracion',
                'forma_farmaceutica',
                'via_administracion',
                'presentacion',
                'unidad_medida',
                'especialidades_vademecum',
            ]);
        });
    }
};
