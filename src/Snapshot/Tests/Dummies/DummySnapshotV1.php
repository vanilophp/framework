<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Tests\Dummies;

use Vanilo\Snapshot\Concerns\CalculatesSnapshotValidity;
use Vanilo\Snapshot\Concerns\HasSnapshotMetadata;
use Vanilo\Snapshot\Contracts\SnapshotPayload;
use Vanilo\Snapshot\SnapshotMetadata;

class DummySnapshotV1 implements SnapshotPayload
{
    use CalculatesSnapshotValidity;
    use HasSnapshotMetadata;

    public function __construct(
        protected readonly SnapshotMetadata $meta,
        private readonly array $data = [],
    ) {
    }

    public static function fromArray(array $data, SnapshotMetadata $meta): static
    {
        // No processing here, just 1:1 throughput
        // Real classes will do the hydration legwork here
        return new static($meta, $data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
