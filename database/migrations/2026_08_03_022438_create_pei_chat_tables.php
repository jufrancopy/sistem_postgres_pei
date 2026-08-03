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
        Schema::create('pei_chat_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pei_profile_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('parent_id')->nullable();
            $table->text('message')->nullable();
            $table->jsonb('attachments')->nullable();
            $table->boolean('is_system')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('pei_profile_id')
                  ->references('id')
                  ->on('planificacion.pei_profiles')
                  ->onDelete('cascade');

            $table->index(['pei_profile_id', 'created_at']);
        });

        Schema::table('pei_chat_messages', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('pei_chat_messages')
                  ->onDelete('cascade');
        });

        Schema::create('pei_chat_reads', function (Blueprint $table) {
            $table->id();
            $table->uuid('pei_profile_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->uuid('last_read_message_id')->nullable();
            $table->timestamps();

            $table->foreign('pei_profile_id')
                  ->references('id')
                  ->on('planificacion.pei_profiles')
                  ->onDelete('cascade');

            $table->unique(['pei_profile_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pei_chat_reads');
        Schema::dropIfExists('pei_chat_messages');
    }
};
