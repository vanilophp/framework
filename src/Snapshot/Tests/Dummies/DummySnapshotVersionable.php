<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Tests\Dummies;

use Vanilo\Snapshot\Concerns\CalculatesSnapshotValidity;
use Vanilo\Snapshot\Concerns\HasSnapshotMetadata;
use Vanilo\Snapshot\Contracts\SnapshotPayload;
use Vanilo\Snapshot\SnapshotMetadata;

class DummySnapshotVersionable implements SnapshotPayload
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
        return new static($meta, $data);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
