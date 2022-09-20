<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $user = User::create([
            'name' => 'Julio Franco',
            'email' => 'jucfra23@gmail.com',
            'password' => bcrypt('jcf3458435'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        
        $user->assignRole('Administrador');
        $permissions = ['role-list', 'role-edit', 'role-create', 'role-delete'];
        
        foreach ($permissions as $permission) {
            $user->givePermissionTo($permission);
        }
        
    }
}
