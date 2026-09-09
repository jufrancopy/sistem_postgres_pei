<?php

use App\Application\Bioestadistica\Dictionary\EspecialidadesCatalogNormalizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Consolidar filas duplicadas por código / nombre
        (new EspecialidadesCatalogNormalizer)->normalize();

        // Nombres reales en PG (Laravel prefixa el schema en el índice/restricción)
        DB::connection('pgsql')->statement('
            ALTER TABLE bioestadistica.especialidades_medicas
            DROP CONSTRAINT IF EXISTS bioestadistica_especialidades_medicas_nombre_contexto_unique
        ');
        DB::connection('pgsql')->statement('
            DROP INDEX IF EXISTS bioestadistica.bioestadistica_especialidades_medicas_contexto_index
        ');

        Schema::connection('pgsql')->table('bioestadistica.especialidades_medicas', function (Blueprint $table) {
            $table->dropColumn('contexto');
        });

        // Evitar choque de unique(nombre) si quedaron acentos distintos con mismo normalizado
        // (ya consolidado en EspecialidadesCatalogNormalizer::consolidateByNormalizedName)

        // SoftDeletes no excluye unique a nivel DB: purgar trashed que chocan con activos
        DB::connection('pgsql')->statement("
            DELETE FROM bioestadistica.especialidades_medicas d
            USING bioestadistica.especialidades_medicas a
            WHERE d.deleted_at IS NOT NULL
              AND a.deleted_at IS NULL
              AND (
                    d.nombre = a.nombre
                 OR (d.codigo IS NOT NULL AND d.codigo = a.codigo)
              )
        ");

        // Unique parciales: permiten historial soft-deleted sin chocar
        DB::connection('pgsql')->statement("
            CREATE UNIQUE INDEX especialidades_medicas_nombre_unique
            ON bioestadistica.especialidades_medicas (nombre)
            WHERE deleted_at IS NULL
        ");
        DB::connection('pgsql')->statement("
            CREATE UNIQUE INDEX especialidades_medicas_codigo_unique
            ON bioestadistica.especialidades_medicas (codigo)
            WHERE deleted_at IS NULL AND codigo IS NOT NULL
        ");
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.especialidades_medicas_nombre_unique');
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.especialidades_medicas_codigo_unique');

        Schema::connection('pgsql')->table('bioestadistica.especialidades_medicas', function (Blueprint $table) {
            $table->string('contexto', 40)->default('ambulatorio');
        });

        Schema::connection('pgsql')->table('bioestadistica.especialidades_medicas', function (Blueprint $table) {
            $table->unique(['nombre', 'contexto']);
            $table->index('contexto');
        });
    }
};
