<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.record_values')) {
            return;
        }

        Schema::connection('pgsql')->table('bioestadistica.record_values', function (Blueprint $table) {
            if (! Schema::connection('pgsql')->hasColumn('bioestadistica.record_values', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('value_json');
                $table->index('created_by', 'bio_record_values_created_by_index');
            }
            if (! Schema::connection('pgsql')->hasColumn('bioestadistica.record_values', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                $table->index('updated_by', 'bio_record_values_updated_by_index');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.record_values')) {
            return;
        }

        Schema::connection('pgsql')->table('bioestadistica.record_values', function (Blueprint $table) {
            if (Schema::connection('pgsql')->hasColumn('bioestadistica.record_values', 'updated_by')) {
                $table->dropIndex('bio_record_values_updated_by_index');
                $table->dropColumn('updated_by');
            }
            if (Schema::connection('pgsql')->hasColumn('bioestadistica.record_values', 'created_by')) {
                $table->dropIndex('bio_record_values_created_by_index');
                $table->dropColumn('created_by');
            }
        });
    }
};
