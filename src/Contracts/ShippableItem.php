<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface ShippableItem extends Configurable
{
    public function getShippingCategoryId(): null|int|string;

    public function getQuantity(): int;

    public function weight(): float;

    public function weightUnit(): string;

    public function dimension(): ?Dimension;
}
