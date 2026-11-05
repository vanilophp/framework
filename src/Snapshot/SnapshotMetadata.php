<?php

declare(strict_types=1);

namespace Vanilo\Snapshot;

use DateTimeImmutable;

final class SnapshotMetadata
{
    public function __construct(
        public readonly int $version,
        public readonly DateTimeImmutable $takenAt,
        public readonly ?DateTimeImmutable $validUntil = null,
    ) {
    }
}
