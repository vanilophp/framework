<?php

declare(strict_types=1);

/**
 * Contains the IsNotIncluded trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Support;

trait IsNotIncluded
{
    public function isIncluded(): bool
    {
        return false;
    }
}
