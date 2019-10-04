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
<<<<<<< HEAD
=======

        DB::table('users')->insert([
            'name' => 'Don Gerente',
            'email' => 'gerente@gmail.com',
            'password' => bcrypt('jcf3458435'),
        ]);

        DB::table('users')->insert([
            'name' => 'Don Director',
            'email' => 'director@gmail.com',
            'password' => bcrypt('jcf3458435'),
        ]);

         DB::table('users')->insert([
            'name' => 'Don Departamento',
            'email' => 'departamento@gmail.com',
            'password' => bcrypt('jcf3458435'),
        ]);

        DB::table('users')->insert([
            'name' => 'Don Seccion',
            'email' => 'seccion@gmail.com',
            'password' => bcrypt('jcf3458435'),
        ]); 
>>>>>>> 241a26be8edd2bbe030de9237187923b504d412f
    }
}
