<?php

namespace App\Providers;

use App\Models\Setting;
use App\Support\CompressedImage;
use App\Support\PageSeo;
use App\Support\SvgIcon;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Carbon\CarbonImmutable;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Semua gambar yang diunggah lewat panel disimpan terkompres (AVIF, maks 1920 px),
        // dan SVG berisi script ditolak. Filament meng-evaluate closure di rules(),
        // jadi rule-nya dibungkus satu lapis.
        FileUpload::configureUsing(fn (FileUpload $upload) => $upload
            ->saveUploadedFileUsing(CompressedImage::store(...))
            ->rules([fn (): Closure => SvgIcon::safe()]));
        RichEditor::configureUsing(fn (RichEditor $editor) => $editor->saveUploadedFileAttachmentUsing(CompressedImage::attachment(...)));

        // Registers Shield's generated policies, including the one for its own Role model.
        FilamentShield::enforcePolicies();

        // The Inertia root template renders meta tags and theme colours from the CMS.
        View::composer('app', function ($view) {
            $site = Setting::current();

            $view->with([
                'site' => $site,
                'themeCss' => $site->themeCss(),
                'seo' => PageSeo::for($site, $view->getData()['page'] ?? []),
                'keywords' => PageSeo::keywords($site),
            ]);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
