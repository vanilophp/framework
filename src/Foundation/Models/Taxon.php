<?php

declare(strict_types=1);

/**
 * Contains the Taxon class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-06
 *
 */

namespace Vanilo\Foundation\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Category\Models\Taxon as BaseTaxon;
use Vanilo\Contracts\HasImages;
use Vanilo\Foundation\Traits\LoadsMediaConversionsFromConfig;
use Vanilo\Product\Contracts\Product;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Support\Traits\HasImagesFromMediaLibrary;
use Vanilo\Video\Traits\HasVideos;

class Taxon extends BaseTaxon implements HasMedia, HasImages
{
    use InteractsWithMedia;
    use HasImagesFromMediaLibrary;
    use LoadsMediaConversionsFromConfig;
    use HasVideos;

    public function products(): MorphToMany
    {
        return $this->morphedByMany(
            ProductProxy::modelClass(),
            'model',
            'model_taxons',
            'taxon_id',
            'model_id'
        );
    }

    public function addProduct(Product $product): void
    {
        $this->products()->attach($product);
    }

    public function addProducts(iterable $products)
    {
        foreach ($products as $product) {
            if (!$product instanceof Product) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Every element passed to addProduct must be a Product object. Given `%s`.',
                        is_object($product) ? get_class($product) : gettype($product)
                    )
                );
            }
        }

        return $this->products()->saveMany($products);
    }

    public function removeProduct(Product $product)
    {
        return $this->products()->detach($product);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->loadConversionsFromVaniloConfig();
    }
}
