<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin already exists
        $admin = Admin::where('username', 'admin')->first();

        if ($admin) {
            // Update existing admin with email
            $admin->update([
                'name' => 'Administrator',
                'email' => 'admin@mestakara.com',
            ]);
            $this->command->info('Admin user updated successfully!');
        } else {
            // Create new admin
            Admin::create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@mestakara.com',
                'password' => Hash::make('admin123'),
            ]);
            $this->command->info('Admin user created successfully!');
        }

        $this->command->info('Username: admin');
        $this->command->info('Email: admin@mestakara.com');
        $this->command->info('Password: admin123');
        $this->command->warn('Please change the password after first login!');
    }
}