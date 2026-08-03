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
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->string('condicion_inmueble', 30)->nullable()->comment('CONVENIO, ALQUILADO, PROPIO');
            
            // Campos si es PROPIO
            $table->decimal('superficie_terreno', 12, 2)->nullable();
            $table->decimal('superficie_construida', 12, 2)->nullable();
            $table->string('plano_url')->nullable();
            
            // Campos si es ALQUILADO
            $table->string('nro_llamado', 100)->nullable();
            $table->string('nro_contrato_alquiler', 100)->nullable();
            $table->string('propietario', 150)->nullable();
            $table->date('vigencia_desde')->nullable();
            $table->date('vigencia_hasta')->nullable();
            $table->decimal('canon_mensual', 15, 2)->nullable();
            $table->string('fecha_pago_alquiler', 50)->nullable();
            
            // Campos si es CONVENIO
            $table->string('nro_resolucion_convenio', 100)->nullable();
            $table->date('vigencia_convenio_desde')->nullable();
            $table->date('vigencia_convenio_hasta')->nullable();
            $table->text('descripcion_convenio')->nullable();
            $table->text('locales_convenio')->nullable();
            $table->string('archivo_convenio_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->dropColumn([
                'condicion_inmueble',
                'superficie_terreno',
                'superficie_construida',
                'plano_url',
                'nro_llamado',
                'nro_contrato_alquiler',
                'propietario',
                'vigencia_desde',
                'vigencia_hasta',
                'canon_mensual',
                'fecha_pago_alquiler',
                'nro_resolucion_convenio',
                'vigencia_convenio_desde',
                'vigencia_convenio_hasta',
                'descripcion_convenio',
                'locales_convenio',
                'archivo_convenio_url',
            ]);
        });
    }
};
