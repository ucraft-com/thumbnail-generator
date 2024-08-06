<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Uc\ImageManipulator\ImageManipulator;
use Uc\ThumbnailGenerator\Drivers\PdfDriver;
use Uc\ThumbnailGenerator\Processors\PdfProcessor;
use Uc\ThumbnailGenerator\ThumbnailGenerator;

class PdfThumbnailGeneratorTest extends AbstractThumbnailGenerator
{
    public function testGenerate_Regular_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/pdf.pdf', 'pdf.pdf'),
            200,
            300
        );

        $this->assertImageProperties($content, 200, 300);
    }

    protected function createThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new PdfDriver(
                new PdfProcessor(
                    new ImageManipulator(
                        new ImageManager(
                            new Driver()
                        )
                    )
                )
            )
        );
    }
}
