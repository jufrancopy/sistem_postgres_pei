<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.records')) {
            return;
        }

        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            if (! Schema::connection('pgsql')->hasColumn('bioestadistica.records', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->index('updated_by', 'bio_records_updated_by_index');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.records')) {
            return;
        }

        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            if (Schema::connection('pgsql')->hasColumn('bioestadistica.records', 'updated_by')) {
                $table->dropIndex('bio_records_updated_by_index');
                $table->dropColumn('updated_by');
            }
        });
    }
};
