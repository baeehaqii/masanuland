<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

/**
 * Flaticon UIcons (npm @flaticon/flaticon-uicons), ±28.000 ikon dalam 9 gaya.
 * Ikon disimpan sebagai class lengkapnya, mis. "fi-rr-home" atau "fi-brands-instagram".
 *
 * CSS tiap gaya jadi entry Vite sendiri (vite.config.ts), jadi halaman publik
 * hanya memuat gaya yang benar-benar dipakai. Lisensinya mewajibkan kredit ke Flaticon.
 */
class UIcons
{
    public const DIR = 'node_modules/@flaticon/flaticon-uicons/css/';

    /** prefix => [file CSS, nama gaya] */
    public const STYLES = [
        'fi-rr' => ['regular/rounded.css', 'Regular Rounded'],
        'fi-rs' => ['regular/straight.css', 'Regular Straight'],
        'fi-br' => ['bold/rounded.css', 'Bold Rounded'],
        'fi-bs' => ['bold/straight.css', 'Bold Straight'],
        'fi-sr' => ['solid/rounded.css', 'Solid Rounded'],
        'fi-ss' => ['solid/straight.css', 'Solid Straight'],
        'fi-tr' => ['thin/rounded.css', 'Thin Rounded'],
        'fi-ts' => ['thin/straight.css', 'Thin Straight'],
        'fi-brands' => ['brands/all.css', 'Brand'],
    ];

    /** @return array<int, string> semua entry CSS, untuk input Vite & panel admin */
    public static function entries(): array
    {
        return array_map(fn (array $style) => self::DIR.$style[0], array_values(self::STYLES));
    }

    /**
     * Entry CSS untuk gaya-gaya yang dipakai sekumpulan ikon.
     *
     * @param  iterable<?string>  $icons
     * @return array<int, string>
     */
    public static function entriesFor(iterable $icons): array
    {
        return collect($icons)
            ->map(fn (?string $icon) => self::STYLES[self::prefix((string) $icon)][0] ?? null)
            ->filter()->unique()
            ->map(fn (string $file) => self::DIR.$file)
            ->values()->all();
    }

    /**
     * Cari ikon untuk Select di panel: nama ikon cocok, gaya Regular Rounded & Brand duluan.
     *
     * @return array<string, string> class => label HTML
     */
    public static function search(string $query, int $limit = 60): array
    {
        $words = array_filter(explode(' ', Str::slug($query, ' ')));

        return collect(self::all())
            ->filter(fn (string $icon) => collect($words)->every(fn (string $word) => str_contains($icon, $word)))
            ->sortBy(fn (string $icon) => [
                array_search(self::prefix($icon), ['fi-rr', 'fi-brands', 'fi-sr', 'fi-br', 'fi-rs', 'fi-ss', 'fi-bs', 'fi-tr', 'fi-ts'], true),
                strlen($icon),
            ])
            ->take($limit)
            ->mapWithKeys(fn (string $icon) => [$icon => self::label($icon)])
            ->all();
    }

    public static function label(?string $icon): string
    {
        if (! $icon || ! isset(self::STYLES[self::prefix($icon)])) {
            return e((string) $icon);
        }

        $prefix = self::prefix($icon);
        $name = str_replace('-', ' ', Str::after($icon, $prefix.'-'));

        return '<span style="display:inline-flex;align-items:center;gap:.6rem">'
            .'<i class="'.e($icon).'" style="font-size:1.25rem;line-height:1;display:inline-flex"></i>'
            .'<span>'.e($name).' <small style="opacity:.55">· '.self::STYLES[$prefix][1].'</small></span>'
            .'</span>';
    }

    /** @return array<int, string> semua class ikon, di-cache per build Vite */
    public static function all(): array
    {
        return Cache::rememberForever('uicons:'.md5((string) Vite::asset(self::entries()[0])), function () {
            $icons = [];

            foreach (self::entries() as $entry) {
                preg_match_all('/\.(fi-[a-z]+-[a-z0-9-]+):before/', (string) file_get_contents(self::path($entry)), $matches);
                array_push($icons, ...$matches[1]);
            }

            return array_values(array_unique($icons));
        });
    }

    private static function prefix(string $icon): string
    {
        return str_starts_with($icon, 'fi-brands-') ? 'fi-brands' : substr($icon, 0, 5);
    }

    /** Lokal: dari node_modules. Server produksi tanpa node_modules: dari hasil build. */
    private static function path(string $entry): string
    {
        return is_file(base_path($entry))
            ? base_path($entry)
            : public_path((string) parse_url(Vite::asset($entry), PHP_URL_PATH));
    }
}
