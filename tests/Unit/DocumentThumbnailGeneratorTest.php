<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests\Unit;

use Illuminate\Http\File;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Uc\ThumbnailGenerator\Drivers\RtfDriver;
use Uc\ImageManipulator\ImageManipulator;
use Uc\ThumbnailGenerator\Drivers\DocxDriver;
use Uc\ThumbnailGenerator\Drivers\OdtDriver;
use Uc\ThumbnailGenerator\Processors\DocumentProcessor;
use Uc\ThumbnailGenerator\Processors\PdfProcessor;
use Uc\ThumbnailGenerator\ThumbnailGenerator;

class DocumentThumbnailGeneratorTest extends AbstractThumbnailGenerator
{
    public function testGenerate_RegularDocx_ReturnThumbnail(): void
    {
        $generator = $this->getThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(new File(__DIR__.'/sources/document.docx'), 100, 100);

        $this->assertImageProperties($content, 100, 100);
    }

    public function testGenerate_RegularOdt_ReturnThumbnail(): void
    {
        $generator = $this->getThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(new File(__DIR__.'/sources/document.odt'), 150, 200);

        $this->assertImageProperties($content, 150, 200);
    }

    public function testGenerate_Regular_ReturnThumbnail(): void
    {
        $generator = $this->getThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(new File(__DIR__.'/sources/document.rtf'), 100, 100);

        $this->assertImageProperties($content, 100, 100);
    }

    protected function getThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new DocxDriver(
                new DocumentProcessor(
                    new PdfProcessor(
                        new ImageManipulator(
                            new ImageManager(
                                new Driver()
                            )
                        )
                    )
                )
            ),
            new OdtDriver(
                new DocumentProcessor(
                    new PdfProcessor(
                        new ImageManipulator(
                            new ImageManager(
                                new Driver()
                            )
                        )
                    )
                )
            ),
            new RtfDriver(
                new DocumentProcessor(
                    new PdfProcessor(
                        new ImageManipulator(
                            new ImageManager(
                                new Driver()
                            )
                        )
                    )
                )
            )
        );
    }
}
