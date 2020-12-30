<?php

declare(strict_types=1);

/**
 * Contains the HasImages trait.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-19
 *
 */

namespace Vanilo\Support\Traits;

use Illuminate\Support\Collection;

trait HasImagesFromMediaLibrary
{
    protected $mediaCollectionName = 'default';

    public function hasImage(): bool
    {
        return $this->getMedia()->isNotEmpty();
    }

    public function imageCount(): int
    {
        return $this->getMedia()->count();
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->getImageUrl('thumbnail');
    }

    public function getThumbnailUrls(): Collection
    {
        return $this->getImageUrls('thumbnail');
    }

    public function getImageUrl(string $variant = ''): ?string
    {
        $medium = $this->fetchPrimaryMedium();
        if (null === $medium) {
            return null;
        }

        return $medium->getUrl($variant);
    }

    public function getImageUrls(string $variant = ''): Collection
    {
        return $this->getMedia($this->mediaCollectionName)->map(function ($medium) use ($variant) {
            return $medium->getUrl($variant);
        });
    }

    private function fetchPrimaryMedium()
    {
        $primary = $this->getFirstMedia($this->mediaCollectionName, ['isPrimary' => true]);

        return $primary ?: $this->getFirstMedia($this->mediaCollectionName);
    }
}
