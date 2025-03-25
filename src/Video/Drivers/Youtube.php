<?php

declare(strict_types=1);

namespace Vanilo\Video\Drivers;

use Vanilo\Video\Contracts\Video;
use Vanilo\Video\Contracts\VideoDriver;
use Vanilo\Video\Dto\DriverCapabilities;
use Vanilo\Video\Dto\MetaData;
use Vanilo\Video\Dto\Stats;
use Vanilo\Video\Dto\Thumbnail;

class Youtube implements VideoDriver
{
    public const string ID = 'youtube';

    public const int DEFAULT_THUMBNAIL_WIDTH = 480;
    public const int DEFAULT_THUMBNAIL_HEIGHT = 360;
    public const int DEFAULT_EMBED_WIDTH = 560;
    public const int DEFAULT_EMBED_HEIGHT = 315;

    protected static ?DriverCapabilities $capabilities = null;

    public static function getName(): string
    {
        return 'Youtube';
    }

    public static function capabilities(): DriverCapabilities
    {
        return self::$capabilities ??= new DriverCapabilities(
            embedding: true,
            thumbnails: true,
            stats: true,
        );
    }

    public static function whatIsReference(): string
    {
        return __('The Youtube video ID');
    }

    public function stats(Video $video): Stats
    {
        return new Stats();
    }

    public function getMetadata(Video $video): MetaData
    {
        return new MetaData();
    }

    public function getThumbnail(Video $video, array $options = []): Thumbnail
    {
        return new Thumbnail(
            url: sprintf('https://i.ytimg.com/vi/%s/hqdefault.jpg', $video->getReference()),
            width: self::DEFAULT_THUMBNAIL_WIDTH,
            height: self::DEFAULT_THUMBNAIL_HEIGHT,
        );
    }

    public function getVideoUrl(Video $video, array $options = []): ?string
    {
        return 'https://www.youtube.com/watch?v=' . $video->getReference();
    }

    public function getStreamUrl(Video $video, array $options = []): ?string
    {
        return null;
    }

    public function getFileUrl(Video $video, array $options = []): ?string
    {
        return null;
    }

    public function getEmbedCode(Video $video, array $options = []): ?string
    {
        $width = is_numeric($options['width'] ?? null) ? (int) $options['width'] : ($video->getWidth() ?: self::DEFAULT_EMBED_WIDTH);
        $height = is_numeric($options['height'] ?? null) ? (int) $options['height'] : ($video->getHeight() ?: self::DEFAULT_EMBED_HEIGHT);

        return sprintf(
            '<iframe width="%d" height="%d" src="https://www.youtube.com/embed/%s"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
            $width,
            $height,
            $video->getReference(),
        );
    }
}
