<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Concerns;

use DateTimeImmutable;
use Vanilo\Snapshot\SnapshotMetadata;

/**
 * Implements the metadata getters of the SnapshotPayload interface.
 * The using class must initialize the `$meta` property (eg. via constructor promotion).
 */
trait HasSnapshotMetadata
{
    protected readonly SnapshotMetadata $meta;

    public function version(): int
    {
        return $this->meta->version;
    }

    public function takenAt(): DateTimeImmutable
    {
        return $this->meta->takenAt;
    }

    public function validUntil(): ?DateTimeImmutable
    {
        return $this->meta->validUntil;
    }
}
