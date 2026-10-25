<?php

namespace App\Support;

use Closure;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;

/** Upload ikon yang hanya menerima SVG — dipakai fasilitas perumahan & kartu alasan beranda. */
class SvgIcon
{
    public static function upload(string $name = 'icon'): FileUpload
    {
        return FileUpload::make($name)->label('Ikon (SVG)')
            ->helperText('Hanya file .svg')
            ->acceptedFileTypes(['image/svg+xml'])
            // Filament meng-evaluate closure di rules(), jadi rule-nya dibungkus satu lapis.
            ->rules([fn (): Closure => static::safe()])
            ->directory('icons');
    }

    /**
     * SVG bisa membawa JavaScript yang jalan kalau file-nya dibuka langsung
     * dari /storage, jadi tolak yang berisi script, event handler, atau link javascript:.
     */
    public static function safe(): Closure
    {
        return static function (string $attribute, mixed $value, Closure $fail): void {
            if (! $value instanceof UploadedFile) {
                return;
            }

            if (preg_match('/<script|\son\w+\s*=|javascript:|<foreignObject/i', (string) file_get_contents($value->getRealPath()))) {
                $fail('SVG mengandung script dan tidak boleh diunggah.');
            }
        };
    }
}
