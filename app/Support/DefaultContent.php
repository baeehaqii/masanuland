<?php

namespace App\Support;

/**
 * The copy the website ships with. Written into the `settings` row by the CMS
 * migration, then edited at /admin — nothing reads this class at runtime.
 */
class DefaultContent
{
    /** @return array<string, mixed> */
    public static function all(): array
    {
        return [
            'menu_header' => [
                ['label' => 'Home', 'url' => '/', 'type' => 'link'],
                ['label' => 'Tentang Kami', 'url' => '/tentang-kami', 'type' => 'link'],
                ['label' => 'Perumahan', 'url' => '/#perumahan', 'type' => 'projects'],
                ['label' => 'Testimoni', 'url' => '/testimoni', 'type' => 'link'],
                ['label' => 'Brosur & Harga', 'url' => '', 'type' => 'brochure'],
            ],
            'menu_footer' => [
                ['label' => 'Home', 'url' => '/', 'type' => 'link'],
                ['label' => 'Tentang Kami', 'url' => '/tentang-kami', 'type' => 'link'],
                ['label' => 'Testimoni', 'url' => '/testimoni', 'type' => 'link'],
                ['label' => 'Perumahan', 'url' => '/#perumahan', 'type' => 'projects'],
            ],
            'buttons' => [
                'whatsapp_label' => 'WhatsApp',
                'whatsapp_mobile_label' => 'Hubungi via WhatsApp',
                'brochure_label' => 'Brosur & Harga',
                'detail_label' => 'Lihat Detail',
                'cta_title' => 'Info Lebih Lanjut, Klik',
                'cta_label' => null,
                'footer_menu_title' => 'Menu',
                'footer_contact_title' => 'Kontak Kami',
                'footer_social_title' => 'Ikuti Kami',
                'copyright' => null,
            ],
            'theme' => [
                'maroon' => '#7a0f1b',
                'maroon_900' => '#37050b',
                'maroon_800' => '#4f0810',
                'maroon_700' => '#650c17',
                'maroon_500' => '#a4162a',
                'maroon_400' => '#c9364b',
                'maroon_200' => '#f6ccd1',
                'maroon_100' => '#fbe4e6',
                'maroon_50' => '#fdf3f4',
                'gold' => '#c9962c',
                'gold_dark' => '#a87b1c',
                'wa' => '#25d366',
                'wa_dark' => '#1da851',
                'brick' => '#932922',
            ],
            'seo' => [
                'title' => 'Masanuland — Perumahan Purbalingga & Banyumas',
                'title_suffix' => 'Masanuland',
                'description' => 'Pengembang properti dan real estate di Purbalingga dan Banyumas. Hunian berkualitas, legalitas aman, rumah pasti dibangun.',
                'keywords' => 'perumahan purbalingga, perumahan banyumas, rumah dijual purwokerto, masanuland',
                'robots' => 'index, follow',
                'canonical' => null,
            ],
            'og' => [
                'title' => 'Masanuland — Bangunlah Rumahnya, Bangun Ceritanya',
                'description' => 'Perumahan berkualitas di Purbalingga dan Banyumas. Legalitas aman, rumah pasti dibangun, dukungan KPR.',
                'image' => null,
                'type' => 'website',
                'site_name' => 'Masanuland',
                'twitter_card' => 'summary_large_image',
            ],
            'page_home' => [
                'about_eyebrow' => 'Tentang Kami',
                'about_title' => 'Pengembang Properti Purbalingga & Banyumas',
                'about_image' => null,
                'about_link_label' => 'Selengkapnya',
                'projects_title' => 'Lokasi Unggulan',
                'projects_subtitle' => 'Pilih hunian yang paling dekat dengan aktivitas harian Anda.',
                'why_title' => 'Developer Terpercaya',
                'why_subtitle' => 'Tiga hal yang kami pegang di setiap unit yang kami serahkan.',
                'reasons' => [
                    ['icon' => 'shield', 'title' => 'Legalitas Aman', 'body' => 'Sertifikat dan perizinan jelas, proses balik nama dibantu sampai selesai.'],
                    ['icon' => 'badge', 'title' => 'Rumah Pasti Dibangun', 'body' => 'Progres pembangunan dilaporkan berkala, bukan sekadar janji brosur.'],
                    ['icon' => 'coins', 'title' => 'Dukungan KPR', 'body' => 'Bekerja sama dengan bank rekanan untuk simulasi dan pengajuan KPR.'],
                ],
                'map_title' => 'Map Lokasi',
                'show_stats' => true,
                'show_map' => true,
            ],
            'page_about' => [
                'hero_title' => 'Tentang Kami',
                'hero_eyebrow' => 'Easy Living, Grow The Future',
                'eyebrow' => 'PT. Masanu Bangun Graha',
                'title' => 'Properti yang Bernilai Melintasi Waktu',
                'name_title' => 'Arti Nama MASANU',
                'name_parts' => [
                    ['word' => 'MASA', 'origin' => 'Sanskerta & Jawa Kuno', 'meaning' => 'Waktu, era, periode.', 'note' => 'Ketahanan melintasi waktu dan visi jangka panjang di setiap kawasan hunian.'],
                    ['word' => 'ANUGRAHA', 'origin' => 'Jawa Kuno & Sanskerta', 'meaning' => 'Keberkahan, pemberian berharga.', 'note' => 'Anugerah dan ruang kehidupan yang membawa kebaikan, rasa aman, dan kedamaian.'],
                    ['word' => 'ADANU', 'origin' => 'Nama Jawa & Sanskerta', 'meaning' => 'Cahaya, penerang bagi yang lain.', 'note' => 'Properti yang menjadi penggerak pusat pertumbuhan ekonomi lingkungan sekitarnya.'],
                ],
                'name_conclusion' => 'MASANU bermakna “Era Keberkahan dan Kebaikan yang Melintasi Waktu”.',
                'visi_title' => 'Visi & Misi',
                'visi' => 'Menjadi pengembang properti terpercaya yang menghadirkan hunian berkualitas, bernilai, dan memberikan keberkahan serta pertumbuhan berkelanjutan bagi masyarakat dan lingkungan.',
                'misi_title' => 'Misi Perusahaan',
                'misi' => [
                    ['title' => 'Menghadirkan Hunian & Area Komersial Berkualitas', 'body' => 'Pembangunan berstandar mutu tinggi dengan struktur yang kokoh, fungsional, dan desain melintasi waktu.'],
                    ['title' => 'Membangun Lingkungan Kehidupan yang Aman dan Harmonis', 'body' => 'Menciptakan kawasan perumahan yang asri, nyaman, dan ramah keluarga sehingga menjadi tempat berteduh penuh kedamaian dan kebaikan.'],
                    ['title' => 'Mengedepankan Integritas dan Kepuasan Pelanggan', 'body' => 'Menjalankan operasional bisnis secara transparan, menjaga legalitas, serta memberikan layanan terbaik tepat waktu mulai dari akad hingga purna jual.'],
                    ['title' => 'Memberikan Nilai Tambah Investasi yang Berkelanjutan', 'body' => 'Memastikan setiap produk properti yang dikembangkan memiliki potensi kenaikan nilai investasi (capital gain) yang kuat bagi pemilik dan investor.'],
                    ['title' => 'Mendorong Pertumbuhan Ekonomi Kawasan dan Pemberdayaan Lokal', 'body' => 'Bersinergi dengan mitra lokal, perbankan, serta masyarakat sekitar untuk memberikan dampak positif bagi perekonomian daerah setempat.'],
                ],
                'budaya_title' => 'Budaya Kerja',
                'budaya_subtitle' => 'Nilai-nilai nama M-A-S-A-N-U menjadi akronim budaya kerja sehari-hari bagi seluruh jajaran pimpinan dan staf.',
                'budaya' => [
                    ['letter' => 'M', 'title' => 'Mutu Berkelanjutan', 'english' => 'Quality & Excellence', 'body' => 'Memprioritaskan kualitas material, presisi perencanaan arsitektur, dan kerapian pengerjaan di setiap detail unit.'],
                    ['letter' => 'A', 'title' => 'Adaptif & Inovatif', 'english' => 'Adaptability', 'body' => 'Tanggap terhadap perkembangan teknologi properti, tren desain masa kini, dan dinamika kebutuhan konsumen.'],
                    ['letter' => 'S', 'title' => 'Sinergis & Integratif', 'english' => 'Synergy', 'body' => 'Membangun kerja sama yang harmonis antara manajemen internal, perbankan, vendor, pemerintah lokal, dan masyarakat konsumen.'],
                    ['letter' => 'A', 'title' => 'Amanah & Transparan', 'english' => 'Integrity & Trust', 'body' => 'Menjaga komitmen legalitas lahan, kejelasan skema pembiayaan, transparansi spesifikasi teknis, serta ketepatan waktu serah terima (BAST).'],
                    ['letter' => 'N', 'title' => 'Nilai Tambah', 'english' => 'Value Creation', 'body' => 'Selalu memberikan nilai ekstra pada setiap kawasan yang dibangun, baik dari sisi tata hijau, kepraktisan aksesibilitas, maupun fasilitas pendukung.'],
                    ['letter' => 'U', 'title' => 'Utamakan Kepuasan Konsumen', 'english' => 'Customer First', 'body' => 'Menempatkan kebutuhan dan kepuasan pelanggan sebagai muara utama pelayanan, mulai dari konsultasi awal hingga masa purna jual (after-sales service).'],
                ],
                'stats_title' => 'Angka Kami',
            ],
            'page_testimonials' => [
                'hero_title' => 'Testimoni',
                'hero_subtitle' => 'Cerita penghuni yang sudah menempati hunian kami.',
                'empty_text' => 'Testimoni akan segera ditampilkan.',
            ],
        ];
    }
}
