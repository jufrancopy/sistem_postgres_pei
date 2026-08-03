<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patrimonies', function (Blueprint $table) {
            if (!Schema::hasColumn('patrimonies', 'dependency_id')) {
                $table->unsignedInteger('dependency_id')->nullable();
                $table->foreign('dependency_id')->references('id')->on('organigramas')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patrimonies', function (Blueprint $table) {
            if (Schema::hasColumn('patrimonies', 'dependency_id')) {
                $table->dropForeign(['dependency_id']);
                $table->dropColumn('dependency_id');
            }
        });
    }
};
