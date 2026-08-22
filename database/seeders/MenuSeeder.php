<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Support\DefaultContent;
use Illuminate\Database\Seeder;

/**
 * Menu navigasi & footer sesuai susunan di website.
 *
 * Menimpa isi menu yang ada — jalankan `php artisan db:seed --class=MenuSeeder`
 * kalau menu di panel perlu dikembalikan ke bawaan.
 */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = DefaultContent::all();

        Setting::current()->update([
            'menu_header' => $defaults['menu_header'],
            'menu_footer' => $defaults['menu_footer'],
        ]);
    }
}
