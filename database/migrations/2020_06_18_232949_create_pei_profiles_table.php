<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreatePeiProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.pei_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('level')->nullable();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->text('values')->nullable();
            $table->string('period')->nullable();
            $table->decimal('numerator')->nullable();
            $table->string('operator')->nullable();
            $table->decimal('denominator')->nullable();
            $table->decimal('goal')->nullable();
            $table->decimal('progress')->nullable();

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->nestedSet();

            $table->softDeletes();
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
        Schema::dropIfExists('planificacion.pei_profiles', function (Blueprint $table) {
            //
        });
    }
}
