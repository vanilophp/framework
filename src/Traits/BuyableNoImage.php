<?php
/**
 * Contains the BuyableNoImage trait.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-12
 *
 */

namespace Vanilo\Support\Traits;

use Illuminate\Support\Collection;

trait BuyableNoImage
{
    public function hasImage(): bool
    {
        return false;
    }

    public function imageCount(): int
    {
        return 0;
    }

    public function getThumbnailUrl(): ?string
    {
        return null;
    }

    public function getThumbnailUrls(): Collection
    {
        return collect([]);
    }

    public function getImageUrl(string $variant = ''): ?string
    {
        return null;
    }

    public function getImageUrls(string $variant = ''): Collection
    {
        return collect([]);
    }
}
