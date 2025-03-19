<?php

declare(strict_types=1);

namespace Vanilo\Video\Dto;

final readonly class MetaData
{
    public function __construct(
        public ?int $width = null,
        public ?int $height = null,
        public ?int $duration = null,
        public ?string $title = null,
        public ?string $description = null,
    ) {
    }
}
