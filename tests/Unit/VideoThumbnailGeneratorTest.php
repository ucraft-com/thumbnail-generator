<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Uc\ImageManipulator\ImageManipulator;
use Uc\ThumbnailGenerator\Drivers\VideoDriver;
use Uc\ThumbnailGenerator\Processors\FFMpegProcessor;
use Uc\ThumbnailGenerator\ThumbnailGenerator;

class VideoThumbnailGeneratorTest extends AbstractThumbnailGenerator
{
    public function testGenerate_RegularMp4_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/mp4.mp4', 'mp4.mp4'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    public function testGenerate_RegularFlv_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/flv.flv', 'flv.flv'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    public function testGenerate_RegularAvi_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/avi.avi', 'avi.avi'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    public function testGenerate_RegularMkv_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/mkv.mkv', 'mkv.mkv'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    public function testGenerate_RegularAsf_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/asf.asf', 'asf.asf'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    public function testGenerate_RegularWebm_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/webm.webm', 'webm.webm'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    public function testGenerate_RegularMov_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new UploadedFile(__DIR__.'/sources/mov.mov', 'mov.mov'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    protected function createThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new VideoDriver(
                new FFMpegProcessor(
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
