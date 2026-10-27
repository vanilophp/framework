<?php

declare(strict_types=1);

namespace Vanilo\Support\Tests\Dummies;

use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Vanilo\Contracts\Snapshot;

class DummySnapshotVersionable implements Snapshot
{
    public function __construct(
        private DateTimeImmutable $takenAt,
        private DateTimeImmutable $validUntil,
        private int $version,
        private ?array $data = null,
    ) {
    }

    public function version(): int
    {
        return $this->version;
    }

    public function takenAt(): DateTimeImmutable
    {
        return $this->takenAt;
    }

    public function validUntil(): DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function data(): array
    {
        return $this->data ?? [];
    }

    public static function create(DateTimeInterface $takenAt, DateTimeInterface $validUntil, int $version = 1): static
    {
        return new static(
            $takenAt instanceof DateTimeImmutable ? $takenAt : Carbon::instance($takenAt)->toImmutable(),
            $validUntil instanceof DateTimeImmutable ? $validUntil : Carbon::instance($validUntil)->toImmutable(),
            $version,
        );
    }

    public function load(array $data): void
    {
        $this->data = $data;
    }
}
