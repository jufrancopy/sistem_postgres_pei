<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            'email_verified_at' => now(),
            'password' => Hash::make('Jcf3458435'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $user = User::find(1);

        if ($user) {
            // Asocia el rol con ID 1 (Administrador) al usuario
            $user->assignRole('Administrador');
        }
    }
}
