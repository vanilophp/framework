<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Contracts;

interface ShippingCategory
{
    public function getId(): string|int;

    public function getName(): string;

    public function isFragile(): bool;

    public function isHazardous(): bool;

    public function isStackable(): bool;

    public function isNotShippable(): bool;

    public function requiresTemperatureControl(): bool;

    public function requiresSignature(): bool;
}
