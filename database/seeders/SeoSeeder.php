<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Setting;
use App\Support\DefaultContent;
use Illuminate\Database\Seeder;

/**
 * SEO & Open Graph bawaan, dirakit dari isi website sendiri: nama brand,
 * tagline, dan daftar perumahan yang tayang.
 *
 * Menimpa isi SEO yang ada — jalankan `php artisan db:seed --class=SeoSeeder`
 * kalau pengaturan SEO perlu dikembalikan ke bawaan.
 */
class SeoSeeder extends Seeder
{
    public function run(): void
    {
        $site = Setting::current();
        $defaults = DefaultContent::all();

        $title = $site->brand_name.' — Perumahan '
            .Project::published()->pluck('location')->filter()
                // Ambil bagian terakhir: "Wiradadi, Sokaraja, Banyumas" → "Banyumas".
                ->map(fn (string $location) => trim((string) collect(explode(',', $location))->last()))
                ->unique()->take(2)->join(' & ');

        // Nama dan lokasi perumahan adalah kata kunci yang benar-benar dicari orang.
        $keywords = collect(DefaultContent::seoKeywords())
            ->merge(Project::published()->get()->flatMap(fn (Project $project) => [
                $project->name,
                $project->location ? 'perumahan '.$project->location : null,
            ]))
            ->filter()
            ->map(fn (string $keyword) => mb_strtolower(trim($keyword)))
            ->unique()->values()->all();

        $description = $site->tagline ?: $defaults['seo']['description'];

        $site->update([
            'seo' => [
                ...$defaults['seo'],
                'title' => rtrim($title, ' —'),
                'title_suffix' => $site->brand_name,
                'description' => $description,
                'keywords' => $keywords,
                'robots' => 'index, follow',
            ],
            'og' => [
                ...$defaults['og'],
                'title' => $site->brand_name,
                'site_name' => $site->brand_name,
                'description' => $description,
                'image' => data_get($site->og, 'image'),
            ],
        ]);
    }
}
