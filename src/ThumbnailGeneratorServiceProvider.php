<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator;

use App\Services\ImageManipulator\ImageManipulator;
use App\Services\ThumbnailGenerator\Processors\FFMpegProcessor;
use App\Services\ThumbnailGenerator\ThumbnailGeneratorFactory;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider of the package.
 *
 * @author Tigran Mesropyan <tiko@ucraft.com>
 */
class ThumbnailGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            'thumbnail-generator'
        );

        $this->app->singleton(FFMpegProcessor::class, function () {
            return new FFMpegProcessor($this->app->get(ImageManipulator::class));
        });

        $this->app->bind(ThumbnailGeneratorFactory::class, function () {
            return new ThumbnailGeneratorFactory(
                $this->app->get(FFMpegProcessor::class),
                $this->app->get(ImageManipulator::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__.'/../config/config.php' => config_path('thumbnail-generator.php'),
                ],
                'thumbnail-generator'
            );
        }
    }
}
