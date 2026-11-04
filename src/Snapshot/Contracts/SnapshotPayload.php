<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Contracts;

use DateTimeImmutable;
use DateTimeInterface;

interface SnapshotPayload
{
    public function version(): int;

    public function takenAt(): DateTimeImmutable;

    public function validUntil(): DateTimeImmutable;

    public function getData(): array;

    public static function create(
        DateTimeInterface $takenAt,
        DateTimeInterface $validUntil,
        int $version = 1,
    ): static;

    public function load(array $data): void;
}
