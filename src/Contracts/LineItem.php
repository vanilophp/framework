<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface LineItem
{
    public function getName(): string;

    public function getQuantity(): int|float;

    public function isShippable(): bool;

    public function isPhysical(): bool;

    public function isService(): bool;

    public function isDigital(): bool;
}
