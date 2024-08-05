<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests\Unit;

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Uc\ImageManipulator\ImageManipulator;
use Uc\ThumbnailGenerator\Drivers\ImageDriver;
use Uc\ThumbnailGenerator\ThumbnailGenerator;
use Illuminate\Http\File;

class ImageThumbnailGeneratorTest extends AbstractThumbnailGenerator
{
    public function testGenerate_Regular_ReturnThumbnail(): void
    {
        $generator = $this->createThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(new File(__DIR__.'/sources/image.png'), 200, 300);

        $this->assertImageProperties($content, 200, 300, 'image/png');
    }

    protected function createThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new ImageDriver(
                new ImageManipulator(
                    new ImageManager(
                        new Driver()
                    )
                )
            )
        );
    }
}
