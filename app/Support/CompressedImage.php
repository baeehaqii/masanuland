<?php

namespace App\Support;

use Filament\Forms\Components\BaseFileUpload;
use GdImage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Admin uploads are stored re-encoded as AVIF (WebP when the server's GD has
 * no AVIF), scaled down to MAX_WIDTH, so a 3 MB JPEG becomes ~100-200 KB.
 *
 * Use: FileUpload::make(...)->saveUploadedFileUsing(CompressedImage::store(...))
 */
class CompressedImage
{
    public const MAX_WIDTH = 1920;

    public const QUALITY = 60;

    public static function store(BaseFileUpload $component, TemporaryUploadedFile $file): ?string
    {
        $image = self::decode($file);

        // SVG, GIF, HEIC, broken files… keep Filament's default behaviour.
        if (! $image) {
            return $component->saveUploadedFile($file);
        }

        if (imagesx($image) > self::MAX_WIDTH) {
            $image = imagescale($image, self::MAX_WIDTH) ?: $image;
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        $avif = function_exists('imageavif');
        ob_start();
        $avif ? imageavif($image, null, self::QUALITY) : imagewebp($image, null, self::QUALITY);
        $bytes = (string) ob_get_clean();

        $path = trim($component->getDirectory().'/'.Str::ulid().($avif ? '.avif' : '.webp'), '/');
        $component->getDisk()->put($path, $bytes, $component->getVisibility());

        return $path;
    }

    private static function decode(TemporaryUploadedFile $file): ?GdImage
    {
        $path = $file->getRealPath();

        $image = match ($file->getMimeType()) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            'image/avif' => function_exists('imagecreatefromavif') ? @imagecreatefromavif($path) : false,
            default => false,
        };

        if (! $image) {
            return null;
        }

        // Phone photos are stored sideways with an EXIF hint; bake the rotation in.
        $orientation = $file->getMimeType() === 'image/jpeg' && function_exists('exif_read_data')
            ? ((@exif_read_data($path) ?: [])['Orientation'] ?? 1)
            : 1;
        $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;

        return $angle ? (imagerotate($image, $angle, 0) ?: $image) : $image;
    }
}
