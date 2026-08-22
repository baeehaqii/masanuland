<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Judul & deskripsi per halaman untuk mesin pencari.
 *
 * Halaman dirender React, jadi <title> milik Inertia baru muncul setelah JS
 * jalan. Crawler membaca HTML mentah lebih dulu — nilai di sini yang dicetak
 * server, sehingga tiap URL punya judul dan deskripsi sendiri tanpa SSR.
 *
 * @phpstan-type Meta array{title: string, description: ?string, image: ?string}
 */
class PageSeo
{
    /**
     * @param  array<string, mixed>  $page  Payload Inertia dari root view.
     * @return array{title: string, description: ?string, image: ?string}
     */
    public static function for(Setting $site, array $page): array
    {
        $suffix = data_get($site->seo, 'title_suffix') ?: $site->brand_name;
        $props = data_get($page, 'props', []);
        $image = $site->asset(data_get($site->og, 'image'));

        return match (data_get($page, 'component')) {
            'about' => [
                'title' => (data_get($site->page_about, 'hero_title') ?: 'Tentang Kami').' — '.$suffix,
                'description' => $site->about_text ?: $site->tagline,
                'image' => $image,
            ],
            'testimonials' => [
                'title' => (data_get($site->page_testimonials, 'hero_title') ?: 'Testimoni').' — '.$suffix,
                'description' => data_get($site->page_testimonials, 'hero_subtitle') ?: $site->tagline,
                'image' => $image,
            ],
            'project' => [
                'title' => data_get($props, 'project.name').' — '.$suffix,
                'description' => data_get($props, 'project.tagline')
                    ?: trim((string) data_get($props, 'project.location').' — '.$site->tagline, ' —'),
                'image' => $site->asset(data_get($props, 'project.card_image') ?? data_get($props, 'project.hero_image')) ?? $image,
            ],
            default => [
                'title' => data_get($site->seo, 'title') ?: $site->brand_name,
                'description' => data_get($site->seo, 'description') ?: $site->tagline,
                'image' => $image,
            ],
        };
    }

    /** Keywords boleh berupa array (TagsInput) atau string lama dipisah koma. */
    public static function keywords(Setting $site): ?string
    {
        $keywords = data_get($site->seo, 'keywords');

        return filled($keywords)
            ? implode(', ', array_filter((array) $keywords))
            : null;
    }
}
