<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Uc\ThumbnailGenerator\Processors\DocumentProcessor;
use Illuminate\Http\File;

/**
 * Driver for generating thumbnails for odt documents.
 *
 * @package Uc\ThumbnailGenerator\Drivers
 */
class OdtDriver implements ThumbnailGenerationDriverInterface
{
    public function __construct(protected DocumentProcessor $documentProcessor)
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
        return $file->getExtension() === 'odt';
    }

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function generate(File $file, int $width, int $height): array
    {
        return $this->documentProcessor->generateThumbnailFromOdt($file, $width, $height);
    }
}