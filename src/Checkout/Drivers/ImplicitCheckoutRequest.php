<?php

declare(strict_types=1);

/**
 * Contains the ImplicitCheckoutRequest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-12-06
 *
 */

namespace Vanilo\Checkout\Drivers;

use Illuminate\Http\Request;
use Vanilo\Checkout\Contracts\CheckoutRequest;
use Vanilo\Contracts\Address;

/**
 * This class represents a checkout request that implicitly contains
 * the fields required for the checkout, without implementing the
 * CheckoutRequest interface. It decorates the via duck-typing
 */
final class ImplicitCheckoutRequest implements CheckoutRequest
{
    public function __construct(
        private Request $request,
    ) {
    }

    public static function from(Request|CheckoutRequest $request): CheckoutRequest
    {
        if ($request instanceof CheckoutRequest) {
            return $request;
        }

        return new self($request);
    }

    public function doesntWantShipping(): bool
    {
        return (bool) $this->input('no_shipping');
    }

    public function wantsShipping(): bool
    {
        return !$this->doesntWantShipping();
    }

    public function wantsShippingToBillingAddress(): bool
    {
        return (bool) $this->input('ship_to_billing_address');
    }

    public function getShippingAddress(): null|array|Address
    {
        $address = $this->input('shipping_address');

        return match (true) {
            empty($address) => null,
            is_array($address) => $address,
            default => null,
        };
    }

    public function has($key)
    {
        return $this->request->has($key);
    }

    public function all($keys = null)
    {
        return $this->request->all($keys);
    }

    public function input($key = null, $default = null)
    {
        return $this->request->input($key, $default);
    }

    public function old($key = null, $default = null)
    {
        return $this->request->old($key, $default);
    }

    public function replace(array $input)
    {
        return $this->request->replace($input);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->request->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->request->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->request->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->request->offsetUnset($offset);
    }
}
