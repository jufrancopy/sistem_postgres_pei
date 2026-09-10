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
        Schema::create('riiss_validacion_establecimientos', function (Blueprint $table) {
            $table->id();
            $table->string('establecimiento_id', 50)->index();
            $table->foreignId('sesion_validador_id')->nullable()->constrained('riiss_sesiones_validador')->nullOnDelete();
            $table->string('validador_nombre', 200);
            $table->string('validador_cargo', 150)->nullable();
            $table->string('validador_documento', 50)->nullable();
            $table->string('estado', 30)->default('validado')->comment('validado, en_proceso');
            $table->integer('total_db')->default(0);
            $table->integer('total_activas')->default(0);
            $table->integer('total_inactivas')->default(0);
            $table->integer('total_agregadas')->default(0);
            $table->text('notas')->nullable();
            $table->longText('firma_digital')->nullable();
            $table->timestamp('firmado_at')->nullable();
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id_establecimiento')->on('establecimientos')->onDelete('cascade');
            $table->unique(['establecimiento_id', 'sesion_validador_id'], 'riiss_val_est_sesion_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_validacion_establecimientos');
    }
};
