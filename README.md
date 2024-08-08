# Thumbnail Generator for Laravel

Thumbnail Generator is a versatile Laravel package designed to create and manage thumbnails from various file types, including images, videos, audios, documents, and PDF files. This package provides a straightforward API to generate thumbnails and customize their dimensions and quality.

## Features

- **Multiple File Type Support**: Generate thumbnails from images (JPEG, PNG, GIF), videos, audios, documents, and PDF files.
- **Customizable Dimensions**: Specify the width and height of the thumbnails.
- **Quality Control**: Adjust the quality of the generated thumbnails to balance between size and visual fidelity.
- **Aspect Ratio Maintenance**: Automatically maintain the aspect ratio of the original media.

## Requirements

- **PHP**: 8.1 or higher
- **Imagick PHP extension**: You need to have the [Imagick PHP extension](https://www.php.net/manual/en/book.imagick.php) installed and enabled to use the ThumbnailGenerator package.

## Installation

You can install the package via Composer:

```bash
composer require ucraft-com/thumbnail-generator

php artisan vendor:publish --provider="Uc\ThumbnailGenerator\ThumbnailGeneratorServiceProvider"
```
### Basic Usage

#### Images

```php
use Uc\ThumbnailGenerator\ThumbnailGeneratorFactory;

$factory = new ThumbnailGeneratorFactory(...);
$gen = $factory->createImageThumbnailGenerator();
$content = $gen->generate($file, 200, 200);
