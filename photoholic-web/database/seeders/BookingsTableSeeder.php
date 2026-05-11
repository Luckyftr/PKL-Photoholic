<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('bookings')->delete();
        
        DB::table('bookings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'booking_code' => 'INV-0PYCPEW',
                'user_id' => 3,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '11:00',
                'end_time' => '11:15',
                'payment_method' => 'qris',
                'total_price' => 135000,
                'notes' => 'mau yang cantik',
                'status' => 'confirmed',
                'created_at' => '2026-04-25 14:20:07',
                'updated_at' => '2026-04-25 14:20:24',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'booking_code' => 'INV-MEOKRXT',
                'user_id' => 3,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '11:00',
                'end_time' => '11:15',
                'payment_method' => 'qris',
                'total_price' => 135000,
                'notes' => NULL,
                'status' => 'confirmed',
                'created_at' => '2026-04-25 14:30:34',
                'updated_at' => '2026-04-25 14:50:35',
                'deleted_at' => '2026-04-25 14:50:35',
            ),
            2 => 
            array (
                'id' => 3,
                'booking_code' => 'INV-LKWE5VV',
                'user_id' => 3,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '11:15',
                'end_time' => '11:25',
                'payment_method' => 'qris',
                'total_price' => 90000,
                'notes' => 'kece badai',
                'status' => 'confirmed',
                'created_at' => '2026-04-25 14:51:21',
                'updated_at' => '2026-04-25 14:58:30',
                'deleted_at' => '2026-04-25 14:58:30',
            ),
            3 => 
            array (
                'id' => 4,
                'booking_code' => 'INV-O0KZVE9',
                'user_id' => 3,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '11:15',
                'end_time' => '11:25',
                'payment_method' => 'qris',
                'total_price' => 90000,
                'notes' => 'kece badai',
                'status' => 'confirmed',
                'created_at' => '2026-04-25 14:51:24',
                'updated_at' => '2026-04-25 14:51:37',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'booking_code' => 'INV-SFTJ13V',
                'user_id' => 3,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '14:00',
                'end_time' => '14:10',
                'payment_method' => 'qris',
                'total_price' => 90000,
                'notes' => NULL,
                'status' => 'confirmed',
                'created_at' => '2026-04-25 15:00:01',
                'updated_at' => '2026-04-25 15:00:15',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'booking_code' => 'INV-MKL1MH9',
                'user_id' => 3,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '17:40',
                'end_time' => '18:00',
                'payment_method' => 'cash',
                'total_price' => 180000,
                'notes' => NULL,
                'status' => 'confirmed',
                'created_at' => '2026-04-25 15:01:16',
                'updated_at' => '2026-04-25 15:01:16',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'booking_code' => 'INV-TVGGWG2',
                'user_id' => 1,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '19:00',
                'end_time' => '19:05',
                'payment_method' => 'cash',
                'total_price' => 45000,
                'notes' => NULL,
                'status' => 'confirmed',
                'created_at' => '2026-04-25 19:58:10',
                'updated_at' => '2026-04-25 19:58:10',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'booking_code' => 'INV-JLG3REN',
                'user_id' => 1,
                'studio_id' => 1,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '20:30',
                'end_time' => '20:50',
                'payment_method' => 'qris',
                'total_price' => 180000,
                'notes' => NULL,
                'status' => 'confirmed',
                'created_at' => '2026-04-25 19:59:14',
                'updated_at' => '2026-04-25 19:59:28',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'booking_code' => 'INV-YKZMQQ3',
                'user_id' => 1,
                'studio_id' => 2,
                'booking_date' => '2026-04-25 00:00:00',
                'start_time' => '21:00',
                'end_time' => '21:15',
                'payment_method' => 'qris',
                'total_price' => 135000,
                'notes' => 'apa aja deh',
                'status' => 'confirmed',
                'created_at' => '2026-04-25 20:59:10',
                'updated_at' => '2026-04-25 20:59:32',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'booking_code' => 'INV-RGWOYTV',
                'user_id' => 1,
                'studio_id' => 1,
                'booking_date' => '2026-05-07 00:00:00',
                'start_time' => '11:05',
                'end_time' => '11:15',
                'payment_method' => 'cash',
                'total_price' => 90000,
                'notes' => NULL,
                'status' => 'confirmed',
                'created_at' => '2026-05-07 17:55:31',
                'updated_at' => '2026-05-07 17:55:31',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}