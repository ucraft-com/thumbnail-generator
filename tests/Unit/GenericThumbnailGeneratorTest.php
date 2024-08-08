<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Uc\ImageManipulator\ImageManipulator;
use Uc\ThumbnailGenerator\Processors\FFMpegProcessor;
use Uc\ThumbnailGenerator\ThumbnailGeneratorFactory;
use PHPUnit\Framework\Attributes\DataProvider;

class GenericThumbnailGeneratorTest extends AbstractThumbnailGenerator
{
    public static function mediaProvider(): array
    {
        return [
            [__DIR__.'/sources/audio_with_artwork.mp3', 'audio_with_artwork.mp3', 'image/jpeg'], // audio
            [__DIR__.'/sources/document.docx', 'document.docx', 'image/jpeg'], // document
            [__DIR__.'/sources/document.odt', 'document.odt', 'image/jpeg'], // document
            [__DIR__.'/sources/document.rtf', 'document.rtf', 'image/jpeg'], // document
            [__DIR__.'/sources/image.png', 'image.png', 'image/png'], // image
            [__DIR__.'/sources/pdf.pdf', 'pdf.pdf', 'image/jpeg'], // pdf
            [__DIR__.'/sources/webm.webm', 'webm.webm', 'image/jpeg'], // video
            [__DIR__.'/sources/mov.mov', 'mov.mov', 'image/jpeg'], // video
            [__DIR__.'/sources/asf.asf', 'asf.asf', 'image/jpeg'], // video
            [__DIR__.'/sources/mkv.mkv', 'mkv.mkv', 'image/jpeg'], // video
            [__DIR__.'/sources/avi.avi', 'avi.avi', 'image/jpeg'], // video
            [__DIR__.'/sources/flv.flv', 'flv.flv', 'image/jpeg'], // video
            [__DIR__.'/sources/mp4.mp4', 'mp4.mp4', 'image/jpeg'], // video
        ];
    }

    #[DataProvider('mediaProvider')]
    public function testGenerate_WithDifferentFormats_ReturnsThumbnail(
        string $path,
        string $name,
        string|null $mime
    ): void {
        $generator = $this
            ->getThumbnailGeneratorFactory()
            ->createGenericThumbnailGenerator();
        $content = $generator->generate(new UploadedFile($path, $name), 100, 100);

        $this->assertImageProperties($content, 100, 100, $mime);
    }

    protected function getThumbnailGeneratorFactory(): ThumbnailGeneratorFactory
    {
        return new ThumbnailGeneratorFactory(
            new FFMpegProcessor(
                new ImageManipulator(
                    new ImageManager(
                        new Driver()
                    )
                )
            ),
            new ImageManipulator(
                new ImageManager(
                    new Driver()
                )
            )
        );
    }
}
