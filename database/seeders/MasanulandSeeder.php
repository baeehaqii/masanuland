<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Starter content. Every value here is editable at /admin — the numbers,
 * prices and distances are placeholders until the real data is entered.
 */
class MasanulandSeeder extends Seeder
{
    public function run(): void
    {
        Setting::current()->update([
            'brand_name' => 'Masanuland',
            'hero_title' => 'Bangunlah Rumahnya',
            'hero_subtitle' => 'Bangun Ceritanya',
            'hero_badges' => ['Gratis Pajak Pembeli', 'Gratis Biaya Balik Nama', 'Rumah Pasti Dibangun'],
            'hero_note' => '*S&K Berlaku',
            'tagline' => 'Pengembang properti dan real estate di Purbalingga dan Banyumas. Membangun hunian berkualitas yang aman, nyaman, dan bernilai jangka panjang.',
            'about_text' => 'PT. Masanu Bangun Graha mengembangkan hunian dan area komersial di Purbalingga dan Banyumas. Nama MASANU bermakna era keberkahan dan kebaikan yang melintasi waktu: rumah yang kami serahkan bukan sekadar fisik bangunan, melainkan investasi bernilai abadi dan tempat berteduh bagi keluarga pemiliknya.',
            'about_points' => [
                'Hunian & Area Komersial Berkualitas',
                'Lingkungan Aman dan Harmonis',
                'Legalitas dan Akad Transparan',
                'Nilai Investasi Berkelanjutan',
            ],
            'stats' => [
                ['value' => '500+', 'label' => 'Rumah Terjual'],
                ['value' => '10+', 'label' => 'Lokasi'],
                ['value' => '5+', 'label' => 'Partner Bank'],
                ['value' => '2021', 'label' => 'Berdiri Sejak'],
            ],
            'phone' => '0812-0000-0000',
            'whatsapp' => '628120000000',
            'whatsapp_text' => 'Halo Masanuland, mohon informasi perumahannya. Saya dapat informasi dari WEBSITE.',
            'email' => 'info@masanuland.id',
            'address' => 'Sokaraja, Kabupaten Banyumas, Jawa Tengah',
            'socials' => [
                ['label' => 'Instagram', 'url' => 'https://instagram.com/masanuland.id'],
                ['label' => 'Facebook', 'url' => 'https://facebook.com/masanuland.id'],
                ['label' => 'TikTok', 'url' => 'https://tiktok.com/@masanuland.id'],
                ['label' => 'YouTube', 'url' => 'https://youtube.com/@masanuland'],
            ],
        ]);

        $projects = [
            [
                'name' => 'Masanu Village Sokaraja',
                'slug' => 'masanu-village-sokaraja',
                'tagline' => '5 Menit ke Pasar Sokaraja',
                'location' => 'Sokaraja, Banyumas',
                'price_from' => 450000000,
                'price_before' => 485000000,
                'price_note' => '*Promo khusus 10 pembeli pertama',
                'badges' => ['120 Unit TERBESAR', '5 Menit ke Pasar Sokaraja'],
                'distances' => [
                    ['minutes' => 5, 'place' => 'Pasar Sokaraja'],
                    ['minutes' => 10, 'place' => 'Alun-Alun Purwokerto'],
                    ['minutes' => 12, 'place' => 'UNSOED Purwokerto'],
                    ['minutes' => 8, 'place' => 'RSUD Banyumas'],
                ],
                'features' => ['120 Unit', 'One Gate System', 'Bebas Banjir', 'Lebar Jalan 8 Meter', 'Keamanan 24 Jam', 'Lokasi Strategis'],
                'card_image' => 'projects/masanu-village-1.webp',
                'hero_image' => 'projects/masanu-village-1.webp',
                'sort' => 1,
                'house_types' => [
                    [
                        'name' => 'T-45/72',
                        'price_label' => 'Rp 450.000.000 ,-',
                        'specs' => [
                            ['count' => 2, 'label' => 'Kamar Tidur'],
                            ['count' => 1, 'label' => 'Kamar Mandi'],
                            ['count' => 1, 'label' => 'Ruang Tamu'],
                            ['count' => 1, 'label' => 'Dapur'],
                            ['count' => 1, 'label' => 'Carport'],
                        ],
                    ],
                    [
                        'name' => 'T-60/90',
                        'price_label' => 'Rp 620.000.000 ,-',
                        'specs' => [
                            ['count' => 3, 'label' => 'Kamar Tidur'],
                            ['count' => 2, 'label' => 'Kamar Mandi'],
                            ['count' => 1, 'label' => 'Ruang Tamu'],
                            ['count' => 1, 'label' => 'Ruang Makan'],
                            ['count' => 1, 'label' => 'Carport'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Cluster Asyifa',
                'slug' => 'cluster-asyifa',
                'tagline' => 'Hunian Cluster Eksklusif',
                'location' => 'Sokaraja, Banyumas',
                'price_from' => 395000000,
                'badges' => ['Cluster Eksklusif', 'Dekat Fasilitas Kesehatan'],
                'distances' => [
                    ['minutes' => 6, 'place' => 'RS Ananda Purwokerto'],
                    ['minutes' => 10, 'place' => 'Terminal Bulupitu'],
                    ['minutes' => 12, 'place' => 'Stasiun Purwokerto'],
                    ['minutes' => 4, 'place' => 'Pasar Sokaraja'],
                ],
                'features' => ['48 Unit', 'One Gate System', 'Bebas Banjir', 'Masjid dalam Cluster', 'Keamanan 24 Jam'],
                'card_image' => 'projects/as-syifa-1.webp',
                'hero_image' => 'projects/as-syifa-1.webp',
                'sort' => 2,
                'house_types' => [
                    [
                        'name' => 'T-36/60',
                        'price_label' => 'Rp 395.000.000 ,-',
                        'specs' => [
                            ['count' => 2, 'label' => 'Kamar Tidur'],
                            ['count' => 1, 'label' => 'Kamar Mandi'],
                            ['count' => 1, 'label' => 'Ruang Tamu'],
                            ['count' => 1, 'label' => 'Dapur'],
                            ['count' => 1, 'label' => 'Carport'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Graha Ayodhya Wiradadi',
                'slug' => 'graha-ayodhya-wiradadi',
                'tagline' => '3 Menit ke Jalan Raya Wiradadi',
                'location' => 'Wiradadi, Sokaraja, Banyumas',
                'price_from' => 520000000,
                'badges' => ['Premium 2 Lantai', '3 Menit ke Jalan Raya'],
                'distances' => [
                    ['minutes' => 3, 'place' => 'Jalan Raya Wiradadi'],
                    ['minutes' => 9, 'place' => 'GOR Satria Purwokerto'],
                    ['minutes' => 11, 'place' => 'Alun-Alun Purwokerto'],
                    ['minutes' => 7, 'place' => 'Pasar Sokaraja'],
                ],
                'features' => ['64 Unit', 'Pilihan 2 Lantai', 'One Gate System', 'Bebas Banjir', 'Lebar Jalan 7 Meter', 'Keamanan 24 Jam'],
                'card_image' => 'projects/ayodya-3.webp',
                'hero_image' => 'projects/ayodya-1.webp',
                'gallery' => ['projects/ayodya-1.webp', 'projects/ayodya-2.webp', 'projects/ayodya-3.webp'],
                'sort' => 3,
                'house_types' => [
                    [
                        'name' => 'T-50/80',
                        'price_label' => 'Rp 520.000.000 ,-',
                        'specs' => [
                            ['count' => 2, 'label' => 'Kamar Tidur'],
                            ['count' => 1, 'label' => 'Kamar Mandi'],
                            ['count' => 1, 'label' => 'Ruang Tamu'],
                            ['count' => 1, 'label' => 'Dapur'],
                            ['count' => 1, 'label' => 'Carport'],
                        ],
                    ],
                    [
                        'name' => '2 Lantai / 90',
                        'price_label' => 'Hubungi CS',
                        'specs' => [
                            ['count' => 3, 'label' => 'Kamar Tidur'],
                            ['count' => 2, 'label' => 'Kamar Mandi'],
                            ['count' => 1, 'label' => 'Ruang Tamu'],
                            ['count' => 1, 'label' => 'Ruang Makan'],
                            ['count' => 1, 'label' => 'Carport'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($projects as $data) {
            $types = $data['house_types'];
            unset($data['house_types']);

            $project = Project::updateOrCreate(['slug' => $data['slug']], $data);
            $project->houseTypes()->delete();

            foreach ($types as $i => $type) {
                $project->houseTypes()->updateOrCreate(['name' => $type['name']], $type + ['sort' => $i]);
            }
        }
    }
}
