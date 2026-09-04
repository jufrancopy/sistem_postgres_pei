<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            $table->string('origen_carga', 30)->default('manual')->after('estado');
            $table->string('import_archivo', 255)->nullable()->after('origen_carga');
            $table->string('import_hoja', 255)->nullable()->after('import_archivo');
        });

        DB::connection('pgsql')->statement(
            "ALTER TABLE bioestadistica.records
             ADD CONSTRAINT bio_records_origen_carga_check
             CHECK (origen_carga IN ('manual', 'importacion_sp'))"
        );
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.records DROP CONSTRAINT IF EXISTS bio_records_origen_carga_check'
        );

        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            $table->dropColumn(['origen_carga', 'import_archivo', 'import_hoja']);
        });
    }
};
