<?php

declare(strict_types=1);

/**
 * Contains the HasAttachmentsFromMediaLibrary trait.
 *
 * @copyright   Copyright (c) 2026 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2026-10-28
 *
 */

namespace Vanilo\Foundation\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait HasAttachmentsFromMediaLibrary
{
    public const string ATTACHMENTS_MEDIA_COLLECTION = 'attachments';

    public function registerAttachmentsMediaCollection(): void
    {
        $this->addMediaCollection(self::ATTACHMENTS_MEDIA_COLLECTION);
    }

    public function addAttachment(string|UploadedFile $file, ?string $name = null): Media
    {
        $adder = $this->addMedia($file);
        if (null !== $name) {
            $adder->usingName($name);
        }

        return $adder->toMediaCollection(self::ATTACHMENTS_MEDIA_COLLECTION);
    }

    public function getAttachments(): MediaCollection
    {
        return $this->getMedia(self::ATTACHMENTS_MEDIA_COLLECTION);
    }

    public function getAttachment(int|string $idOrUuid): ?Media
    {
        return $this->getAttachments()->first(
            fn (Media $media) => is_int($idOrUuid) ? $media->id === $idOrUuid : $media->uuid === $idOrUuid
        );
    }

    public function hasAttachments(): bool
    {
        return $this->getAttachments()->isNotEmpty();
    }

    public function attachmentCount(): int
    {
        return $this->getAttachments()->count();
    }

    public function isAttachment(?Media $media): bool
    {
        return self::ATTACHMENTS_MEDIA_COLLECTION === $media?->collection_name;
    }
}
