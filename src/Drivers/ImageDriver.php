<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Uc\ImageManipulator\ImageManipulator;
use Illuminate\Http\File;

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
     * @param \Illuminate\Http\File $file
     *
     * @return bool
     */
    public function supports(File $file): bool
    {
        return in_array($file->getExtension(), ['jpeg', 'jpg', 'png', 'webp', 'svg', 'gif'], true);
    }

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     */
    public function generate(File $file, int $width, int $height): array
    {
        $content = (string)file_get_contents($file->path());

        if (in_array($file->getMimeType(), ['image/svg+xml', 'application/svg+xml'], true)) {
            return ['frameContent' => $content, 'webPContent' => null];
        }

        return ['frameContent' => $this->imageManipulator->resize($content, $width, $height), 'webPContent' => null];
    }
}
