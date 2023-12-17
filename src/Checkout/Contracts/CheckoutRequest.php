<?php

declare(strict_types=1);

/**
 * Contains the CheckoutRequest interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-12-06
 *
 */

namespace Vanilo\Checkout\Contracts;

use ArrayAccess;
use Vanilo\Contracts\Address;

interface CheckoutRequest extends ArrayAccess
{
    public function has($key);

    public function all($keys = null);

    public function input($key = null, $default = null);

    public function old($key = null, $default = null);

    public function replace(array $input);

    public function doesntWantShipping(): bool;

    public function wantsShipping(): bool;

    public function wantsShippingToBillingAddress(): bool;

    public function getShippingAddress(): null|array|Address;
}
