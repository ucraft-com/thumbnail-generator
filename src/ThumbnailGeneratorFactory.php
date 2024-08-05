<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator;

use Uc\ImageManipulator\ImageManipulator;
use Uc\ThumbnailGenerator\Drivers\AudioDriver;
use Uc\ThumbnailGenerator\Drivers\DocxDriver;
use Uc\ThumbnailGenerator\Drivers\ImageDriver;
use Uc\ThumbnailGenerator\Drivers\OdtDriver;
use Uc\ThumbnailGenerator\Drivers\PdfDriver;
use Uc\ThumbnailGenerator\Drivers\RtfDriver;
use Uc\ThumbnailGenerator\Drivers\VideoDriver;
use Uc\ThumbnailGenerator\Processors\DocumentProcessor;
use Uc\ThumbnailGenerator\Processors\FFMpegProcessor;
use Uc\ThumbnailGenerator\Processors\PdfProcessor;

/**
 * Factory for creating thumbnail generator instances.
 *
 * @package Uc\ThumbnailGeneratorFactory
 */
class ThumbnailGeneratorFactory
{
    public function __construct(
        protected FFMpegProcessor $ffmpegProcessor,
        protected ImageManipulator $imageManipulator
    ) {
    }

    /**
     * Create instance of ThumbnailGenerator powered with ImageDriver.
     *
     * @return \Uc\ThumbnailGenerator\ThumbnailGenerator
     */
    public function createImageThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new ImageDriver(
                $this->imageManipulator
            )
        );
    }

    /**
     * Create instance of ThumbnailGenerator powered with AudioDriver.
     *
     * @return \Uc\ThumbnailGenerator\ThumbnailGenerator
     */
    public function createAudioThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new AudioDriver(
                $this->ffmpegProcessor
            )
        );
    }

    /**
     * Create instance of ThumbnailGenerator powered with VideoDriver.
     *
     * @return \Uc\ThumbnailGenerator\ThumbnailGenerator
     */
    public function createVideoThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new VideoDriver(
                $this->ffmpegProcessor
            )
        );
    }

    /**
     * Create instance of ThumbnailGenerator powered with various Document drivers.
     *
     * @return \Uc\ThumbnailGenerator\ThumbnailGenerator
     */
    public function createDocumentThumbnailGenerator(): ThumbnailGenerator
    {
        $pdfProcessor = $this->createPdfProcessor();
        $documentProcessor = $this->createDocumentProcessor($pdfProcessor);

        $pdfDriver = new PdfDriver($pdfProcessor);
        $docxDriver = new DocxDriver($documentProcessor);
        $odtDriver = new OdtDriver($documentProcessor);
        $rtfDriver = new RtfDriver($documentProcessor);

        return new ThumbnailGenerator($pdfDriver, $docxDriver, $odtDriver, $rtfDriver);
    }

    /**
     * Create instance of DocumentProcessor.
     *
     * @param \Uc\ThumbnailGenerator\Processors\PdfProcessor $processor
     *
     * @return \Uc\ThumbnailGenerator\Processors\DocumentProcessor
     */
    protected function createDocumentProcessor(PdfProcessor $processor): DocumentProcessor
    {
        return new DocumentProcessor($processor);
    }

    /**
     * Create instance of PdfProcessor.
     *
     * @return \Uc\ThumbnailGenerator\Processors\PdfProcessor
     */
    protected function createPdfProcessor(): PdfProcessor
    {
        return new PdfProcessor($this->imageManipulator);
    }
}
