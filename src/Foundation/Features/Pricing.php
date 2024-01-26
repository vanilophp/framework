<?php

declare(strict_types=1);

/**
 * Contains the Pricing class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-01-18
 *
 */

namespace Vanilo\Foundation\Features;

use Vanilo\Foundation\Contracts\Feature;

class Pricing implements Feature
{
    public function isEnabled(): bool
    {
        return config('vanilo.foundation.features.pricing.is_enabled', false)
            && null !== concord()->module('vanilo.pricing');
    }

    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    public function configuration(): array
    {
        return config('vanilo.foundation.features.pricing', []);
    }
}
