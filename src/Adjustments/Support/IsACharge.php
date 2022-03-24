<?php

declare(strict_types=1);

/**
 * Contains the IsACharge trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Support;

trait IsACharge
{
    public function isCredit(): bool
    {
        return false;
    }

    public function isCharge(): bool
    {
        return true;
    }
}
