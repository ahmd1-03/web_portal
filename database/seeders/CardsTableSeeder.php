<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cards')->truncate();

        DB::table('cards')->insert([
            [
                'image_url' => '/storage/images/placeholder.png',
                'title' => 'Dinas Pendidikan Karawang',
                'description' => 'Informasi lengkap tentang pendidikan di Karawang.',
                'external_link' => 'https://pendidikan.karawang.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image_url' => '/storage/images/placeholder.png',
                'title' => 'Dinas Kesehatan Karawang',
                'description' => 'Berita dan layanan kesehatan di Karawang.',
                'external_link' => 'https://kesehatan.karawang.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'image_url' => '/storage/images/placeholder.png',
                'title' => 'Pariwisata Karawang',
                'description' => 'Tempat wisata menarik di Karawang.',
                'external_link' => 'https://pariwisata.karawang.go.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
