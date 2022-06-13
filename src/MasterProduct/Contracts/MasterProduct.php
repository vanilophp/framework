<?php

declare(strict_types=1);

/**
 * Contains the MasterProduct interface.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Vanilo\Product\Contracts\Product;

/**
 * @property-read Collection|MasterProductVariant[] $variants
 */
interface MasterProduct extends Product
{
    public function variants(): HasMany;
}
