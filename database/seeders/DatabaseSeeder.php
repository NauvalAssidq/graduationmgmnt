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
        \App\Models\Admin::updateOrCreate(
            ['email' => 'admin@ar-raniry.ac.id'],
            [
                'name' => 'Admin Pertama',
                'nip' => '123456789012345678',
                'role' => 'system_admin',
                'password' => bcrypt('password'),
            ]
        );

        \App\Models\Admin::updateOrCreate(
            ['email' => 'operator@ar-raniry.ac.id'], 
            [
                'name' => 'Operator Buku',
                'nip' => '987654321098765432',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );
    }
}
