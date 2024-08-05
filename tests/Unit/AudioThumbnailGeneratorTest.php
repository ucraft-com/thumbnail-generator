<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests\Unit;

use Illuminate\Http\File;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Uc\ImageManipulator\ImageManipulator;
use Uc\ThumbnailGenerator\Drivers\AudioDriver;
use Uc\ThumbnailGenerator\Processors\FFMpegProcessor;
use Uc\ThumbnailGenerator\ThumbnailGenerator;

class AudioThumbnailGeneratorTest extends AbstractThumbnailGenerator
{
    public function testGenerate_WhenAudioHasArtwork_ReturnsThumbnail(): void
    {
        $generator = $this->getThumbnailGenerator();
        ['frameContent' => $content] = $generator->generate(
            new File(__DIR__.'/sources/audio_with_artwork.mp3'),
            100,
            100
        );

        $this->assertImageProperties($content, 100, 100);
    }

    protected function getThumbnailGenerator(): ThumbnailGenerator
    {
        return new ThumbnailGenerator(
            new AudioDriver(
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
