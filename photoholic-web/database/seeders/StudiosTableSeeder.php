<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudiosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('studios')->delete();
        
        DB::table('studios')->insert(array (
            0 => 
            array (
                'id' => 1,
                'studio_code' => 'STD-001',
                'photo' => 'studios/RMyuHYhr255b7HP1bhMqUA5jzwAYrjlOdU8LChLk.jpg',
                'name' => 'Classy',
                'max_people_per_session' => 8,
                'session_duration' => 5,
                'photo_strips' => 2,
                'paper_type' => 'negative_film',
                'price' => 45000,
                'is_active' => 1,
                'created_at' => '2026-04-25 14:04:39',
                'updated_at' => '2026-04-25 14:15:32',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'studio_code' => 'STD-002',
                'photo' => 'studios/hnsUeTr4Xe4cMpoqhPcoHboJNU0Ex4lQeEOQzaI1.jpg',
                'name' => 'Spotlight',
                'max_people_per_session' => 8,
                'session_duration' => 5,
                'photo_strips' => 2,
                'paper_type' => 'photo_paper',
                'price' => 45000,
                'is_active' => 1,
                'created_at' => '2026-04-25 14:04:39',
                'updated_at' => '2026-04-25 14:15:57',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'studio_code' => 'STD-003',
                'photo' => 'studios/i2TY7AfVqrEZjZiYRte9wb2JZD2xhQu5bwc2ZKtu.jpg',
                'name' => 'Lavatory',
                'max_people_per_session' => 8,
                'session_duration' => 5,
                'photo_strips' => 2,
                'paper_type' => 'photo_paper',
                'price' => 35000,
                'is_active' => 1,
                'created_at' => '2026-04-25 14:04:39',
                'updated_at' => '2026-04-25 14:16:21',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'studio_code' => 'STD-004',
                'photo' => 'studios/cXYiO2oJdh9knq9Z45bA4Ywgtt21uJO5oOjDJYaF.jpg',
                'name' => 'Oven',
                'max_people_per_session' => 4,
                'session_duration' => 5,
                'photo_strips' => 2,
                'paper_type' => 'photo_paper',
                'price' => 35000,
                'is_active' => 1,
                'created_at' => '2026-04-25 14:04:39',
                'updated_at' => '2026-04-25 14:16:46',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'studio_code' => 'STD-005',
                'photo' => 'studios/6w323Al5KiYIIrVNq99HyOHcxo6rQU6cZgjYJQDH.png',
                'name' => 'Aquarium',
                'max_people_per_session' => 4,
                'session_duration' => 5,
                'photo_strips' => 2,
                'paper_type' => 'photo_paper',
                'price' => 35000,
                'is_active' => 1,
                'created_at' => '2026-04-25 14:04:39',
                'updated_at' => '2026-04-25 14:18:15',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}