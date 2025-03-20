<?php

declare(strict_types=1);

namespace Vanilo\Video\Dto;

final readonly class DriverCapabilities
{
    public function __construct(
        public bool $embedding = false,
        public bool $fileAccess = false,
        public bool $fileUpload = false,
        public bool $streaming = false,
        public bool $thumbnails = false,
        public bool $stats = false,
    ) {
    }
}
