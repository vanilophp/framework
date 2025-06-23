<?php

declare(strict_types=1);

/**
 * Contains the MasterProduct class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-29
 *
 */

namespace Vanilo\Foundation\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Category\Traits\HasTaxons;
use Vanilo\Channel\Traits\Channelable;
use Vanilo\Foundation\Traits\LoadsMediaConversionsFromConfig;
use Vanilo\MasterProduct\Models\MasterProduct as BaseMasterProduct;
use Vanilo\Properties\Traits\HasPropertyValues;
use Vanilo\Shipment\Traits\BelongsToShippingCategory;
use Vanilo\Support\Traits\HasImagesFromMediaLibrary;
use Vanilo\Taxes\Traits\BelongsToTaxCategory;
use Vanilo\Video\Traits\HasVideos;

/**
 * @property-read int $units_sold
 * @property-read null|Carbon $last_sale_at
 */
class MasterProduct extends BaseMasterProduct implements HasMedia
{
    use BelongsToTaxCategory;
    use BelongsToShippingCategory;
    use Channelable;
    use InteractsWithMedia;
    use HasImagesFromMediaLibrary;
    use LoadsMediaConversionsFromConfig;
    use HasTaxons;
    use HasPropertyValues;
    use HasVideos;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->loadConversionsFromVaniloConfig();
    }

    protected function unitsSold(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->variants()->sum('units_sold'),
        );
    }

    protected function lastSaleAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->variants()->max('last_sale_at'),
        );
    }
}
