<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@babequ.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN->value,
        ]);
        User::factory()->create([
            'name' => 'Warga',
            'email' => 'warga@email.com',
            'password' => Hash::make('password'),
            'role' => UserRole::CITIZEN->value,
        ]);
        User::factory()->create([
            'name' => 'Pengunjung',
            'email' => 'pengunjung@email.com',
            'password' => Hash::make('password'),
            'role' => UserRole::VISITOR->value,
        ]);

        User::factory(rand(5, 10))->create([
            'role' => UserRole::CITIZEN->value,
        ]);

        User::factory(rand(5, 10))->create([
            'role' => UserRole::VISITOR->value,
        ]);
    }
}
