<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface Identifiable
{
    /**
     * This is intentionally identical to the Eloquent Model's `getKey()` method
     *
     * @return mixed
     */
    public function getKey();
}
