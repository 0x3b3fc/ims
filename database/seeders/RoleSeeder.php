<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->addRole('admin');

        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
        ]);
        $manager->addRole('manager');

        $viewer = User::create([
            'name' => 'User',
            'email' => 'viewer@test.com',
            'password' => bcrypt('password'),
        ]);
        $viewer->addRole('viewer');

    }
}
