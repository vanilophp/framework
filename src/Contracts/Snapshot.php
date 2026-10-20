<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

use DateTimeImmutable;

interface Snapshot
{
    public function version(): int;

    public function takenAt(): DateTimeImmutable;

    public function validUntil(): DateTimeImmutable;

    public function data(): array;

    public static function from(array $rawData): static;
}
