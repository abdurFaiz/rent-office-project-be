<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {name} {--email=} {--password=}';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->option('email');
        $password = $this->option('password');
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        $this->info("Admin user {$user->email} created successfully!");
    }
}