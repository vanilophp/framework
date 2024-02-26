<?php

declare(strict_types=1);

/**
 * Contains the BelongsToTaxCategory trait.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Taxes\Contracts\TaxCategory;
use Vanilo\Taxes\Models\TaxCategoryProxy;

/**
 * @property integer|null $tax_category_id
 * @property-read TaxCategory|null $taxCategory
 */
trait BelongsToTaxCategory
{
    public function taxCategory(): BelongsTo
    {
        return $this->belongsTo(TaxCategoryProxy::modelClass());
    }

    public function getTaxCategory(): ?TaxCategory
    {
        return $this->taxCategory;
    }
}
