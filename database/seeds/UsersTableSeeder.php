<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('users')->insert([
            'name' => 'Julio Franco',
            'email' => 'jucfra23@gmail.com',
            'password' => bcrypt('jcf3458435'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),

        ]);
    }
}
