<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Contracts;

interface Snapshottable
{
    public function toSnapshotPayload(): SnapshotPayload;

    public static function fromSnapshotPayload(SnapshotPayload $payload): static;
}
