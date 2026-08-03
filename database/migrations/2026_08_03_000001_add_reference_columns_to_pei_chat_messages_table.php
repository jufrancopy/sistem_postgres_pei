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
            if (!Schema::hasColumn('pei_chat_messages', 'reference_type')) {
                $table->string('reference_type')->nullable()->after('recipient_id');
            }
            if (!Schema::hasColumn('pei_chat_messages', 'reference_id')) {
                $table->string('reference_id')->nullable()->after('reference_type');
            }
            if (!Schema::hasColumn('pei_chat_messages', 'reference_title')) {
                $table->string('reference_title')->nullable()->after('reference_id');
            }
            if (!Schema::hasColumn('pei_chat_messages', 'reference_url')) {
                $table->text('reference_url')->nullable()->after('reference_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pei_chat_messages', function (Blueprint $table) {
            $table->dropColumn(['reference_type', 'reference_id', 'reference_title', 'reference_url']);
        });
    }
};
