<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mecip_casos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_caso')->index(); // Ej. 3782431
            $table->string('codigo_subproceso')->index(); // Ej. GES_002_01
            $table->string('macroproceso');
            $table->string('proceso');
            $table->string('subproceso');
            $table->string('version')->default('1.0');
            $table->date('fecha_elaboracion')->nullable();
            $table->string('responsable_analisis')->nullable();
            
            $table->unsignedBigInteger('lider_mecip_id')->nullable();
            $table->foreign('lider_mecip_id')->references('id')->on('users')->onDelete('set null');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->enum('estado_flujo', [
                'borrador',
                'remitido_lider',
                'resuelto_lider',
                'cerrado_admin'
            ])->default('borrador')->index();

            $table->text('dictamen_final')->nullable();
            $table->boolean('automatico_flag')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mecip_caso_componentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mecip_caso_id')->constrained('mecip_casos')->onDelete('cascade');
            $table->enum('tipo', ['insumo', 'producto'])->default('insumo');
            $table->string('nombre');
            $table->string('entidad_origen_destino')->nullable(); // Proveedor o Cliente
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(1);
            $table->timestamps();
        });

        Schema::create('mecip_caso_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mecip_caso_id')->constrained('mecip_casos')->onDelete('cascade');
            $table->string('codigo_actividad')->nullable(); // Ej. ACT_01
            $table->string('nombre');
            $table->text('objetivo')->nullable();
            $table->string('responsable')->nullable();
            $table->integer('orden')->default(1);
            $table->string('estado_revision')->default('pendiente');
            $table->timestamps();
        });

        Schema::create('mecip_caso_tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('mecip_caso_actividades')->onDelete('cascade');
            $table->text('descripcion');
            $table->integer('tiempo_estimado_minutos')->default(0);
            $table->integer('orden')->default(1);
            $table->timestamps();
        });

        Schema::create('mecip_caso_comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mecip_caso_id')->constrained('mecip_casos')->onDelete('cascade');
            $table->foreignId('actividad_id')->nullable()->constrained('mecip_caso_actividades')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('rol_usuario')->default('Usuario');
            $table->text('comentario');
            $table->text('justificacion_camino')->nullable();
            $table->boolean('es_resolucion')->default(false);
            $table->timestamps();
        });

        Schema::create('mecip_caso_cambios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mecip_caso_id')->constrained('mecip_casos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('estado_anterior');
            $table->string('estado_nuevo');
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mecip_caso_cambios');
        Schema::dropIfExists('mecip_caso_comentarios');
        Schema::dropIfExists('mecip_caso_tareas');
        Schema::dropIfExists('mecip_caso_actividades');
        Schema::dropIfExists('mecip_caso_componentes');
        Schema::dropIfExists('mecip_casos');
    }
};
