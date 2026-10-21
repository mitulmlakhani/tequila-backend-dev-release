<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class CreateSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Super Admin', 
            'email' => 'superadmin@chetu.com',
            'password' => bcrypt('Chetu@123'),
            'status' => true,
            'user_type' => 1
        ]);
    
        $role = Role::create([
            'name' => 'TequilaSuperAdmin',
            'guard_name' => 'web',
            'is_deletable' => false,
            'status' => true,
            'role_type'=>1
        ]);
     
        $user->assignRole([$role->id]);
    }
}
