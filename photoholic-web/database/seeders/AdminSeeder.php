<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat akun admin pertama
        User::create([
            'name'     => 'Admin Photoholic',
            'email'    => 'minphotoholic@gmail.com',
            'phone'    => '08123456789',
            'address'  => '-',
            'photo'    => null, // Bisa diisi path foto jika ada
            'password' => Hash::make('admin123'), // Ganti dengan password yang aman
            'role'     => 'admin',
            'status'   => 'active',
        ]);

        $this->command->info('Akun Admin berhasil dibuat!');
    }
}