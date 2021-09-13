<?php

declare(strict_types=1);

/**
 * Contains the HasImages interface.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-19
 *
 */

namespace Vanilo\Contracts;

use Illuminate\Support\Collection;

interface HasImages
{
    public function hasImage(): bool;

    public function imageCount(): int;

    public function getThumbnailUrl(): ?string;

    public function getThumbnailUrls(): Collection;

    public function getImageUrl(string $variant = ''): ?string;

    public function getImageUrls(string $variant = ''): Collection;
}
