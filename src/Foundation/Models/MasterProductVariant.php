<?php

declare(strict_types=1);

/**
 * Contains the MasterProductVariant class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-08-23
 *
 */

namespace Vanilo\Foundation\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Contracts\Buyable;
use Vanilo\Foundation\Traits\LoadsMediaConversionsFromConfig;
use Vanilo\MasterProduct\Models\MasterProductVariant as BaseMasterProductVariant;
use Vanilo\Properties\Traits\HasPropertyValues;
use Vanilo\Shipment\Contracts\ShippingCategory;
use Vanilo\Shipment\Models\ShippingCategoryProxy;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Support\Traits\HasImagesFromMediaLibrary;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Traits\BelongsToTaxCategory;
use Vanilo\Video\Traits\HasVideos;

/**
 *
 * @property int|null $shipping_category_id
 * @property-read \Vanilo\Shipment\Contracts\ShippingCategory|null $shippingCategory
 *
 */
class MasterProductVariant extends BaseMasterProductVariant implements Buyable, HasMedia, Taxable
{
    use BelongsToTaxCategory;
    use BuyableModel;
    use HasPropertyValues;
    use HasImagesFromMediaLibrary;
    use InteractsWithMedia;
    use LoadsMediaConversionsFromConfig;
    use HasVideos;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->loadConversionsFromVaniloConfig();
    }

    public function hasOwnShippingCategory(): bool
    {
        return null !== $this->getRawOriginal('shipping_category_id');
    }

    public function shippingCategory(): BelongsTo
    {
        if ($this->hasOwnShippingCategory() || is_null($this->masterProduct)) {
            return $this->belongsTo(ShippingCategoryProxy::modelClass());
        } else {
            return $this->masterProduct->shippingCategory();
        }
    }

    public function getShippingCategory(): ?ShippingCategory
    {
        return $this->shippingCategory;
    }

    protected function shippingCategoryId(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? $this->masterProduct?->shipping_category_id : intval($value),
        );
    }
}
