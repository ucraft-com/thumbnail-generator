<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Uc\ThumbnailGenerator\ThumbnailGeneratorServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        $app->setBasePath(realpath(__DIR__ . '/..'));
        return [
            ThumbnailGeneratorServiceProvider::class,
        ];
    }
}
