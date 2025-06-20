<?php

declare(strict_types=1);

namespace Vanilo\Shipment\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vanilo\Shipment\Contracts\ShippingCategory;
use Vanilo\Shipment\Models\ShippingCategoryProxy;

/**
 * @property integer|null $shipping_category_id
 * @property-read ShippingCategory|null $shippingCategory
 */
trait BelongsToShippingCategory
{
    public function shippingCategory(): BelongsTo
    {
        return $this->belongsTo(ShippingCategoryProxy::modelClass());
    }

    public function getShippingCategory(): ?ShippingCategory
    {
        return $this->shippingCategory;
    }
}
