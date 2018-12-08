<?php
/**
 * Contains the AttributeType interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Contracts;

interface AttributeType
{
    public function getName(): string;

    public function transformValue(string $value, ?array $settings);
}
