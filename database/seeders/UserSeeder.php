<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => config('filament-shield.super_admin.name', 'super_admin'),
            'guard_name' => 'web',
        ]);

        $users = [
            ['name' => 'Admin Masanuland', 'email' => 'admin@masanuland.id', 'password' => 'password'],
            ['name' => 'Anugrah', 'email' => 'anugrah@masanuland.com', 'password' => 'Masanuland#@'],
            ['name' => 'Baehaqi', 'email' => 'baehaqi@masanuland.com', 'password' => 'Masanuland#@'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                ['name' => $user['name'], 'password' => Hash::make($user['password'])],
            )->assignRole($role);
        }
    }
}
