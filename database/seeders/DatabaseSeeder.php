<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'full_name' => 'Admin Adminovic',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'phone_number' => '000-000-000'
        ]);
    }
}
