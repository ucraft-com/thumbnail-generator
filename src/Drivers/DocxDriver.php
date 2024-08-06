<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\UploadedFile;
use Uc\ThumbnailGenerator\Processors\DocumentProcessor;

/**
 * Driver for generating thumbnails for docx documents.
 *
 * @package Uc\ThumbnailGenerator\Drivers
 */
class DocxDriver implements ThumbnailGenerationDriverInterface
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
        return $file->getExtension() === 'docx';
    }

    /**
     * Generate thumbnail for given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int                           $width
     * @param int                           $height
     *
     * @return array
     * @throws \ImagickException
     */
    public function generate(UploadedFile $file, int $width, int $height): array
    {
        return $this->documentProcessor->generateThumbnailFromDocx($file, $width, $height);
    }
}
