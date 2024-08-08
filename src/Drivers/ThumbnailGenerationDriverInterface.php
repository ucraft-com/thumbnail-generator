<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\UploadedFile;

/**
 * Interface declares main functionality for thumbnail generation drivers.
 *
 * @package App\Services\ThumbnailGenerator
 */
interface ThumbnailGenerationDriverInterface
{
    /**
     * Determine whether the driver supports given file to generate thumbnail.
     *
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return bool
     */
    public function supports(UploadedFile $file): bool;

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int                           $width
     * @param int                           $height
     *
     * @return string|null
     */
    public function generate(UploadedFile $file, int $width, int $height): string|null;
}
