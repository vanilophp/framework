<?php

declare(strict_types=1);

/**
 * Contains the Shippable interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-26
 *
 */

namespace Vanilo\Contracts;

interface Shippable
{
    public function getShippingAddress(): ?Address;

    public function needsShipping(): bool;

    public function getShippableItems(): ShippableItemCollection;

    public function getShippingMethodId(): null|int|string;

    public function weight(): float;

    public function weightUnit(): string;

    public function dimension(): ?Dimension;
}
