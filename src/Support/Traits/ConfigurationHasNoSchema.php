<?php

declare(strict_types=1);

/**
 * Contains the ConfigurationHasNoSchema trait.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-27
 *
 */

namespace Vanilo\Support\Traits;

use Vanilo\Contracts\Schematized;

trait ConfigurationHasNoSchema
{
    public function getConfigurationSchema(): ?Schematized
    {
        return null;
    }
}
