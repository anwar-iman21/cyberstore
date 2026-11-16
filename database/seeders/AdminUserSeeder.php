<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tokocyber.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // GANTI setelah login pertama kali
                'role' => User::ROLE_ADMIN,
                'whatsapp_number' => '6281234567890',
                'email_verified_at' => now(),
            ]
        );
    }
}
