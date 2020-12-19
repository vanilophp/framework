<?php
/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */

namespace Vanilo\Framework\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Category\Traits\HasTaxons;
use Vanilo\Contracts\Buyable;
use Vanilo\Framework\Traits\LoadsMediaConversionsFromConfig;
use Vanilo\Properties\Traits\HasPropertyValues;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Product\Models\Product as BaseProduct;
use Vanilo\Support\Traits\HasImagesFromMediaLibrary;

class Product extends BaseProduct implements Buyable, HasMedia
{
    use BuyableModel, InteractsWithMedia, HasImagesFromMediaLibrary, LoadsMediaConversionsFromConfig, HasTaxons, HasPropertyValues;

    protected $dates = ['created_at', 'updated_at', 'last_sale_at'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->loadConversionsFromVaniloConfig();
    }
}
