<?php

declare(strict_types=1);

/**
 * Contains the Taxable interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Contracts;

interface Taxable
{
    public function getTaxCategory(): TaxCategory;
}
