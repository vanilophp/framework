<?php
/**
 * Contains the Boolean class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Types;

use Vanilo\Properties\Contracts\PropertyType;

class Boolean implements PropertyType
{
    private const FALSE_VALUES = ['false', '0', '', 'null', 'no'];

    public function getName(): string
    {
        return __('Boolean');
    }

    public function transformValue(string $value, ?array $settings)
    {
        return in_array(strtolower($value), static::FALSE_VALUES) ? false : true;
    }
}
