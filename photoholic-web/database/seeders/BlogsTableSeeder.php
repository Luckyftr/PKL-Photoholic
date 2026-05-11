<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('blogs')->delete();
        
        DB::table('blogs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'photo' => 'blogs/xKLUyLzHKOeQBkWQDl860M9VYV1RALDyADunCMBI.jpg',
                'title' => 'Anniversary 1th Photoholic',
                'category' => 'event',
                'publish_date' => '2026-03-25 00:00:00',
                'short_caption' => 'Yang ulangtahun 25 maret sini merapat!',
                'content' => 'Rayakan anniversary pertama Photoholic bersama kami! Banyak promo dan keseruan menanti 🎉',
                'instagram_url' => 'https://www.instagram.com/p/DWS5GNrCduf/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
                'sync_insta' => 1,
                'status' => 'published',
                'created_at' => '2026-04-25 14:04:40',
                'updated_at' => '2026-04-25 14:15:01',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'photo' => 'blogs/TgutYbPSa5oeBjUIxxy6PSqNRMNnRRagSioSBLJf.jpg',
                'title' => 'Costum Border',
                'category' => 'pengumuman',
                'publish_date' => '2026-01-02 00:00:00',
                'short_caption' => 'Kalian bingung mau photobooth tapi pengen custom border diphotoholic bisaa!',
                'content' => 'Sekarang kamu bisa request custom border sesuai keinginanmu! Bikin hasil photobooth makin personal ✨',
                'instagram_url' => 'https://www.instagram.com/p/DU-Pq7TCRT5/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
                'sync_insta' => 1,
                'status' => 'published',
                'created_at' => '2026-04-25 14:04:40',
                'updated_at' => '2026-04-25 15:04:24',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'photo' => 'blogs/7N1JsweU2Q9k3Q8PvGo9vzEzrsh3UEE6JzEcf55y.jpg',
                'title' => 'Spesial Valentine',
                'category' => 'promo',
                'publish_date' => '2026-02-09 00:00:00',
                'short_caption' => 'Valentine makin terkesan bareng kesayangan',
                'content' => 'Rayakan hari Valentine dengan pengalaman photobooth yang romantis bersama pasanganmu ❤️',
                'instagram_url' => 'https://www.instagram.com/p/DUisdlFibaB/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
                'sync_insta' => 1,
                'status' => 'published',
                'created_at' => '2026-04-25 14:04:40',
                'updated_at' => '2026-04-25 14:13:24',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}