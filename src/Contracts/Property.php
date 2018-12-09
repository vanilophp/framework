<?php
/**
 * Contains the Property interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Contracts;

interface Property
{
    public function getType(): PropertyType;
}
