<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Uc\ThumbnailGenerator\Processors\PdfProcessor;
use Illuminate\Http\File;

/**
 * Driver for generating thumbnails for pdf documents.
 *
 * @package Uc\ThumbnailGenerator\Drivers
 */
class PdfDriver implements ThumbnailGenerationDriverInterface
{
    /**
     * PdfDriver constructor.
     *
     * @param PdfProcessor $pdfProcessor
     */
    public function __construct(protected PdfProcessor $pdfProcessor)
    {
    }

    /**
     * @inheritDoc
     *
     * @param File $file
     *
     * @return bool
     */
    public function supports(File $file): bool
    {
        return $file->getExtension() === 'pdf';
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
     */
    public function generate(File $file, int $width, int $height): array
    {
        return $this->pdfProcessor->generateThumbnail($file, $width, $height);
    }
}
