<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organigramas', function (Blueprint $table) {
            $table->string('tipo_establecimiento', 20)->nullable()->after('email')
                ->comment('H., H.R., C.S., C.P., U.S., C.M.I. — null si es unidad administrativa');
            $table->string('nivel_complejidad', 5)->nullable()->after('tipo_establecimiento')
                ->comment('N1, N2, N3, N4');
            $table->string('tenencia', 20)->nullable()->after('nivel_complejidad')
                ->comment('PROPIO, CONVENIO, TERCERIZADO');
            $table->boolean('tiene_aop')->default(false)->after('tenencia')
                ->comment('Si tiene boca de atención AOP');
            $table->string('region')->nullable()->after('tiene_aop')
                ->comment('ASUNCIÓN Y CENTRAL, ORIENTAL, OCCIDENTAL');
            $table->unsignedBigInteger('locality_id')->nullable()->after('region');
            $table->foreign('locality_id')->references('id')->on('localities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('organigramas', function (Blueprint $table) {
            $table->dropForeign(['locality_id']);
            $table->dropColumn([
                'tipo_establecimiento', 'nivel_complejidad',
                'tenencia', 'tiene_aop', 'region', 'locality_id',
            ]);
        });
    }
};
