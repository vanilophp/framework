<?php

declare(strict_types=1);

/**
 * Contains the UnknownPropertyException class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-04-22
 *
 */

namespace Vanilo\Properties\Exceptions;

use RuntimeException;

class UnknownPropertyException extends RuntimeException
{
    public static function createFromSlug(string $slug): self
    {
        return new self("The property `{$slug}` does not exist.");
    }
}
