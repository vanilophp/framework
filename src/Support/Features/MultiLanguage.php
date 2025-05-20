<?php

declare(strict_types=1);

/**
 * Contains the MultiLanguage class.
 *
 * @copyright   Copyright (c) 2025 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2025-05-20
 *
 */

namespace Vanilo\Support\Features;

use Illuminate\Support\Arr;
use Vanilo\Contracts\Feature;

class MultiLanguage implements Feature
{
    public function isEnabled(): bool
    {
        return config('vanilo.features.multi_language.is_enabled', false) && null !== concord()->module('multi_language');
    }

    public function isDisabled(): bool
    {
        return !$this->isEnabled();
    }

    public function configuration(): array
    {
        return Arr::wrap(config('vanilo.features.multi_language'));
    }
}
