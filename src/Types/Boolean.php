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

namespace Vanilo\Attributes\Types;

use Vanilo\Attributes\Contracts\AttributeType;

class Boolean implements AttributeType
{
    public function getName(): string
    {
        return __('Boolean attribute');
    }

    public function transformValue(string $value, ?array $settings)
    {
        return (bool) $value;
    }
}
