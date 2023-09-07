<?php

declare(strict_types=1);

/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */

namespace Vanilo\Foundation\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Category\Traits\HasTaxons;
use Vanilo\Channel\Traits\Channelable;
use Vanilo\Contracts\Buyable;
use Vanilo\Foundation\Traits\LoadsMediaConversionsFromConfig;
use Vanilo\Product\Models\Product as BaseProduct;
use Vanilo\Properties\Traits\HasPropertyValues;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Support\Traits\HasImagesFromMediaLibrary;
use Vanilo\Taxes\Traits\BelongsToTaxCategory;

class Product extends BaseProduct implements Buyable, HasMedia
{
    use BelongsToTaxCategory;
    use BuyableModel;
    use Channelable;
    use InteractsWithMedia;
    use HasImagesFromMediaLibrary;
    use LoadsMediaConversionsFromConfig;
    use HasTaxons;
    use HasPropertyValues;

    protected $casts = [
        'price' => 'float',
        'original_price' => 'float',
        'weight' => 'float',
        'height' => 'float',
        'width' => 'float',
        'length' => 'float',
        'stock' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_sale_at' => 'datetime',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->loadConversionsFromVaniloConfig();
    }
}
