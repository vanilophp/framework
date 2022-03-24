<?php

declare(strict_types=1);

/**
 * Contains the IsLockable trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Support;

trait IsLockable
{
    private bool $isLocked = false;

    public function isLocked(): bool
    {
        return $this->isLocked;
    }

    public function lock(): void
    {
        $this->isLocked = true;
    }

    public function unlock(): void
    {
        $this->isLocked = false;
    }
}
