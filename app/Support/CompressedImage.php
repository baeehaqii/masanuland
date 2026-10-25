<?php

namespace App\Support;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\RichEditor;
use GdImage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Admin uploads are stored re-encoded as AVIF (WebP when the server's GD has
 * no AVIF), scaled down to MAX_WIDTH, so a 3 MB JPEG becomes ~100-200 KB.
 *
 * Every FileUpload goes through store() by default (AppServiceProvider).
 * Fields read by crawlers/browsers that don't take AVIF override it with as().
 */
class CompressedImage
{
    public const MAX_WIDTH = 1920;

    public const QUALITY = 60;

    public static function store(BaseFileUpload $component, TemporaryUploadedFile $file): ?string
    {
        return self::save($component->getDisk(), $component->getDirectory(), $component->getVisibility(), $file, self::defaultFormat(), self::MAX_WIDTH)
            // SVG, GIF, HEIC, broken files… keep Filament's default behaviour.
            ?? $component->saveUploadedFile($file);
    }

    /**
     * E.g. as('jpg', 1200) for OG images — WhatsApp/Facebook previews don't read AVIF.
     *
     * @param  'avif'|'webp'|'jpg'|'png'  $format
     */
    public static function as(string $format, int $maxWidth): Closure
    {
        return fn (BaseFileUpload $component, TemporaryUploadedFile $file): ?string => self::save($component->getDisk(), $component->getDirectory(), $component->getVisibility(), $file, $format, $maxWidth)
            ?? $component->saveUploadedFile($file);
    }

    /** Images dropped into a RichEditor (e.g. project description). */
    public static function attachment(RichEditor $component, TemporaryUploadedFile $file): ?string
    {
        $disk = $component->getFileAttachmentsDisk();
        $directory = (string) $component->getFileAttachmentsDirectory();
        $visibility = $component->getFileAttachmentsVisibility();

        if ($path = self::save($disk, $directory, $visibility, $file, self::defaultFormat(), self::MAX_WIDTH)) {
            return $path;
        }

        // Non-image attachments (PDF…): same as Filament's default.
        $path = $file->store($directory, $component->getFileAttachmentsDiskName()) ?: null;
        rescue(fn () => $path && $disk->setVisibility($path, $visibility), report: false);

        return $path;
    }

    /**
     * Preview WhatsApp/Facebook tidak membaca AVIF: buat salinan JPEG 1200 px
     * sekali (storage/og/…), lalu pakai itu untuk og:image.
     */
    public static function shareable(?string $path): ?string
    {
        if (! $path || ! str_ends_with(strtolower($path), '.avif') || ! function_exists('imagecreatefromavif')) {
            return $path;
        }

        $disk = Storage::disk('public');
        $copy = 'og/'.md5($path).'.jpg';

        if (! $disk->exists($copy)) {
            $image = $disk->exists($path) ? @imagecreatefromavif($disk->path($path)) : false;

            if (! $image) {
                return $path;
            }

            ob_start();
            imagejpeg(imagesx($image) > 1200 ? (imagescale($image, 1200) ?: $image) : $image, null, 82);
            $disk->put($copy, (string) ob_get_clean(), 'public');
        }

        return $copy;
    }

    /** @return 'avif'|'webp' */
    private static function defaultFormat(): string
    {
        return function_exists('imageavif') ? 'avif' : 'webp';
    }

    /**
     * Returns null when the file isn't a raster image GD can read.
     *
     * @param  'avif'|'webp'|'jpg'|'png'  $format
     */
    private static function save(Filesystem $disk, ?string $directory, string $visibility, TemporaryUploadedFile $file, string $format, int $maxWidth): ?string
    {
        $image = self::decode($file);

        if (! $image) {
            return null;
        }

        if (imagesx($image) > $maxWidth) {
            $image = imagescale($image, $maxWidth) ?: $image;
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);

        ob_start();
        match ($format) {
            'avif' => imageavif($image, null, self::QUALITY),
            'webp' => imagewebp($image, null, self::QUALITY),
            'jpg' => imagejpeg($image, null, 82),
            'png' => imagepng($image, null, 9),
        };
        $bytes = (string) ob_get_clean();

        $path = trim($directory.'/'.Str::ulid().'.'.$format, '/');
        $disk->put($path, $bytes, $visibility);

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
