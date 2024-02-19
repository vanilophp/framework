<?php

declare(strict_types=1);

/**
 * Contains the Feature interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-07
 *
 */

namespace Vanilo\Contracts;

interface Feature
{
    public function isEnabled(): bool;

    public function isDisabled(): bool;

    public function configuration(): array;
}
