<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface ItemCollection extends \IteratorAggregate, \ArrayAccess, \Countable
{
    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function clear(): void;
}

