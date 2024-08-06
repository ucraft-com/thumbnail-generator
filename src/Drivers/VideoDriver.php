<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\UploadedFile;
use Uc\ThumbnailGenerator\Processors\FFMpegProcessor;

use function in_array;

/**
 * Driver for generating thumbnails for video files.
 *
 * @package Uc\ThumbnailGenerator\Drivers
 */
class VideoDriver implements ThumbnailGenerationDriverInterface
{
    public function __construct(protected FFMpegProcessor $processor)
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
        return in_array($file->getExtension(), ['mp4', 'flv', 'avi', 'mkv', 'asf', 'webm', 'mov', 'ogg', 'ogv', 'svg'], true);
    }

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int                           $width
     * @param int                           $height
     *
     * @return array
     */
    public function generate(UploadedFile $file, int $width, int $height): array
    {
        return $this->processor->generateVideoThumbnail($file, $width, $height);
    }
}
