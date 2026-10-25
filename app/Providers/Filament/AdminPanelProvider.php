<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Shortlinks\Pages\CreateShortlink;
use App\Filament\Resources\Shortlinks\Pages\EditShortlink;
use App\Filament\Resources\Shortlinks\Pages\ListShortlinks;
use App\Support\UIcons;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Vite;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Masanuland CMS')
            ->colors([
                // Explicit ramp: Color::hex() anchors the brand at shade 500 and
                // washes the button shades out to pink.
                'primary' => [
                    50 => '#fdf3f4',
                    100 => '#fae3e6',
                    200 => '#f4c6cc',
                    300 => '#e89aa5',
                    400 => '#d76476',
                    500 => '#b93a4e',
                    600 => '#97182c',
                    700 => '#7a0f1b',
                    800 => '#650c17',
                    900 => '#4f0810',
                    950 => '#37050b',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                'Halaman',
                'Proyek',
                'Master Data',
                'Pengaturan',
            ])
            // Font ikon Flaticon hanya untuk halaman Shortlink, supaya panel lain tetap ringan.
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => app(Vite::class)(UIcons::entries()),
                scopes: [ListShortlinks::class, CreateShortlink::class, EditShortlink::class],
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Pengaturan')
                    ->navigationLabel('Peran & Akses')
                    ->navigationIcon(Heroicon::OutlinedShieldCheck)
                    ->navigationSort(4)
                    ->modelLabel('Peran')
                    ->pluralModelLabel('Peran'),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
