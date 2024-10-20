<?php

declare(strict_types=1);

/**
 * Contains the Inventory class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-10-18
 *
 */

namespace Vanilo\Support\Features;

use Vanilo\Contracts\Feature;

class Inventory implements Feature
{
    public function isEnabled(): bool
    {
        return config('vanilo.features.inventory.is_enabled', false)
            && null !== concord()->module('inventory');
    }

    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    public function configuration(): array
    {
        return config('vanilo.features.inventory', []);
    }
}
