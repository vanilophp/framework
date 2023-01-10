<?php

declare(strict_types=1);

/**
 * Contains the Configurable interface.
 *
 * @copyright   Copyright (c) 2023 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-01-10
 *
 */

namespace Vanilo\Contracts;

interface Configurable
{
    public function configuration(): Configuration;

    public function hasConfiguration(): bool;

    public function doesntHaveConfiguration();
}
