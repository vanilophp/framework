<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface LineItemType extends \Konekt\Enum\EnumInterface
{
    public function isShippable(): bool;

    public function isPhysical(): bool;

    public function isAService(): bool;

    public function isDigital(): bool;

    public function hasPrice(): bool;
}
