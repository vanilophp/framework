<?php
/**
 * Contains the Number class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Types;

use Vanilo\Properties\Contracts\PropertyType;

class Number implements PropertyType
{
    public function getName(): string
    {
        return __('Number');
    }

    public function transformValue(string $value, ?array $settings)
    {
        return (float) $value;
    }
}
