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

namespace Vanilo\Attributes\Types;

use Vanilo\Attributes\Contracts\AttributeType;

class Number implements AttributeType
{
    public function getName(): string
    {
        return __('Number attribute');
    }
}
