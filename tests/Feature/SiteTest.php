<?php

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MasanulandSeeder;
use Database\Seeders\SeoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Produksi hanya menjalankan PHP-FPM, jadi head selalu datang dari Blade.
        // Tanpa ini, gateway SSR lokal ikut menjawab dan menutupi tag yang diuji.
        config(['inertia.ssr.enabled' => false]);

        $this->seed(MasanulandSeeder::class);
    }

    public function test_public_pages_render(): void
    {
        $this->get('/')->assertOk();
        $this->get('/tentang-kami')->assertOk();
        $this->get('/testimoni')->assertOk();

        foreach (Project::published()->pluck('slug') as $slug) {
            $this->get("/perumahan/{$slug}")->assertOk();
        }
    }

    public function test_unpublished_projects_are_hidden_from_the_home_page(): void
    {
        $project = Project::first();
        $project->update(['is_published' => false]);

        $this->get('/')->assertOk()->assertDontSee($project->name);
    }

    public function test_admin_panel_pages_load(): void
    {
        $this->actingAs($this->superAdmin());

        $this->get('/admin')->assertOk();
        $this->get('/admin/projects')->assertOk();
        $this->get('/admin/projects/'.Project::first()->getKey().'/edit')
            ->assertOk()
            ->assertSee('Nama Perumahan')
            ->assertSee('Masanu Village Sokaraja');
        $this->get('/admin/testimonials')->assertOk();
        $this->get('/admin/users')->assertOk();
        $this->get('/admin/shield/roles')->assertOk();

        foreach (['page-home', 'page-about', 'page-project', 'page-testimonials', 'manage-menu', 'theme-settings'] as $page) {
            $this->get('/admin/'.$page)->assertOk();
        }

        // Semua section di website punya field-nya di panel.
        $this->get('/admin/page-home')->assertOk()
            ->assertSee('Statistik')->assertSee('Poin Keunggulan')->assertSee('Deskripsi');
        $this->get('/admin/page-project')->assertOk()
            ->assertSee('Fasilitas')->assertSee('Siteplan');

        $this->get('/admin/master-identity')->assertOk()->assertSee('Nama Brand');
        $this->get('/admin/master-contact')->assertOk()->assertSee('Nomor WhatsApp');
        $this->get('/admin/master-buttons')->assertOk()->assertSee('Tombol Brosur');
        $this->get('/admin/seo-settings')->assertOk()->assertSee('Meta Description');
    }

    public function test_panel_is_closed_to_users_without_a_role(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin')->assertForbidden();
    }

    public function test_cms_drives_the_site_head_and_menu(): void
    {
        Setting::current()->update([
            'theme' => ['maroon' => '#123456', 'gold' => ''],
            'seo' => ['description' => 'Deskripsi uji coba'],
            'menu_header' => [['label' => 'Menu Uji', 'url' => '/tentang-kami', 'type' => 'link']],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('--color-maroon:#123456;', escape: false)
            ->assertDontSee('--color-gold:', escape: false)
            ->assertSee('Deskripsi uji coba', escape: false)
            ->assertSee('Menu Uji', escape: false);
    }

    public function test_public_visits_are_counted_but_admin_visits_are_not(): void
    {
        $this->get('/')->assertOk();
        $this->actingAs($this->superAdmin())->get('/admin')->assertOk();

        $this->assertSame(1, PageView::count());
        $this->assertSame('/', PageView::first()->path);
    }

    public function test_setiap_halaman_punya_judul_dan_meta_sendiri_untuk_crawler(): void
    {
        $this->seed(SeoSeeder::class);

        $project = Project::published()->first();

        $this->get('/')
            ->assertOk()
            // Tanpa tag <title> penuh: kalau SSR aktif, tag itu dirender React.
            ->assertSee('Masanuland — Perumahan Banyumas', escape: false)
            ->assertSee('name="robots" content="index, follow"', escape: false)
            ->assertSee('rel="canonical"', escape: false)
            ->assertSee('perumahan banyumas', escape: false);

        // Judul & og:image per perumahan dicetak server, bukan menunggu React.
        $this->get('/perumahan/'.$project->slug)
            ->assertOk()
            ->assertSee($project->name.' — Masanuland', escape: false)
            ->assertSee('property="og:image"', escape: false);

        $this->get('/tentang-kami')->assertOk()->assertSee('Tentang Kami — Masanuland', escape: false);
    }

    public function test_sitemap_memuat_semua_halaman_dan_perumahan_tayang(): void
    {
        $hidden = Project::published()->first();
        $hidden->update(['is_published' => false]);

        $response = $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml');

        $response->assertSee(url('/tentang-kami'), escape: false)
            ->assertSee(url('/testimoni'), escape: false)
            ->assertDontSee(url('/perumahan/'.$hidden->slug), escape: false);

        foreach (Project::published()->pluck('slug') as $slug) {
            $response->assertSee(url('/perumahan/'.$slug), escape: false);
        }
    }

    public function test_keywords_lama_berupa_string_tetap_tercetak(): void
    {
        Setting::current()->update(['seo' => ['keywords' => 'rumah murah, kpr']]);

        $this->get('/')->assertOk()->assertSee('name="keywords" content="rumah murah, kpr"', escape: false);
    }

    public function test_seeder_ulang_tidak_menimpa_konten_yang_sudah_diedit(): void
    {
        $project = Project::first();
        $project->update([
            'card_image' => 'projects/foto-asli.jpg',
            'price_from' => 166_000_000,
            'tagline' => 'Tagline hasil editan',
        ]);

        Setting::current()->update(['brand_name' => 'Nama Baru', 'tagline' => 'Tagline situs']);

        // Persis yang dijalankan orang di server saat menambah data awal.
        $this->seed(DatabaseSeeder::class);

        $project->refresh();
        $this->assertSame('projects/foto-asli.jpg', $project->card_image);
        $this->assertSame(166_000_000, $project->price_from);
        $this->assertSame('Tagline hasil editan', $project->tagline);
        $this->assertSame('Nama Baru', Setting::current()->brand_name);
        $this->assertSame('Tagline situs', Setting::current()->tagline);

        // Tetap tidak menggandakan tipe rumah.
        $this->assertSame(2, $project->houseTypes()->count());
    }

    private function superAdmin(): User
    {
        return User::factory()
            ->create()
            ->assignRole(Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']));
    }

    public function test_whatsapp_link_strips_formatting_from_the_number(): void
    {
        $site = Setting::current();
        $site->update(['whatsapp' => '+62 812-3456-7890']);

        $this->assertStringContainsString('phone=628123456789', $site->waLink('hai'));
    }
}
