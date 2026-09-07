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
        Schema::create('riiss_auditoria_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique()->index();
            $table->string('pin', 10);
            $table->string('establecimiento_id')->nullable()->comment('Referencia a establecimientos.id_establecimiento o null');
            $table->string('destinatario')->nullable();
            $table->integer('duracion_horas')->default(24);
            $table->timestamp('expira_en')->index();
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('visitas_count')->default(0);
            $table->timestamp('ultimo_acceso_at')->nullable();
            $table->string('ip_ultimo_acceso', 45)->nullable();
            $table->string('estado', 20)->default('activo');
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id_establecimiento')->on('establecimientos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_auditoria_tokens');
    }
};
