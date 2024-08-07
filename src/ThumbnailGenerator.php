<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator;

use Illuminate\Http\UploadedFile;
use Uc\ThumbnailGenerator\Drivers\ThumbnailGenerationDriverInterface;

/**
 * Utility to generate thumbnails for specified files.
 *
 * @package Uc\ThumbnailGenerator
 */
class ThumbnailGenerator
{
    /**
     * @var \Uc\ThumbnailGenerator\Drivers\ThumbnailGenerationDriverInterface[]|array
     */
    protected array $drivers = [];

    /**
     * ThumbnailGenerator constructor.
     *
     * @param \Uc\ThumbnailGenerator\Drivers\ThumbnailGenerationDriverInterface ...$drivers
     */
    public function __construct(ThumbnailGenerationDriverInterface ...$drivers)
    {
        $this->drivers = $drivers;
    }

    /**
     * Generate thumbnail of given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int                           $width
     * @param int                           $height
     *
     * @return string|null
     */
    public function generate(UploadedFile $file, int $width, int $height): string|null
    {
        foreach ($this->drivers as $driver) {
            if ($driver->supports($file)) {
                return $driver->generate($file, $width, $height);
            }
        }

        return null;
    }
}
