<?php

declare(strict_types=1);

/**
 * Contains the SearchEngine class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-10-18
 *
 */

namespace Vanilo\Support\Features;

use Vanilo\Contracts\Feature;

class SearchEngine implements Feature
{
    public function isEnabled(): bool
    {
        return config('vanilo.features.pricing.search_engine', false)
            && null !== concord()->module('search');
    }

    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    public function configuration(): array
    {
        return config('vanilo.features.search_engine', []);
    }
}
