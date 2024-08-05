<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\File;
use Uc\ThumbnailGenerator\Processors\FFMpegProcessor;

use function in_array;

/**
 * Driver for generating thumbnails for audio files.
 *
 * @package App\Services\ThumbnailGenerator\Drivers
 */
class AudioDriver implements ThumbnailGenerationDriverInterface
{
    public function __construct(protected FFMpegProcessor $processor)
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
        return in_array($file->getExtension(), ['mpga', 'mp3', 'aac', 'm4a'], true);
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
        return $this->processor->generateAudioThumbnail($file, $width, $height);
    }
}
