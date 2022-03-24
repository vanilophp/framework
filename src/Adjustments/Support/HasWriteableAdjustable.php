<?php

declare(strict_types=1);

/**
 * Contains the HasWriteableAdjustable trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Support;

use Vanilo\Adjustments\Contracts\Adjustable;

trait HasWriteableAdjustable
{
    private Adjustable $adjustable;

    public function getAdjustable(): Adjustable
    {
        return $this->adjustable;
    }

    public function setAdjustable(Adjustable $adjustable): void
    {
        $this->adjustable = $adjustable;
    }
}
