<?php

declare(strict_types=1);

/**
 * Contains the Dimension interface.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-01-28
 *
 */

namespace Vanilo\Contracts;

interface Dimension
{
    public function width(): float;

    public function height(): float;

    public function length(): float;
}
