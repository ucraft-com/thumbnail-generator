<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator;

use Uc\ThumbnailGenerator\Drivers\ThumbnailGenerationDriverInterface;
use Illuminate\Http\File;

/**
 * Utility to generate thumbnails for specified files.
 *
 * @package App\Services\ThumbnailGenerator
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
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     */
    public function generate(File $file, int $width, int $height): array
    {
        foreach ($this->drivers as $driver) {
            if ($driver->supports($file)) {
                return $driver->generate($file, $width, $height);
            }
        }

        return ['frameContent' => null, 'webPContent' => null];
    }
}
