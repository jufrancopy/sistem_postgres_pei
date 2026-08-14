<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pei_chat_reactions')) {
            Schema::create('pei_chat_reactions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('message_id');
                $table->bigInteger('user_id')->unsigned();
                $table->string('emoji', 20);
                $table->timestamps();

                $table->foreign('message_id')
                      ->references('id')
                      ->on('pei_chat_messages')
                      ->onDelete('cascade');

                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->unique(['message_id', 'user_id', 'emoji']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pei_chat_reactions');
    }
};
