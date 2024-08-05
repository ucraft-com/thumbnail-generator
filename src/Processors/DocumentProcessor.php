<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Processors;

use Illuminate\Http\File;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use PhpOffice\PhpWord\Exception\Exception as PhpWordException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\PDF;
use PhpOffice\PhpWord\Writer\WriterInterface;

use function base_path;
use function stream_get_meta_data;
use function tmpfile;

/**
 * Utility class for generating thumbnails of various documents.
 * Under the hood wraps PhpOffice library.
 *
 * @package Uc\ThumbnailGenerator\Processors
 */
class DocumentProcessor
{
    public function __construct(protected PdfProcessor $pdfProcessor)
    {
        $domPdfPath = base_path('vendor/dompdf/dompdf');

        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
    }

    /**
     * Generate thumbnail from docx document.
     *
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception|\ImagickException
     */
    public function generateThumbnailFromDocx(File $file, int $width, int $height): array
    {
        $writer = $this->createWordWriter('Word2007', $file->path());

        return $this->generateThumbnail($writer, $width, $height);
    }

    /**
     * Generate thumbnail from ODT (OpenOffice format) document.
     *
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception|\ImagickException
     */
    public function generateThumbnailFromOdt(File $file, int $width, int $height): array
    {
        $writer = $this->createWordWriter('ODText', $file->path());

        return $this->generateThumbnail($writer, $width, $height);
    }

    /**
     * Generate thumbnail from Rich text document.
     *
     * @param \Illuminate\Http\File $file
     * @param int                   $width
     * @param int                   $height
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception|\ImagickException
     */
    public function generateThumbnailFromRtf(File $file, int $width, int $height): array
    {
        $writer = $this->createWordWriter('RTF', $file->path());

        return $this->generateThumbnail($writer, $width, $height);
    }

    /**
     * Create instance of Word Writer.
     *
     * @param string $format
     * @param string $path
     *
     * @return \PhpOffice\PhpWord\Writer\PDF|\PhpOffice\PhpWord\Writer\WriterInterface|null
     */
    protected function createWordWriter(string $format, string $path): PDF|WriterInterface|null
    {
        try {
            $reader = IOFactory::createReader($format)->load($path);

            return IOFactory::createWriter($reader, 'PDF');
        } catch (PhpWordException) {
            return null;
        }
    }

    /**
     * Generate thumbnail with given writer instance.
     *
     * @param \PhpOffice\PhpSpreadsheet\Writer\IWriter|\PhpOffice\PhpWord\Writer\WriterInterface|\PhpOffice\PhpWord\Writer\PDF|null $writer
     * @param int                                                                                                                   $width
     * @param int                                                                                                                   $height
     *
     * @return array
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    protected function generateThumbnail(IWriter|WriterInterface|PDF|null $writer, int $width, int $height): array
    {
        if ($writer === null) {
            return ['frameContent' => null, 'webPContent' => null];
        }

        $writer->save($path = $this->getTemporaryPath());

        return $this->generateThumbnailFromPdf($path, $width, $height);
    }

    /**
     * Create temporary file and return its path.
     *
     * @return string
     */
    protected function getTemporaryPath(): string
    {
        return stream_get_meta_data(tmpfile())['uri'];
    }

    /**
     * Generate thumbnail from PDF document.
     *
     * @param string $path
     * @param int    $width
     * @param int    $height
     *
     * @return array
     * @throws \ImagickException
     */
    protected function generateThumbnailFromPdf(string $path, int $width, int $height): array
    {
        return $this->pdfProcessor->generateThumbnail(new File($path), $width, $height);
    }
}
