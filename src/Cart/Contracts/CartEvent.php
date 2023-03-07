<?php

declare(strict_types=1);

/**
 * Contains the CartEvent interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-07
 *
 */

namespace Vanilo\Cart\Contracts;

interface CartEvent
{
    public function getCart(): Cart;
}
