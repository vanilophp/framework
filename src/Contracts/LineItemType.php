<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

use Konekt\Extend\Contracts\Registerable;

interface LineItemType extends Registerable
{
    public function isShippable(): bool;

    public function isPhysical(): bool;

    public function isAService(): bool;

    public function isDigital(): bool;

    public function hasPrice(): bool;
}
