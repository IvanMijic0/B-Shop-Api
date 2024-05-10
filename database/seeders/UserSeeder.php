<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
