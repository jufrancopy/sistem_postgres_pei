<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('complejidad_tipo_id')->nullable()->after('complejidad');
            $table->foreign('complejidad_tipo_id')->references('id')->on('complejidad_tipos')->nullOnDelete();
        });

        // Migrar datos existentes usando el campo legacy
        DB::statement("
            UPDATE establecimientos e
            SET complejidad_tipo_id = ct.id
            FROM complejidad_tipos ct
            WHERE ct.nombre_legacy = e.complejidad
              AND e.deleted_at IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->dropForeign(['complejidad_tipo_id']);
            $table->dropColumn('complejidad_tipo_id');
        });
    }
};
