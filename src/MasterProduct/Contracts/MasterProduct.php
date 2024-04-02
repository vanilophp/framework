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

/**
 * @property-read Collection|MasterProductVariant[] $variants
 */
interface MasterProduct
{
    public function isActive(): bool;

    public function title(): string;

    public function variants(): HasMany;

    public function createVariant(array $attributes): MasterProductVariant;
}
