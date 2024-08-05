<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests\Unit;

use Uc\ThumbnailGenerator\Tests\TestCase;

abstract class AbstractThumbnailGenerator extends TestCase
{
    protected function assertImageProperties(string $content, int $width, int $height, string $mimeType = 'image/jpeg'): void
    {
        $info = getimagesizefromstring($content);

        $this->assertLessThanOrEqual($width, $info[0]);
        $this->assertLessThanOrEqual($height, $info[1]);
        $this->assertEquals($mimeType, $info['mime']);
    }
}
