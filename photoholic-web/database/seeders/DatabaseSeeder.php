<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seeder Manual (Bawaanmu)
        // CATATAN: Jika di dalam data 'users' dari database lamamu SUDAH ADA akun admin,
        // kamu bisa memberikan tanda komentar (//) pada baris AdminSeeder di bawah ini
        // agar tidak terjadi error "email sudah digunakan" (duplicate entry).
       // $this->call(AdminSeeder::class);

        // 2. Tabel Induk (Data Master yang harus ada lebih dulu)
        $this->call([
            UsersTableSeeder::class,
            StudiosTableSeeder::class,
        ]);

        // 3. Tabel Anak (Bergantung pada tabel Induk)
        $this->call([
            BookingsTableSeeder::class, // Butuh user_id dan studio_id
        ]);

        // 4. Tabel Lainnya (Bisa berjalan mandiri)
        $this->call([
            BlogsTableSeeder::class,
            ActivityLogsTableSeeder::class,
        ]);
        $this->call(ActivityLogsTableSeeder::class);
    }
}