<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Contracts;

use DateTimeImmutable;

interface Snapshot
{
    public static function take(Snapshottable $source): static;

    public function takenAt(): DateTimeImmutable;

    public function validUntil(): ?DateTimeImmutable;

    public function getRawData(): array;

    public function payload(): SnapshotPayload;
}
