<?php

declare(strict_types=1);

/**
 * Contains the IndependentMaster class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-29
 *
 */

namespace Vanilo\Foundation\Tests\Examples;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vanilo\MasterProduct\Contracts\MasterProduct;
use Vanilo\MasterProduct\Contracts\MasterProductVariant;

class IndependentMaster extends Model implements MasterProduct
{
    public function variants(): HasMany
    {
    }

    public function createVariant(array $attributes): MasterProductVariant
    {
    }

    public function isActive(): bool
    {
    }

    public function title(): string
    {
    }
}
