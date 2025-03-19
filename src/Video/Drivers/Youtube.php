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

    protected static ?DriverCapabilities $capabilities = null;

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
        return new Metadata();
    }

    public function getThumbnail(Video $video): Thumbnail
    {
        return new Thumbnail(
            url: sprintf('https://i.ytimg.com/vi/%s/hqdefault.jpg', $video->getReference()),
            width: 480,
            height: 360,
        );
    }

    public function getVideoUrl(Video $video): ?string
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

    public function getEmbedCode(Video $video, array $options = []): string
    {
        return sprintf(
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/%s"
                title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
            $video->getReference()
        );
    }
}
