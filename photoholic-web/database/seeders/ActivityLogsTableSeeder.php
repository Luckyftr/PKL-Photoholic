<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('activity_logs')->delete();
        
        DB::table('activity_logs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => NULL,
                'activity' => 'Update Blog',
                'description' => 'Mengubah konten blog: Spesial Valentine',
                'created_at' => '2026-04-25 14:13:24',
                'updated_at' => '2026-04-25 14:13:24',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => NULL,
                'activity' => 'Update Blog',
                'description' => 'Mengubah konten blog: Costum Border',
                'created_at' => '2026-04-25 14:14:21',
                'updated_at' => '2026-04-25 14:14:21',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => NULL,
                'activity' => 'Update Blog',
                'description' => 'Mengubah konten blog: Anniversary 1th Photoholic',
                'created_at' => '2026-04-25 14:15:02',
                'updated_at' => '2026-04-25 14:15:02',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => NULL,
                'activity' => 'Update Studio',
                'description' => 'Mengubah data studio: Classy',
                'created_at' => '2026-04-25 14:15:32',
                'updated_at' => '2026-04-25 14:15:32',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => NULL,
                'activity' => 'Update Studio',
                'description' => 'Mengubah data studio: Spotlight',
                'created_at' => '2026-04-25 14:15:58',
                'updated_at' => '2026-04-25 14:15:58',
            ),
            5 => 
            array (
                'id' => 6,
                'user_id' => NULL,
                'activity' => 'Update Studio',
                'description' => 'Mengubah data studio: Lavatory',
                'created_at' => '2026-04-25 14:16:21',
                'updated_at' => '2026-04-25 14:16:21',
            ),
            6 => 
            array (
                'id' => 7,
                'user_id' => NULL,
                'activity' => 'Update Studio',
                'description' => 'Mengubah data studio: Oven',
                'created_at' => '2026-04-25 14:16:46',
                'updated_at' => '2026-04-25 14:16:46',
            ),
            7 => 
            array (
                'id' => 8,
                'user_id' => NULL,
                'activity' => 'Update Studio',
                'description' => 'Mengubah data studio: Aquarium',
                'created_at' => '2026-04-25 14:18:15',
                'updated_at' => '2026-04-25 14:18:15',
            ),
            8 => 
            array (
                'id' => 9,
                'user_id' => 3,
                'activity' => 'Update Blog',
                'description' => 'Mengubah konten blog: Costum Border',
                'created_at' => '2026-04-25 15:04:25',
                'updated_at' => '2026-04-25 15:04:25',
            ),
        ));
        
        
    }
}