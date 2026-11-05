<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Contracts;

use DateTimeImmutable;
use DateTimeInterface;
use Vanilo\Snapshot\SnapshotMetadata;

interface SnapshotPayload
{
    public function version(): int;

    public function takenAt(): DateTimeImmutable;

    /** A null value means the snapshot never expires */
    public function validUntil(): ?DateTimeImmutable;

    public function isValid(?DateTimeInterface $at = null): bool;

    public static function fromArray(array $data, SnapshotMetadata $meta): static;

    /** Must contain the content only, the metadata (version, dates) must not be part of it */
    public function toArray(): array;
}
