<?php

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\MasanulandSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

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

        foreach (['page-home', 'page-about', 'page-testimonials', 'manage-menu', 'theme-settings'] as $page) {
            $this->get('/admin/'.$page)->assertOk();
        }

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
