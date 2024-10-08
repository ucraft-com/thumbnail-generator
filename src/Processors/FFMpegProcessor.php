<?php

declare(strict_types=1);

namespace Uc\ThumbnailGenerator\Processors;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Media\Audio;
use FFMpeg\Media\Frame;
use FFMpeg\Media\Video;
use Illuminate\Http\UploadedFile;
use Uc\ImageManipulator\ImageManipulator;
use FFMpeg\FFMpeg;

use function config;
use function stream_get_meta_data;
use function tmpfile;
use function file_get_contents;

/**
 * Utility class wraps "ffmpeg" library to provide thumbnail generation mechanism for audio and video files.
 *
 * @package Uc\ThumbnailGenerator\Processors
 */
class FFMpegProcessor
{
    /**
     * @var \FFMpeg\FFMpeg
     */
    protected FFMpeg $ffmpeg;

    public function __construct(protected ImageManipulator $imageManipulator)
    {
        $this->ffmpeg = FFMpeg::create([
            'ffmpeg.threads'   => 4,
            'ffmpeg.timeout'   => 300,
            'ffprobe.timeout'  => 30,
            'ffmpeg.binaries'  => config('thumbnail-generator.ffmpeg_binary'),
            'ffprobe.binaries' => config('thumbnail-generator.ffprobe_binary'),
        ]);
    }

    /**
     * Generate thumbnail for given video file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int|null                      $width
     * @param int|null                      $height
     *
     * @return string|null
     */
    public function generateVideoThumbnail(UploadedFile $file, ?int $width, ?int $height): string|null
    {
        $media = $this->open($file);

        if (!$media instanceof Video) {
            return null;
        }

        $second = $this->calculateThumbnailMoment($file);
        $frame = $this->retrieveFrame($media, $second);

        return $this->getFrameContent($frame, $width, $height);
    }

    /**
     * Generate thumbnail for given audio file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int|null                      $width
     * @param int|null                      $height
     *
     * @return string|null
     */
    public function generateAudioThumbnail(UploadedFile $file, ?int $width, ?int $height): string|null
    {
        $media = $this->open($file);

        if (!$media instanceof Video) {
            return null;
        }

        $frame = $this->retrieveFrame($media, 0.0);
        return $this->getFrameContent($frame, $width, $height);
    }

    /**
     * Open given media file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \FFMpeg\Media\Audio|\FFMpeg\Media\Video
     */
    protected function open(UploadedFile $file): Video|Audio
    {
        return $this->ffmpeg->open($file->path());
    }

    /**
     * Calculate the moment for taking the video snapshot for thumbnail.
     *
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return float
     */
    protected function calculateThumbnailMoment(UploadedFile $file): float
    {
        $duration = $this->getDuration($file);

        return ($duration / 10 > 10) ? 10 : $duration / 10;
    }

    /**
     * Return file duration in seconds
     *
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return float
     */
    public function getDuration(UploadedFile $file): float
    {
        return (float)$this->ffmpeg->getFFProbe()
            ->format($file->path())->get('duration', 0);
    }

    /**
     * Retrieve frame from the given file.
     *
     * @param \FFMpeg\Media\Video $video
     * @param float               $second
     *
     * @return \FFMpeg\Media\Frame
     */
    protected function retrieveFrame(Video $video, float $second): Frame
    {
        return $video->frame(TimeCode::fromSeconds($second));
    }

    /**
     * Get content of the given frame.
     *
     * @param \FFMpeg\Media\Frame $frame
     * @param int|null            $width
     * @param int|null            $height
     *
     * @return string|null
     */
    protected function getFrameContent(Frame $frame, ?int $width, ?int $height): string|null
    {
        $path = stream_get_meta_data(tmpfile())['uri'];
        $frame->save($path, true);
        $content = @file_get_contents($path);

        if (empty($content)) {
            return null;
        }

        return $this->imageManipulator->resize($content, $width, $height);
    }
}
