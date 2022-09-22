<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateOrganigramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organigramas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dependency');

            $table->nestedSet();

            $table->string('manager');
            $table->integer('phone');
            $table->string('email')->unique();

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('organigramas', function (Blueprint $table) {
            $table->dropNestedSet();
        });

        Schema::dropIfExists('organigramas');
    }
}
