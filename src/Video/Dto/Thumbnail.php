<?php

declare(strict_types=1);

namespace Vanilo\Video\Dto;

final readonly class Thumbnail
{
    public function __construct(
        public ?string $url = null,
        public ?int $width = null,
        public ?int $height = null,
    ) {
    }
}
