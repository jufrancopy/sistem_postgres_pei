<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.establecimientos', function (Blueprint $table) {
            $table->boolean('incluye_tercerizado')->default(false)->after('prestador');
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.establecimientos', function (Blueprint $table) {
            $table->dropColumn('incluye_tercerizado');
        });
    }
};
