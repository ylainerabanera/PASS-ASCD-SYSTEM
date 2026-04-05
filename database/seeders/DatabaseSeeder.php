<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::create([
                'name' => env('ADMIN_NAME', 'PASS Admin'),
                'email' => env('ADMIN_EMAIL', 'pass_admin@gmail.com'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin1234')),
                'is_admin' => true,
            ]);
        }
    }
}
