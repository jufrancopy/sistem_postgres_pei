<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class CreateCoordinadorRiissRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::firstOrCreate(['name' => 'Coordinador RIISS']);
        Role::firstOrCreate(['name' => 'Coordinación RIISS']);
        Role::firstOrCreate(['name' => 'Coordinador - RIISS']);
        Role::firstOrCreate(['name' => 'Analista RIISS']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
