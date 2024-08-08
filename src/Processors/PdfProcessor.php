<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Processors;

use Illuminate\Http\UploadedFile;
use Uc\ImageManipulator\ImageManipulator;
use Imagick;
use ImagickException;

/**
 * Utility class for generating thumbnails of pdf documents.
 * Wraps Imagick extension.
 *
 * @package Uc\ThumbnailGenerator\Processors
 */
class PdfProcessor
{
    public function __construct(protected ImageManipulator $imageManipulator)
    {
    }

    /**
     * Generate thumbnail of given file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int                           $width
     * @param int                           $height
     *
     * @return string|null
     * @throws \ImagickException
     */
    public function generateThumbnail(UploadedFile $file, int $width, int $height): string|null
    {
        $content = $this->getFirstPageContent($file);
        return $this->imageManipulator->resize($content, $width, $height);
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return string
     * @throws \ImagickException
     */
    protected function getFirstPageContent(UploadedFile $file): string
    {
        try {
            $imagick = new Imagick();
            // Read first page of the file.
            $imagick->readImage(sprintf('%s[0]', $file->path()));
            $imagick->setImageFormat('jpeg');
        } catch (ImagickException) {
            return '';
        }

        return $imagick->getImageBlob();
    }
}
