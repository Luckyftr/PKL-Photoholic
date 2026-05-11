<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('users')->delete();
        
        DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Admin',
                'email' => 'minphotoholic@gmail.com',
                'email_verified_at' => NULL,
                'phone' => '08123456789',
                'role' => 'admin',
                'status' => 'active',
                'password' => '$2y$12$v2JGVpRUUsm9V0B207.jjeX7YkKtSMRP0qLx75ZdFkzwuTuO19.vK',
                'remember_token' => NULL,
                'created_at' => '2026-04-25 14:04:37',
                'updated_at' => '2026-05-02 11:40:41',
                'google_id' => NULL,
                'address' => NULL,
                'photo' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Customer',
                'email' => 'customer@mail.com',
                'email_verified_at' => NULL,
                'phone' => '081234567891',
                'role' => 'customer',
                'status' => 'active',
                'password' => '$2y$12$WDuCI/1DXFJDWRkupRYuW.Vx9bKqlG/5wdctMcce7AN5ylhrbdFha',
                'remember_token' => NULL,
                'created_at' => '2026-04-25 14:04:38',
                'updated_at' => '2026-04-25 14:04:38',
                'google_id' => NULL,
                'address' => NULL,
                'photo' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Nathaniel',
                'email' => 'rujtub@oliesmail.com',
                'email_verified_at' => NULL,
                'phone' => '085706103199',
                'role' => 'customer',
                'status' => 'active',
                'password' => '$2y$12$FFixC5d4Ts5nNEnopxYWxeFzOgNIU.b.L44tIQHLnym0bH1HR7X0i',
                'remember_token' => NULL,
                'created_at' => '2026-04-25 14:19:03',
                'updated_at' => '2026-05-03 20:10:28',
                'google_id' => NULL,
                'address' => 'Heaven',
                'photo' => 'users/i1ozheeCj9vx9makWnSdYZ2NVhpGd4SXHj6bTRGS.jpg',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'lucky aja',
                'email' => 'luckyy.erp@gmail.com',
                'email_verified_at' => NULL,
                'phone' => '085706103139',
                'role' => 'customer',
                'status' => 'active',
                'password' => '$2y$12$89JRKGMVk8c0dGAyNqQYm.qVMQQ.99mFB5ox/I7qle4rp1ZONNQZu',
                'remember_token' => NULL,
                'created_at' => '2026-05-01 19:57:10',
                'updated_at' => '2026-05-01 20:43:46',
                'google_id' => '105970798067534068304',
                'address' => NULL,
                'photo' => NULL,
            ),
        ));
        
        
    }
}