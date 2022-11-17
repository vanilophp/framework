<?php

declare(strict_types=1);

/**
 * Contains the VariantWithProperties test class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-17
 *
 */

namespace Vanilo\MasterProduct\Tests\Examples;

use Vanilo\MasterProduct\Models\MasterProductVariant;
use Vanilo\Properties\Traits\HasPropertyValues;

class VariantWithProperties extends MasterProductVariant
{
    use HasPropertyValues;

    protected $table = 'master_product_variants';
}
