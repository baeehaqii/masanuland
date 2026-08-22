<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

/**
 * Moves the images the site ships with into the public storage disk so the
 * upload fields at /admin show them instead of an empty dropzone.
 */
class SiteMediaSeeder extends Seeder
{
    /** Bundled file in public/images → path on the public disk. */
    private const MEDIA = [
        'images/hero-own-your-future.webp' => 'site/hero-own-your-future.webp',
        'images/hero-graha-ayodya.webp' => 'site/hero-graha-ayodya.webp',
        'images/hero-cluster-as-syifa.webp' => 'site/hero-cluster-as-syifa.webp',
        'images/about-fasad.webp' => 'site/about-fasad.webp',
    ];

    public function run(): void
    {
        File::ensureDirectoryExists(storage_path('app/public/site'));

        foreach (self::MEDIA as $source => $target) {
            $from = public_path($source);
            $to = storage_path('app/public/'.$target);

            if (File::exists($from) && ! File::exists($to)) {
                File::copy($from, $to);
            }
        }

        $site = Setting::current();

        $site->update([
            'hero_slides' => $site->hero_slides ?: [
                'site/hero-own-your-future.webp',
                'site/hero-graha-ayodya.webp',
                'site/hero-cluster-as-syifa.webp',
            ],
            'page_home' => [
                ...($site->page_home ?? []),
                'about_image' => data_get($site->page_home, 'about_image') ?: 'site/about-fasad.webp',
            ],
        ]);
    }
}
