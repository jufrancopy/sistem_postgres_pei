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
            if (!Schema::hasColumn('pei_chat_messages', 'recipient_id')) {
                $table->foreignId('recipient_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pei_chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('pei_chat_messages', 'recipient_id')) {
                $table->dropForeign(['recipient_id']);
                $table->dropColumn('recipient_id');
            }
        });
    }
};
