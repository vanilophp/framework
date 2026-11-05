<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Contracts;

use DateTimeImmutable;
use DateTimeInterface;

interface Snapshot
{
    public static function take(Snapshottable $source): static;

    public function takenAt(): DateTimeImmutable;

    public function validUntil(): ?DateTimeImmutable;

    public function isValid(?DateTimeInterface $at = null): bool;

    public function getRawData(): array;

    public function payload(): SnapshotPayload;
}
