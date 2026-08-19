<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.fields', function (Blueprint $table) {
            $table->foreignId('detalle_id')
                ->nullable()
                ->constrained('bioestadistica.variable_detalles')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.fields', function (Blueprint $table) {
            $table->dropConstrainedForeignId('detalle_id');
        });
    }
};
