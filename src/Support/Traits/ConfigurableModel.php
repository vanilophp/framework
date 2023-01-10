<?php

declare(strict_types=1);

/**
 * Contains the ConfigurableModel trait.
 *
 * @copyright   Copyright (c) 2023 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-01-10
 *
 */

namespace Vanilo\Support\Traits;

use Vanilo\Contracts\Configuration;

trait ConfigurableModel
{
    protected string $configurationFieldName = 'configuration';

    public function configuration(): Configuration
    {

    }

    public function hasConfiguration(): bool
    {

    }

    public function doesntHaveConfiguration()
    {

    }
}
