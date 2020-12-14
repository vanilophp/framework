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

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Category\Traits\HasTaxons;
use Vanilo\Contracts\Buyable;
use Vanilo\Properties\Traits\HasPropertyValues;
use Vanilo\Support\Traits\BuyableImageSpatieV8;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Product\Models\Product as BaseProduct;

class Product extends BaseProduct implements Buyable, HasMedia
{
    use BuyableModel, BuyableImageSpatieV8, InteractsWithMedia, HasTaxons, HasPropertyValues;

    protected const DEFAULT_THUMBNAIL_WIDTH  = 250;
    protected const DEFAULT_THUMBNAIL_HEIGHT = 250;
    protected const DEFAULT_THUMBNAIL_FIT    = Manipulations::FIT_CROP;

    protected $dates = ['created_at', 'updated_at', 'last_sale_at'];

    public function registerMediaConversions(Media $media = null): void
    {
        foreach (config('vanilo.framework.image.variants', []) as $name => $settings) {
            $conversion = $this->addMediaConversion($name)
                 ->fit(
                     $settings['fit'] ?? static::DEFAULT_THUMBNAIL_FIT,
                     $settings['width'] ?? static::DEFAULT_THUMBNAIL_WIDTH,
                     $settings['height'] ?? static::DEFAULT_THUMBNAIL_HEIGHT
                 );
            if (!($settings['queued'] ?? false)) {
                $conversion->nonQueued();
            }
        }
    }

    public function getThumbnailUrl(string $conversionName = 'thumbnail'): ?string
    {
        $media = $this->getFirstMedia('default', ['isPrimary' => true]);

        return $media ? $media->getUrl($conversionName) : $this->getFirstMediaUrl('default', $conversionName);
    }
}
