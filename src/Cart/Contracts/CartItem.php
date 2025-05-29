<?php

declare(strict_types=1);

/**
 * Contains the CartItem interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */

namespace Vanilo\Cart\Contracts;

use Vanilo\Contracts\CheckoutSubjectItem;

interface CartItem extends CheckoutSubjectItem
{
    public function hasParent(): bool;

    public function getParent(): ?self;
}
