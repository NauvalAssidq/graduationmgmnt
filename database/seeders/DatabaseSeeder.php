<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default Admin account
        \App\Models\Admin::create([
            'name' => 'Admin Pertama',
            'nip' => '123456789012345678',
            'email' => 'admin@ar-raniry.ac.id',
            'password' => bcrypt('password'),
        ]);
    }
}
