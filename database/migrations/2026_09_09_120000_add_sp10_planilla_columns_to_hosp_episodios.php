<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.hosp_episodios', function (Blueprint $table) {
            if (! Schema::connection('pgsql')->hasColumn('bioestadistica.hosp_episodios', 'nro_patronal')) {
                $table->string('nro_patronal', 40)->nullable()->after('cedula_hash');
            }
            if (! Schema::connection('pgsql')->hasColumn('bioestadistica.hosp_episodios', 'ciudad_residencia')) {
                $table->string('ciudad_residencia', 150)->nullable()->after('edad');
            }
            if (! Schema::connection('pgsql')->hasColumn('bioestadistica.hosp_episodios', 'rn_sexo')) {
                $table->string('rn_sexo', 1)->nullable()->after('recien_nacido');
            }
            if (! Schema::connection('pgsql')->hasColumn('bioestadistica.hosp_episodios', 'rn_peso')) {
                $table->unsignedInteger('rn_peso')->nullable()->after('rn_sexo');
            }
        });

        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios DROP CONSTRAINT IF EXISTS hosp_episodios_rn_sexo_chk'
        );
        DB::connection('pgsql')->statement(
            "ALTER TABLE bioestadistica.hosp_episodios
             ADD CONSTRAINT hosp_episodios_rn_sexo_chk CHECK (rn_sexo IN ('M','F') OR rn_sexo IS NULL)"
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios DROP CONSTRAINT IF EXISTS hosp_episodios_rn_peso_chk'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios
             ADD CONSTRAINT hosp_episodios_rn_peso_chk CHECK (rn_peso IS NULL OR rn_peso BETWEEN 200 AND 9000)'
        );
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios DROP CONSTRAINT IF EXISTS hosp_episodios_rn_sexo_chk'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios DROP CONSTRAINT IF EXISTS hosp_episodios_rn_peso_chk'
        );

        Schema::connection('pgsql')->table('bioestadistica.hosp_episodios', function (Blueprint $table) {
            $table->dropColumn(['nro_patronal', 'ciudad_residencia', 'rn_sexo', 'rn_peso']);
        });
    }
};
