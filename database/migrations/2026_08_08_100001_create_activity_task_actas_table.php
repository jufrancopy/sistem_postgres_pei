<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_task_actas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_task_id')->constrained('activity_tasks')->cascadeOnDelete();
            $table->string('uuid', 64)->unique();
            $table->string('numero_acta', 50)->nullable();
            $table->string('institucion', 255)->default('INSTITUTO DE PREVISIÓN SOCIAL');
            $table->string('dependencia', 255)->nullable();
            $table->string('lugar', 255)->default('REUNIÓN VIRTUAL');
            $table->date('fecha')->nullable();
            $table->string('hora_desde', 20)->nullable();
            $table->string('hora_hasta', 20)->nullable();
            $table->text('convocados_texto')->nullable();
            $table->text('temas_tratar')->nullable();
            $table->text('objetivo')->nullable();
            $table->longText('desarrollo')->nullable();
            $table->text('acuerdos')->nullable();
            $table->json('compromisos')->nullable();
            $table->string('estado', 30)->default('borrador'); // borrador, finalizada
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('activity_task_acta_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('activity_task_actas')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('correo', 150)->nullable();
            $table->string('dependencia', 255)->nullable();
            $table->string('cargo', 150)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->boolean('asistio')->default(true);
            $table->boolean('registrado_via_qr')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_task_acta_participantes');
        Schema::dropIfExists('activity_task_actas');
    }
};
