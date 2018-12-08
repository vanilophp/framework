<?php
/**
 * Contains the MaterialAttributeType class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Tests\Examples;

use Vanilo\Attributes\Contracts\AttributeType;

class MaterialAttributeType implements AttributeType
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
