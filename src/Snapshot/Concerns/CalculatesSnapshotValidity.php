<?php

declare(strict_types=1);

namespace Vanilo\Snapshot\Concerns;

use DateTimeInterface;
use LogicException;

trait CalculatesSnapshotValidity
{

    public function isValid(?DateTimeInterface $at = null): bool
    {
        if (!method_exists($this, 'validUntil')) {
            throw new LogicException(sprintf('The %s class does not implement the validUntil() method', static::class ));
        }

        if (null === $this->validUntil()) {
            return true;
        }

        return $this->validUntil()->isAfter($at ?? \Illuminate\Support\now());
    }
}
