<?php
/**
 * Contains the PropertyType interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Contracts;

interface PropertyType
{
    public function getName(): string;

    public function transformValue(string $value, ?array $settings);
}
