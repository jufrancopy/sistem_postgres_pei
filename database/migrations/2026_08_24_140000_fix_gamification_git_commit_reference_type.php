<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixGamificationGitCommitReferenceType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // En PostgreSQL, evitar que reference_type = 'App\Models\User' cuando reference_id es un hash git hexadecimal
        DB::table('gamification_points')
            ->where('action_type', 'git_commit')
            ->where('reference_type', 'App\Models\User')
            ->update(['reference_type' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No revertir
    }
}
