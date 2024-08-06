<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Drivers;

use Illuminate\Http\UploadedFile;
use Uc\ThumbnailGenerator\Processors\PdfProcessor;

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
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function supports(UploadedFile $file): bool
    {
        return $file->getExtension() === 'pdf';
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
        return $this->pdfProcessor->generateThumbnail($file, $width, $height);
    }
}
