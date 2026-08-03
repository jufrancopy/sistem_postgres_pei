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
        Schema::table('pei_chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('pei_chat_messages', 'origin_module')) {
                $table->string('origin_module')->nullable()->after('reference_url');
            }
            if (!Schema::hasColumn('pei_chat_messages', 'origin_title')) {
                $table->string('origin_title')->nullable()->after('origin_module');
            }
            if (!Schema::hasColumn('pei_chat_messages', 'origin_url')) {
                $table->text('origin_url')->nullable()->after('origin_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pei_chat_messages', function (Blueprint $table) {
            $table->dropColumn(['origin_module', 'origin_title', 'origin_url']);
        });
    }
};
