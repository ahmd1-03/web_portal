<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('pages')->insert([
            [
                'slug' => 'kebijakan-privasi',
                'title' => 'Kebijakan Privasi',
                'content' => '<p>Ini adalah halaman Kebijakan Privasi yang diambil dari database.</p>',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'slug' => 'syarat-ketentuan',
                'title' => 'Syarat & Ketentuan',
                'content' => '<p>Ini adalah halaman Syarat & Ketentuan yang diambil dari database.</p>',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
