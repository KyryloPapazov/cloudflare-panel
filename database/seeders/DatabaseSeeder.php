<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        if (User::where('email', 'admin@example.com')->doesntExist()) {
            User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'), // Hash the password "admin"
            ]);
        }
    }
}
