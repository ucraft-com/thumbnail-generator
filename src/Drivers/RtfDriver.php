<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\UploadedFile;
use Uc\ThumbnailGenerator\Processors\DocumentProcessor;

/**
 * Driver for generating thumbnails for rtf documents.
 *
 * @package Uc\ThumbnailGenerator\Drivers
 */
class RtfDriver implements ThumbnailGenerationDriverInterface
{
    public function __construct(protected DocumentProcessor $documentProcessor)
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
        return $file->guessExtension() === 'rtf';
    }

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int                           $width
     * @param int                           $height
     *
     * @return string|null
     * @throws \ImagickException
     */
    public function generate(UploadedFile $file, int $width, int $height): string|null
    {
        return $this->documentProcessor->generateThumbnailFromRtf($file, $width, $height);
    }
}
