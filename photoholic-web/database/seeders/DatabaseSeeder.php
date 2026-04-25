<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Studio;
use App\Models\Blog;
use Carbon\Carbon;
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

        Studio::create([
            'studio_code' => 'STD-003',
            'name' => 'Lavatory',
            'photo' => null,
            'max_people_per_session' => 8,
            'session_duration' => 5,
            'photo_strips' => 2,
            'paper_type' => 'photo_paper',
            'price' => 35000,
            'is_active' => true,
        ]);
        
        Studio::create([
            'studio_code' => 'STD-004',
            'name' => 'Oven',
            'photo' => null,
            'max_people_per_session' => 4,
            'session_duration' => 5,
            'photo_strips' => 2,
            'paper_type' => 'photo_paper',
            'price' => 35000,
            'is_active' => true,
        ]);
        
        Studio::create([
            'studio_code' => 'STD-005',
            'name' => 'Aquarium',
            'photo' => null,
            'max_people_per_session' => 4,
            'session_duration' => 5,
            'photo_strips' => 2,
            'paper_type' => 'photo_paper',
            'price' => 35000,
            'is_active' => true,
        ]);

        Blog::create([
            'photo' => null,
            'title' => 'Anniversary 1th Photoholic',
            'category' => 'event',
            'publish_date' => Carbon::create(2026, 3, 25),
            'short_caption' => 'Yang ulangtahun 25 maret sini merapat!',
            'content' => 'Rayakan anniversary pertama Photoholic bersama kami! Banyak promo dan keseruan menanti 🎉',
            'sync_insta' => true,
            'status' => 'published',
        ]);

        Blog::create([
            'photo' => null,
            'title' => 'Costum Border',
            'category' => 'pengumuman',
            'publish_date' => Carbon::create(2026, 1, 2),
            'short_caption' => 'Kalian bingung mau photobooth tapi pengen custom border diphotoholic bisaa!',
            'content' => 'Sekarang kamu bisa request custom border sesuai keinginanmu! Bikin hasil photobooth makin personal ✨',
            'sync_insta' => true,
            'status' => 'draft',
        ]);

        Blog::create([
            'photo' => null,
            'title' => 'Spesial Valentine',
            'category' => 'promo',
            'publish_date' => Carbon::create(2026, 2, 9),
            'short_caption' => 'Valentine makin terkesan bareng kesayangan',
            'content' => 'Rayakan hari Valentine dengan pengalaman photobooth yang romantis bersama pasanganmu ❤️',
            'sync_insta' => true,
            'status' => 'published',
        ]);
    }
}