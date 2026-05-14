<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface ShippableItem extends LineItem
{
    public function getShippingCategoryId(): null|int|string;

    public function weight(): float;

    public function weightUnit(): string;

    public function dimension(): ?Dimension;
}
