<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles($adminRole);

        User::firstOrCreate(
            ['email' => 'teacher@gmail.com'],
            [
                'name' => 'Teacher',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        )->syncRoles($teacherRole);

        User::firstOrCreate(
            ['email' => 'student@gmail.com'],
            [
                'name' => 'Student',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        )->syncRoles($studentRole);
    }
}
