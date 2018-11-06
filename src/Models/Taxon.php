<?php
/**
 * Contains the Taxon class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-11-06
 *
 */

namespace Vanilo\Framework\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Vanilo\Category\Models\Taxon as BaseTaxon;
use Vanilo\Product\Contracts\Product;
use Vanilo\Product\Models\ProductProxy;

class Taxon extends BaseTaxon
{
    public function products(): MorphToMany
    {
        return $this->morphedByMany(
            ProductProxy::modelClass(), 'model', 'model_taxons', 'taxon_id', 'model_id'
        );
    }

    public function addProduct(Product $product): void
    {
        $this->products()->attach($product);
    }

    public function addProducts(iterable $products)
    {
        foreach ($products as $product) {
            if (! $product instanceof Product) {
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
}
