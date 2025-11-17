<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => '123123', // Plain text password
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => '123123', // Plain text password
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Employee
        User::create([
            'name' => 'Employee',
            'email' => 'employee@example.com',
            'password' => '123123', // Plain text password
            'role' => 'employee',
            'status' => 'active',
        ]);
    }
}
