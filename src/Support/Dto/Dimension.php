<?php

declare(strict_types=1);

/**
 * Contains the Dimension class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Support\Dto;

use Vanilo\Contracts\Dimension as DimensionContract;

final class Dimension implements DimensionContract
{
    public function __construct(
        private float $width,
        private float $height,
        private float $length,
    ) {
    }

    public function width(): float
    {
        return $this->width;
    }

    public function height(): float
    {
        return $this->height;
    }

    public function length(): float
    {
        return $this->length;
    }
}
