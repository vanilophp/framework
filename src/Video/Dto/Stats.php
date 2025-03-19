<?php

declare(strict_types=1);

namespace Vanilo\Video\Dto;

final readonly class Stats
{
    public function __construct(
        public ?int $views = null,
        public ?int $likes = null,
        public ?int $comments = null,
    ) {
    }
}
