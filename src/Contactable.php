<?php
/**
 * Contains the Contactable interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */

namespace Vanilo\Contracts;

interface Contactable
{
    public function getEmail(): ?string;

    public function getPhone(): ?string;
}
