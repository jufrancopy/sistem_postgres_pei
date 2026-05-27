<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->string('id_establecimiento')->primary()->comment('Ej: 09-PS-48');
            $table->string('nombre_oficial');
            $table->integer('codigo')->nullable();
            $table->string('tipo_est', 5)->comment('PS, HR, US, CE, CP, HO, HC, OT');
            $table->string('complejidad', 60)->comment('No Hospitalario de Baja Complejidad, etc.');
            $table->string('departamento', 40);
            $table->string('microred', 60)->nullable();
            $table->string('prestador', 20)->comment('IPS, CONVENIO, TERCERIZADO');
            $table->tinyInteger('nro_departamento');
            $table->string('tipologia_clasificacion', 40)->comment('PUESTO SANITARIO, HOSPITAL REGIONAL, etc.');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('nm_empresa_costos', 80)->nullable();
            $table->string('access_nm_empresa', 80)->nullable();
            $table->string('area_gestion', 30)->nullable();
            $table->string('situacion_inmueble', 60)->nullable();
            $table->string('observacion', 100)->nullable();
            $table->string('sistema_hospitalario', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->string('codigo_ine', 20)->nullable();

            // Campos computados para el matching
            $table->tinyInteger('nivel_atencion')->nullable()->comment('1, 2, 3');
            $table->tinyInteger('grado_complejidad')->nullable()->comment('1, 2, 3');
            $table->boolean('es_hospitalario')->default(false);
            $table->boolean('tiene_internacion')->default(false);
            $table->boolean('tiene_quirofano_req')->default(false);
            $table->boolean('tiene_uti_req')->default(false);
            $table->boolean('tiene_urgencias_req')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('tipo_est');
            $table->index('complejidad');
            $table->index('departamento');
            $table->index('microred');
            $table->index('prestador');
            $table->index('tipologia_clasificacion');
            $table->index('nivel_atencion');
            $table->index('grado_complejidad');
            $table->index(['tipo_est', 'complejidad']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};
