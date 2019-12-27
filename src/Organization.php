<?php
/**
 * Contains the Organization interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */

namespace Vanilo\Contracts;

interface Organization extends Contactable
{
    /**
     * Returns the Company name
     */
    public function getCompanyName(): ?string;

    /**
     * Customer's tax number (VAT id, tax id, etc)
     */
    public function getTaxNumber(): ?string;
}
