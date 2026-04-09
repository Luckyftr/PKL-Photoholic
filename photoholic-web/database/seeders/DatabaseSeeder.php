<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Studio;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'minphotoholic@gmail.com',
            'phone' => '08123456789',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@mail.com',
            'phone' => '081234567891',
            'password' => Hash::make('cust123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Studio::create([
            'studio_code' => 'STD-001',
            'name' => 'Classy',
            'photo' => null,
            'max_people_per_session' => 8,
            'session_duration' => 5,
            'photo_strips' => 2,
            'paper_type' => 'negative_film',
            'price' => 45000,
            'is_active' => true,
        ]);


        Studio::create([
            'studio_code' => 'STD-002',
            'name' => 'Spotlight',
            'photo' => null,
            'max_people_per_session' => 8,
            'session_duration' => 5,
            'photo_strips' => 2,
            'paper_type' => 'photo_paper',
            'price' => 45000,
            'is_active' => true,
        ]);
    }
}