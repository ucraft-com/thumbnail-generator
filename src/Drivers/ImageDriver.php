<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\UploadedFile;
use Uc\ImageManipulator\ImageManipulator;

use function file_get_contents;
use function in_array;

/**
 * Driver for generating thumbnails for image files.
 *
 * @package Uc\ThumbnailGenerator\Drivers
 */
class ImageDriver implements ThumbnailGenerationDriverInterface
{
    public function __construct(protected ImageManipulator $imageManipulator)
    {
    }

    /**
     * Determine whether the driver supports given file to generate thumbnail.
     *
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return bool
     */
    public function supports(UploadedFile $file): bool
    {
        return in_array($file->guessExtension(), ['jpeg', 'jpg', 'png', 'webp', 'svg', 'gif'], true);
    }

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int                           $width
     * @param int                           $height
     *
     * @return string|null
     */
    public function generate(UploadedFile $file, int $width, int $height): string|null
    {
        $content = (string)file_get_contents($file->path());

        if (in_array($file->getMimeType(), ['image/svg+xml', 'application/svg+xml'], true)) {
            return $content;
        }

        return $this->imageManipulator->resize($content, $width, $height);
    }
}
