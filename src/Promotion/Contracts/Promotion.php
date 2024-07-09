<?php

declare(strict_types=1);

/**
 * Contains the Promotion interface.
 *
 * @copyright   Copyright (c) 2024 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-07-09
 *
 */

namespace Vanilo\Promotion\Contracts;

interface Promotion
{
    public function isValid(?\DateTimeInterface $at = null): bool;
}
