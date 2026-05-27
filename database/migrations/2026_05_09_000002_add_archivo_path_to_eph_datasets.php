<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega archivo_path a eph_datasets para procesar CSVs grandes en streaming.
 * Hace nullable el campo datos para no forzar carga en memoria.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->table('estadistica.eph_datasets', function (Blueprint $table) {
            // Ruta física del archivo original (CSV/JSON) en storage
            $table->string('archivo_path')->nullable()->after('descripcion')
                ->comment('Ruta en storage/app/eph/ — para procesamiento en streaming');

            $table->string('archivo_extension', 10)->nullable()->after('archivo_path')
                ->comment('csv, json');
        });

        // Hacer datos nullable (ya no es obligatorio si hay archivo_path)
        \Illuminate\Support\Facades\DB::connection('pgsql')
            ->statement('ALTER TABLE estadistica.eph_datasets ALTER COLUMN datos DROP NOT NULL');
    }

    public function down(): void
    {
        Schema::connection('pgsql')->table('estadistica.eph_datasets', function (Blueprint $table) {
            $table->dropColumn(['archivo_path', 'archivo_extension']);
        });
    }
};
