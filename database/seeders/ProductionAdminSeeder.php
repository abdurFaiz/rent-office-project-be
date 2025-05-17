<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => env('ADMIN_NAME', 'Admin'),
            'email' => env('ADMIN_EMAIL', 'admin@rent-office-main-suoixw.laravel.cloud'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'your-secure-password')),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);
    }
}