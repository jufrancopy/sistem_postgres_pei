<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('activities', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('activities', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('activities', 'date_start')) {
                $table->date('date_start')->nullable();
            }
            if (!Schema::hasColumn('activities', 'date_end')) {
                $table->date('date_end')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['name', 'type', 'description', 'date_start', 'date_end']);
        });
    }
};
