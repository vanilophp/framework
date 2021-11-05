<?php

declare(strict_types=1);

/**
 * Contains the MaterialPropertyType class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Tests\Examples;

use Vanilo\Properties\Contracts\PropertyType;

class MaterialPropertyType implements PropertyType
{
    public function getName(): string
    {
        return 'material';
    }

    public function transformValue(string $value, ?array $settings)
    {
        return $value;
    }
}
