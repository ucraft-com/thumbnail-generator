<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Processors;

use Uc\ImageManipulator\ImageManipulator;
use Illuminate\Http\File;
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
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     * @throws \ImagickException
     */
    public function generateThumbnail(File $file, int $width, int $height): array
    {
        $content = $this->getFirstPageContent($file);

        return [
            'frameContent' => $this->imageManipulator->resize($content, $width, $height),
            'webPContent'  => null
        ];
    }

    /**
     * @param \Illuminate\Http\File $file
     *
     * @return string
     * @throws \ImagickException
     */
    protected function getFirstPageContent(File $file): string
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
