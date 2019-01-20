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

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Vanilo\Category\Contracts\Taxon;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Contracts\Buyable;
use Vanilo\Properties\Models\PropertyValueProxy;
use Vanilo\Support\Traits\BuyableImageSpatieV7;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Product\Models\Product as BaseProduct;

class Product extends BaseProduct implements Buyable, HasMedia
{
    protected const DEFAULT_THUMBNAIL_WIDTH  = 250;
    protected const DEFAULT_THUMBNAIL_HEIGHT = 250;
    protected const DEFAULT_THUMBNAIL_FIT    = Manipulations::FIT_CROP;

    use BuyableModel, BuyableImageSpatieV7, HasMediaTrait;

    protected $dates = ['created_at', 'updated_at', 'last_sale_at'];

    public function taxons(): MorphToMany
    {
        return $this->morphToMany(
            TaxonProxy::modelClass(), 'model', 'model_taxons', 'model_id', 'taxon_id'
        );
    }

    public function propertyValues(): MorphToMany
    {
        return $this->morphToMany(PropertyValueProxy::modelClass(), 'model',
            'model_property_values', 'model_id', 'property_value_id'
        );
    }

    public function addTaxon(Taxon $taxon): void
    {
        $this->taxons()->attach($taxon);
    }

    public function addTaxons(iterable $taxons)
    {
        foreach ($taxons as $taxon) {
            if (! $taxon instanceof Taxon) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Every element passed to addTaxons must be a Taxon object. Given `%s`.',
                        is_object($taxon) ? get_class($taxon) : gettype($taxon)
                    )
                );
            }
        }

        return $this->taxons()->saveMany($taxons);
    }

    public function removeTaxon(Taxon $taxon)
    {
        return $this->taxons()->detach($taxon);
    }

    public function registerMediaConversions(Media $media = null)
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
}
