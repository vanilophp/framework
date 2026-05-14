<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface LineItemContainer
{
    public function getLineItems(): LineItemCollection;
}
