<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Str;

/**
 * Judul & deskripsi per halaman untuk mesin pencari.
 *
 * Halaman dirender React, jadi <title> milik Inertia baru muncul setelah JS
 * jalan. Crawler membaca HTML mentah lebih dulu — nilai di sini yang dicetak
 * server, sehingga tiap URL punya judul dan deskripsi sendiri tanpa SSR.
 *
 * og:title/og:description ikut halamannya; isian "SEO & OG" di CMS hanya
 * jadi bawaan untuk beranda, supaya link perumahan yang dibagikan di WhatsApp
 * menampilkan nama dan deskripsi perumahannya sendiri.
 */
class PageSeo
{
    /**
     * @param  array<string, mixed>  $page  Payload Inertia dari root view.
     * @return array{title: string, description: ?string, image: ?string, og_title: string, og_description: ?string}
     */
    public static function for(Setting $site, array $page): array
    {
        $meta = self::meta($site, $page);
        $component = data_get($page, 'component');

        return $meta + match ($component) {
            'project' => [
                'og_title' => (string) data_get($page, 'props.project.name'),
                'og_description' => $meta['description'],
            ],
            'about', 'testimonials', 'shortlink' => [
                'og_title' => $meta['title'],
                'og_description' => $meta['description'],
            ],
            default => [
                'og_title' => data_get($site->og, 'title') ?: $meta['title'],
                'og_description' => data_get($site->og, 'description') ?: $meta['description'],
            ],
        };
    }

    /**
     * @param  array<string, mixed>  $page
     * @return array{title: string, description: ?string, image: ?string}
     */
    private static function meta(Setting $site, array $page): array
    {
        $suffix = data_get($site->seo, 'title_suffix') ?: $site->brand_name;
        $props = data_get($page, 'props', []);
        $image = $site->asset(CompressedImage::shareable(data_get($site->og, 'image')));

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
                // Potongan deskripsi perumahan; tanpa deskripsi pakai tagline / lokasinya.
                'description' => Str::limit(trim(html_entity_decode(strip_tags((string) data_get($props, 'project.description')))), 155) ?: (
                    data_get($props, 'project.tagline')
                    ?: trim((string) data_get($props, 'project.location').' — '.$site->tagline, ' —')
                ),
                'image' => $site->asset(CompressedImage::shareable(data_get($props, 'project.card_image') ?? data_get($props, 'project.hero_image'))) ?? $image,
            ],
            'shortlink' => [
                'title' => data_get($props, 'shortlink.title').' — '.$suffix,
                'description' => data_get($props, 'shortlink.subtitle') ?: $site->tagline,
                'image' => $site->asset(CompressedImage::shareable(data_get($props, 'shortlink.logo'))) ?? $image,
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
