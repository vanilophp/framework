<?php
/**
 * Contains the PropertyValue interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Contracts;

interface PropertyValue
{
    /**
     * Returns the transformed value according to the underlying type
     */
    public function getCastedValue();
}
