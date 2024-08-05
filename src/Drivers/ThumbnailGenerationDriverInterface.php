<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\File;

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
     * @param File $file
     *
     * @return bool
     */
    public function supports(File $file): bool;

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     */
    public function generate(File $file, int $width, int $height): array;
}
