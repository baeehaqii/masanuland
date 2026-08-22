<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Isi awal instalasi. Aman dijalankan ulang di server: setiap seeder di
     * sini hanya mengisi yang masih kosong.
     *
     * MenuSeeder dan SeoSeeder sengaja TIDAK dipanggil — keduanya menimpa isi
     * menu/SEO ke bawaan, jadi dipanggil manual saja saat memang mau reset.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        $this->call(MasanulandSeeder::class);
        $this->call(SiteMediaSeeder::class);
    }
}
