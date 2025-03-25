<?php

declare(strict_types=1);

namespace Vanilo\Video\Contracts;

use Konekt\Extend\Contracts\Registerable;
use Vanilo\Video\Dto\DriverCapabilities;
use Vanilo\Video\Dto\MetaData;
use Vanilo\Video\Dto\Stats;
use Vanilo\Video\Dto\Thumbnail;

interface VideoDriver extends Registerable
{
    public static function capabilities(): DriverCapabilities;

    public static function whatIsReference(): string;

    public function stats(Video $video): Stats;

    public function getMetadata(Video $video): MetaData;

    public function getThumbnail(Video $video, array $options = []): Thumbnail;

    public function getVideoUrl(Video $video, array $options = []): ?string;

    public function getStreamUrl(Video $video, array $options = []): ?string;

    public function getFileUrl(Video $video, array $options = []): ?string;

    public function getEmbedCode(Video $video, array $options = []): ?string;
}
