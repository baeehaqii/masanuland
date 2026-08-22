<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'hero_badges' => 'array',
        'hero_slides' => 'array',
        'about_points' => 'array',
        'stats' => 'array',
        'socials' => 'array',
        'menu_header' => 'array',
        'menu_footer' => 'array',
        'buttons' => 'array',
        'theme' => 'array',
        'seo' => 'array',
        'og' => 'array',
        'page_home' => 'array',
        'page_about' => 'array',
        'page_project' => 'array',
        'page_testimonials' => 'array',
    ];

    /** The one and only settings row. */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    /** Upload path → public URL. Absolute URLs and rooted paths pass through. */
    public function asset(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        return preg_match('#^(https?:)?//|^/#', $path) ? $path : '/storage/'.$path;
    }

    /** The `theme` column as CSS custom properties, e.g. `--color-maroon:#7a0f1b;`. */
    public function themeCss(): string
    {
        return collect($this->theme ?? [])
            ->filter(fn ($value) => filled($value))
            ->map(fn (string $value, string $key) => '--color-'.str_replace('_', '-', $key).':'.$value.';')
            ->implode('');
    }

    public function waLink(?string $text = null): string
    {
        $number = preg_replace('/\D/', '', (string) $this->whatsapp);

        return 'https://api.whatsapp.com/send/?phone='.$number
            .'&text='.urlencode($text ?? $this->whatsapp_text ?? 'Halo, mohon informasi perumahannya.');
    }
}
