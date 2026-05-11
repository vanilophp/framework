<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

use Konekt\Extend\Contracts\Registerable;

interface LineItemType extends Registerable
{
    public function hasPrice(): bool;
}
