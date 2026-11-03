<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Contracts;

interface Snapshottable
{
    public function toSnapshot(): Snapshot;

    public static function fromSnapshot(Snapshot $snapshot): static;
}
