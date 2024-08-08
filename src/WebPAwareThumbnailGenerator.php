<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator;

use Uc\ImageManipulator\ImageManipulator;
use Illuminate\Http\UploadedFile;

/**
 * WebPAwareThumbnailGenerator
 *
 * A decorator class that enhances a ThumbnailGenerator instance with the capability
 * to generate thumbnails in both the original format and the WebP format.
 * This class leverages an ImageManipulator to convert the generated thumbnail
 * into WebP, a highly efficient image format known for superior compression rates.
 *
 * The primary method, `generate`, accepts an uploaded file along with the desired
 * thumbnail dimensions, and returns an array containing the original thumbnail
 * content and the WebP version. If the thumbnail generation fails, it returns
 * `null` values.
 *
 * Example usage:
 *
 * ```php
 * $generator = new ThumbnailGenerator();
 * $webPGenerator = new WebPAwareThumbnailGenerator($generator, $imageManipulator);
 *
 * [$original, $webP] = $webPGenerator->generate($uploadedFile, 150, 150);
 * ```
 *
 * This class is useful in scenarios where WebP support is needed alongside traditional
 * image formats, ensuring compatibility with modern web standards.
 *
 * @package Uc\ThumbnailGenerator
 */
class WebPAwareThumbnailGenerator
{
    public function __construct(
        protected ThumbnailGenerator $generator,
        protected ImageManipulator $imageManipulator,
    ) {
    }

    /**
     * Generate thumbnail of the given file in both the original format and WebP format.
     *
     * @param \Illuminate\Http\UploadedFile $file
     *        The uploaded file from which the thumbnail will be generated.
     * @param int                           $width
     *        The desired width of the thumbnail.
     * @param int                           $height
     *        The desired height of the thumbnail.
     *
     * @return array
     *         An array containing the original thumbnail content and the WebP version.
     *         If generation fails, returns [null, null].
     */
    public function generate(UploadedFile $file, int $width, int $height): array
    {
        $content = $this->generator->generate($file, $width, $height);
        if (null === $content) {
            return [null, null];
        }

        $webP = $this->imageManipulator->convertToWebP($content);

        return [$content, $webP];
    }
}
