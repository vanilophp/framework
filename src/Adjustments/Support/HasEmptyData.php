<?php

declare(strict_types=1);

/**
 * Contains the HasEmptyData trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Support;

trait HasEmptyData
{
    public function getData(): array
    {
        return [];
    }
}
