<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [];

        foreach (['user', 'admin'] as $roleName) {
            $roles[$roleName] = Role::updateOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
                []
            );
        }

        $users = [
            ['name' => 'user', 'email' => 'user@gmail', 'role' => 'user'],
            ['name' => 'studen', 'email' => 'studen@gmail', 'role' => 'user'],
            ['name' => 'admin', 'email' => 'admin@gmail', 'role' => 'admin'],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]
            );

            $user->syncRoles($roles[$userData['role']]);
        }
    }
}
